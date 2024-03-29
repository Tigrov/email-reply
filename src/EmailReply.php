<?php
/**
 * @link https://github.com/tigrov/email-reply
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\emailReply;

class EmailReply
{
    /** @var ModelInterface[] with keys as email prefixes */
    public $classesMap = [];

    /** @var string[] allowed host names for parsing income emails */
    public $allowedHosts = [];

    /** @var string String to separate class key (email prefix) and primary key in email address */
    public $separator = '';

    /** @var string for multiple primary keys */
    public $paramSeparator = '-';

    /** @var callable will be called if reply email does not match any names from self::$classesMap */
    public $defaultCallback;

    /**
     * @var callable to encode reply email mailbox (the part before @ in the email address)
     * function (string $mailbox, string $host = '') string { return $mailbox; }
     */
    public $encodeCallback;

    /**
     * @var callable to decode reply email mailbox (the part before @ in the email address)
     * function (string $mailbox, string $host = '') ?string { return $mailbox; }
     */
    public $decodeCallback;

    /**
     * EmailReply constructor.
     * @param array $config
     * @throws \ReflectionException
     */
    public function __construct($config = [])
    {
        $this->configure($config);
    }

    /**
     * @param $config
     * @throws \ReflectionException
     */
    public function configure($config)
    {
        if (isset($config['classesMap'])) {
            $this->classesMap = $this->normalizeClassesMap($config['classesMap']);
            unset($config['classesMap']);
        }

        foreach ($config as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * @param $classes
     * @return array
     * @throws \ReflectionException
     */
    private function normalizeClassesMap($classes)
    {
        $map = [];
        foreach ($classes as $name => $className) {
            if (is_int($name)) {
                $name = (new \ReflectionClass($className))->getShortName();
            }

            $map[strtolower($name)] = $className;
        }

        return $map;
    }

    /**
     * Returns email address for a model
     * @param ModelInterface $model the model
     * @param string|null $host the host for the email address
     * @return string
     */
    public function getReplyEmail($model, $host = null)
    {
        $className = get_class($model);
        $name = array_search($className, $this->classesMap);
        if ($name === false) {
            $name = (new \ReflectionClass($className))->getShortName();
        }

        $values = $model->paramValues();
        $values = is_array($values) ? implode($this->paramSeparator, $values) : $values;

        if ($host === null) {
            $host = reset($this->allowedHosts)
                ?: $_SERVER['HTTP_X_FORWARDED_HOST']
                ?? $_SERVER['HTTP_HOST']
                ?? $_SERVER['SERVER_NAME']
                ?? null;

            if ($host === null) {
                throw new \Exception('Host must be specified. You can add it in config to EmailReply::$allowedHosts');
            }
        }

        $mailbox = $name . $this->separator . $values;
        if ($this->encodeCallback) {
            $mailbox = call_user_func($this->encodeCallback, $mailbox, $host);
        }

        return strtolower($mailbox . '@' . $host);
    }

    /**
     * Reads and processes email messages
     * @param \Ddeboer\Imap\MessageInterface[] $messages email messages
     * @param bool $delete indicator to delete processed email messages
     */
    public function read($messages, $delete = true)
    {
        foreach ($messages as $message) {
            if (!Helper::isAutoresponse($message)) {
                $to = array_merge($message->getTo(), $message->getCc(), $message->getBcc());
                $content = $message->getBodyHtml() ?: $message->getBodyText() ?: $message->getDecodedContent();
                $model = $this->getModel($to, $content);
                if ($model) {
                    $model->emailReply($message);
                } elseif ($this->defaultCallback) {
                    call_user_func($this->defaultCallback, $message);
                }
            }
            if ($delete) {
                $message->delete();
            }
        }
    }

    /**
     * Returns a model corresponding to a reply email
     * @param \Ddeboer\Imap\Message\EmailAddress[] $emails emails to find the reply email of model
     * @param string $content reply content to find the reply email of model
     * @return ModelInterface|null
     */
    public function getModel($emails, $content = '')
    {
        $regex = $this->getEmailRegex();
        return $this->getModelFromEmails($emails, $regex)
            ?: $this->getModelFromContent($content, $regex);
    }

    /**
     * Returns an email address matched to regex from emails
     * @param \Ddeboer\Imap\Message\EmailAddress[] $emails emails to compare
     * @param string $regex
     * @return ModelInterface|null
     */
    private function getModelFromEmails($emails, $regex)
    {
        foreach ($emails as $email) {
            $emailAddtess = $email->getAddress();
            if ($this->decodeCallback) {
                $decoded = call_user_func($this->decodeCallback, $email->getMailbox(), $email->getHostname());
                if ($decoded) {
                    $emailAddtess = $decoded . '@' . $email->getHostname();
                }
            }

            if (preg_match('~^' . $regex . '$~i', $emailAddtess, $matches)) {
                return $this->matchModel($matches);
            }
        }

        return null;
    }

    /**
     * Returns an email address matched to regex from content
     * @param string $content
     * @param string $regex
     * @return ModelInterface|null
     */
    private function getModelFromContent($content, $regex)
    {
        if ($content) {
            if ($this->decodeCallback) {
                $emailList = Helper::findEmails($content);
                foreach ($emailList as $email) {
                    list($mailbox, $host) = explode('@', $email, 2);
                    $decoded = call_user_func($this->decodeCallback, $mailbox, $host);
                    if ($decoded) {
                        $emailAddtess = $decoded . '@' . $host;
                        if (preg_match('~^' . $regex . '$~i', $emailAddtess, $matches)) {
                            return $this->matchModel($matches);
                        }
                    }
                }
            } elseif (preg_match('~\b' . $regex . '\b~i', $content, $matches)) {
                return $this->matchModel($matches);
            }
        }

        return null;
    }

    /**
     * Returns a model matched to regex result
     * @param $matches
     * @return ModelInterface|null
     */
    private function matchModel($matches)
    {
        $name = strtolower($matches[1]);
        /** @var string|ModelInterface $className */
        $className = $this->classesMap[$name];
        $paramNames = $className::paramNames();
        $paramValues = explode($this->paramSeparator, $matches[2]);
        $params = array_combine($paramNames, $paramValues);
        return $className::buildFromParams($params);
    }

    /**
     * Returns regex to find reply email for model
     * @return string
     */
    public function getEmailRegex()
    {
        $names = array_keys($this->classesMap);
        $names = array_map('preg_quote', $names);
        $separator = preg_quote($this->separator);
        $paramSeparator = preg_quote($this->paramSeparator);

        $regex = '(' . implode('|', $names) . ')'
            . $separator
            . '([\w' . $paramSeparator . ']+)@';

        if ($this->allowedHosts) {
            $hosts = array_map('preg_quote', $this->allowedHosts);
            $regex .= '(' . implode('|', $hosts) . ')';
        } else {
            $regex .= '([\w.\-]+)';
        }

        return $regex;
    }
}