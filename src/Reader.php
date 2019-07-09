<?php
namespace tigrov\emailReply;

use Ddeboer\Imap\Exception\MailboxDoesNotExistException;

class Reader
{
    /**
     * @param \Ddeboer\Imap\MailboxInterface[] $mailboxes
     * @return \Ddeboer\Imap\MessageInterface[]
     */
    public static function getIterator($mailboxModels)
    {
        foreach ($mailboxModels as $mailboxName => $mailbox) {
            foreach ($mailbox->getMessages() as $messageKey => $message) {
                yield $mailboxName . '#' . $messageKey => $message;
            }
        }
    }

    /**
     * @param \Ddeboer\Imap\ConnectionInterface $connection
     * @param string[] $mailboxes
     * @return \Ddeboer\Imap\MailboxInterface[]
     */
    public static function getMailboxModels($connection, $mailboxes = ['INBOX', 'SPAM'])
    {
        $models = [];
        if ($mailboxes) {
            foreach ($mailboxes as $mailboxName) {
                try {
                    $models[$mailboxName] = $connection->getMailbox($mailboxName);
                } catch (MailboxDoesNotExistException $e) {
                    // skip if the mailbox is not found
                }
            }
        } else {
            $models = $connection->getMailboxes();
        }

        return $models;
    }
}