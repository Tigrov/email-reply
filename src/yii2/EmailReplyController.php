<?php
/**
 * @link https://github.com/tigrov/email-reply
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\emailReply\yii2;

use Ddeboer\Imap\Server;
use tigrov\emailReply\EmailReply;
use tigrov\emailReply\Reader;
use yii\console\Controller;

/**
 * Email reply console controller for Yii2 framework
 * to run use command `yii email-reply` in console or cron
 * it will read mailbox messages and process them by `ModelInterface::emailReply($message)`
 */
class EmailReplyController extends Controller
{
    public $defaultAction = 'read';

    public function actionRead()
    {
        /** @var \Swift_SmtpTransport $transport */
        $transport = \Yii::$app->mailer->transport;

        $server = new Server($transport->getHost(), $transport->getPort());
        $connection = $server->authenticate($transport->getUsername(), $transport->getPassword());

        $mailboxModels = Reader::getMailboxModels($connection);
        $messages = Reader::getIterator($mailboxModels);

        /** @var EmailReply $emailReply */
        $emailReply = \Yii::$app->emailReply;
        $emailReply->read($messages);

        $connection->expunge();
    }
}