<?php
/**
 * @link https://github.com/tigrov/email-reply
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\emailReply;

class Helper
{
    /**
     * Find all email addresses in content
     * @param $content
     * @return array
     */
    public static function findEmails($content)
    {
        $list = [];
        if (preg_match_all('~[\w.-]+@[a-z0-9.-]+~i', $content, $matches)) {
            $list = array_map(function($v){return trim($v,'.');}, $matches[0]);
        }

        return $list;
    }

    /**
     * Try to detect if email message is auto response
     * @see https://github.com/jpmckinney/multi_mail/wiki/Detecting-autoresponders
     * @see https://github.com/Exim/exim/wiki/EximAutoReply
     * @param \Ddeboer\Imap\MessageInterface $message
     * @return bool
     */
    public static function isAutoresponse($message)
    {
        if (!$message->getReturnPath()) {
            return true;
        }

        $headers = $message->getHeaders();
        if ($test = $headers->get('auto_submitted')) {
            if (strtolower($test) !== 'no') {
                return true;
            }
        }

        $headerNames = [
            'autorespond', 'x_autorespond', 'x_autogenerated', 'x_autoreply_from', 'x_mail_autoreply', 'x_autoreply',
            'list_id', 'list_help', 'list_unsubscribe', 'list_subscribe', 'list_owner', 'list_post', 'list_archive',
            'x_fc_machinegenerated', 'x_cron_env', 'x_ebay_mailtracker', 'x_maxcode_template', 'x_auto_response_suppress',
        ];
        foreach ($headerNames as $name) {
            if ($headers->get($name)) {
                return true;
            }
        }

        if ($test = $headers->get('preference') ?: $headers->get('x_precedence')) {
            if (strtolower($test) === 'auto_reply') {
                return true;
            }
        }

        if ($test = $headers->get('precedence')) {
            if (in_array(strtolower($test), ['bulk', 'list', 'junk', 'auto_reply'])) {
                return true;
            }
        }

        if ($test = $headers->get('x_post_messageclass') ?: $headers->get('delivered_to')) {
            if (stripos($test, 'Autoresponder') !== false) {
                return true;
            }
        }

        $subjectRegex = '~(?:^auto:|auto[\s_.-]*(?:reply|repsonse)|(?:out of|away from)\s.*?(?:office|country|city|town))~i';
        if (preg_match($subjectRegex, $message->getSubject())) {
            return true;
        }

        $fromRegex = '~(?:daemon|nobody|bounce|(?:auto|no)[_.-]?reply|auto[_.-]?responder)~i';
        if (preg_match($fromRegex, $message->getFrom()->getMailbox())) {
            return true;
        }

        return false;
    }
}