<?php
namespace tigrov\tests\unit\emailReply\models;

use tigrov\emailReply\ModelInterface;

class Model implements ModelInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public static function paramNames()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function paramValues()
    {
        return ['id' => $this->id];
    }

    /**
     * @inheritdoc
     */
    public static function buildFromParams($paramValues)
    {
        $id = (int) $paramValues['id'];
        if ($id) {
            $model = new static($id);

            return $model;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function emailReply($message)
    {
        /** @var string $fromEmail email address of the sender */
        $fromEmail = $message->getFrom()->getAddress();

        /** @var string $fromName name of the sender */
        $fromName = $message->getFrom()->getName();

        /** @var string $content content from the replied message */
        $content = $message->getBodyHtml() ?: $message->getBodyText() ?: $message->getDecodedContent();

        // Parse the content to get only answer
        // $content = EmailReplyParser\EmailReplyParser::parseReply($content);

        // To do something with $content
        $text = '#' . $this->id . ' ' . $fromName . ' (' . $fromEmail . ') replied:' . "\n" . $content . "\n\n";
        file_put_contents(__DIR__ . '/../log/reply.log', $text, FILE_APPEND);
    }
}