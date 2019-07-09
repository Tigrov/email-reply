<?php

namespace tigrov\tests\unit\emailReply\models;

use Ddeboer\Imap\MailboxInterface;
use Ddeboer\Imap\Message\AttachmentInterface;
use Ddeboer\Imap\Message\EmailAddress;
use Ddeboer\Imap\Message\Headers;
use Ddeboer\Imap\Message\Parameters;
use Ddeboer\Imap\Message\PartInterface;
use Ddeboer\Imap\MessageInterface;
use RecursiveIterator;

class Message implements MessageInterface
{
    public $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get raw message headers.
     *
     * @return string
     */
    public function getRawHeaders(): string
    {
        // TODO: Implement getRawHeaders() method.
    }

    /**
     * Get the raw message, including all headers, parts, etc. unencoded and unparsed.
     *
     * @return string the raw message
     */
    public function getRawMessage(): string
    {
        // TODO: Implement getRawMessage() method.
    }

    /**
     * Get message headers.
     *
     * @return Headers
     */
    public function getHeaders(): Headers
    {
        return new Headers(new \stdClass);
    }

    /**
     * Get message id.
     *
     * A unique message id in the form <...>
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        // TODO: Implement getId() method.
    }

    /**
     * Get message sender (from headers).
     *
     * @return null|EmailAddress
     */
    public function getFrom(): ?EmailAddress
    {
        return $this->data['from'] ?? null;
    }

    /**
     * Get To recipients.
     *
     * @return EmailAddress[] Empty array in case message has no To: recipients
     */
    public function getTo(): array
    {
        return $this->data['to'] ?? [];
    }

    /**
     * Get Cc recipients.
     *
     * @return EmailAddress[] Empty array in case message has no CC: recipients
     */
    public function getCc(): array
    {
        return $this->data['cc'] ?? [];
    }

    /**
     * Get Bcc recipients.
     *
     * @return EmailAddress[] Empty array in case message has no BCC: recipients
     */
    public function getBcc(): array
    {
        return $this->data['bcc'] ?? [];
    }

    /**
     * Get Reply-To recipients.
     *
     * @return EmailAddress[] Empty array in case message has no Reply-To: recipients
     */
    public function getReplyTo(): array
    {
        return $this->data['replyTo'] ?? [];
    }

    /**
     * Get Sender.
     *
     * @return EmailAddress[] Empty array in case message has no Sender: recipients
     */
    public function getSender(): array
    {
        // TODO: Implement getSender() method.
    }

    /**
     * Get Return-Path.
     *
     * @return EmailAddress[] Empty array in case message has no Return-Path: recipients
     */
    public function getReturnPath(): array
    {
        return [$this->getFrom()];
    }

    /**
     * Get date (from headers).
     *
     * @return null|\DateTimeImmutable
     */
    public function getDate(): ?\DateTimeImmutable
    {
        // TODO: Implement getDate() method.
    }

    /**
     * Get message size (from headers).
     *
     * @return null|int|string
     */
    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    /**
     * Get message subject (from headers).
     *
     * @return null|string
     */
    public function getSubject(): ?string
    {
        return $this->data['subject'] ?? null;
    }

    /**
     * Get message In-Reply-To (from headers).
     *
     * @return array
     */
    public function getInReplyTo(): array
    {
        // TODO: Implement getInReplyTo() method.
    }

    /**
     * Get message References (from headers).
     *
     * @return array
     */
    public function getReferences(): array
    {
        // TODO: Implement getReferences() method.
    }

    /**
     * Get body HTML.
     *
     * @return null|string Null if message has no HTML message part
     */
    public function getBodyHtml(): ?string
    {
        return $this->data['bodyHtml'] ?? null;
    }

    /**
     * Get body text.
     *
     * @return null|string
     */
    public function getBodyText(): ?string
    {
        return $this->data['bodyText'] ?? null;
    }

    /**
     * Get attachments (if any) linked to this e-mail.
     *
     * @return AttachmentInterface[]
     */
    public function getAttachments(): array
    {
        // TODO: Implement getAttachments() method.
    }

    /**
     * Does this message have attachments?
     *
     * @return bool
     */
    public function hasAttachments(): bool
    {
        // TODO: Implement hasAttachments() method.
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * Get raw part content.
     *
     * @return string
     */
    public function getContent(): string
    {
        // TODO: Implement getContent() method.
    }

    /**
     * Get message recent flag value (from headers).
     *
     * @return null|string
     */
    public function isRecent(): ?string
    {
        // TODO: Implement isRecent() method.
    }

    /**
     * Get message unseen flag value (from headers).
     *
     * @return bool
     */
    public function isUnseen(): bool
    {
        // TODO: Implement isUnseen() method.
    }

    /**
     * Get message flagged flag value (from headers).
     *
     * @return bool
     */
    public function isFlagged(): bool
    {
        // TODO: Implement isFlagged() method.
    }

    /**
     * Get message answered flag value (from headers).
     *
     * @return bool
     */
    public function isAnswered(): bool
    {
        // TODO: Implement isAnswered() method.
    }

    /**
     * Get message deleted flag value (from headers).
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        // TODO: Implement isDeleted() method.
    }

    /**
     * Get message draft flag value (from headers).
     *
     * @return bool
     */
    public function isDraft(): bool
    {
        // TODO: Implement isDraft() method.
    }

    /**
     * Has the message been marked as read?
     *
     * @return bool
     */
    public function isSeen(): bool
    {
        // TODO: Implement isSeen() method.
    }

    /**
     * Mark message as seen.
     *
     * @return bool
     *
     * @deprecated since version 1.1, to be removed in 2.0
     */
    public function maskAsSeen(): bool
    {
        // TODO: Implement maskAsSeen() method.
    }

    /**
     * Mark message as seen.
     *
     * @return bool
     */
    public function markAsSeen(): bool
    {
        // TODO: Implement markAsSeen() method.
    }

    /**
     * Move message to another mailbox.
     *
     * @param MailboxInterface $mailbox
     */
    public function copy(MailboxInterface $mailbox): void
    {
        // TODO: Implement copy() method.
    }

    /**
     * Move message to another mailbox.
     *
     * @param MailboxInterface $mailbox
     */
    public function move(MailboxInterface $mailbox): void
    {
        // TODO: Implement move() method.
    }

    /**
     * Delete message.
     */
    public function delete(): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * Undelete message.
     */
    public function undelete(): void
    {
        // TODO: Implement undelete() method.
    }

    /**
     * Set Flag Message.
     *
     * @param string $flag \Seen, \Answered, \Flagged, \Deleted, and \Draft
     *
     * @return bool
     */
    public function setFlag(string $flag): bool
    {
        // TODO: Implement setFlag() method.
    }

    /**
     * Clear Flag Message.
     *
     * @param string $flag \Seen, \Answered, \Flagged, \Deleted, and \Draft
     *
     * @return bool
     */
    public function clearFlag(string $flag): bool
    {
        // TODO: Implement clearFlag() method.
    }

    /**
     * Get message number (from headers).
     *
     * @return int
     */
    public function getNumber(): int
    {
        // TODO: Implement getNumber() method.
    }

    /**
     * Part charset.
     *
     * @return null|string
     */
    public function getCharset(): ?string
    {
        // TODO: Implement getCharset() method.
    }

    /**
     * Part type.
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        // TODO: Implement getType() method.
    }

    /**
     * Part subtype.
     *
     * @return null|string
     */
    public function getSubtype(): ?string
    {
        // TODO: Implement getSubtype() method.
    }

    /**
     * Part encoding.
     *
     * @return null|string
     */
    public function getEncoding(): ?string
    {
        // TODO: Implement getEncoding() method.
    }

    /**
     * Part disposition.
     *
     * @return null|string
     */
    public function getDisposition(): ?string
    {
        // TODO: Implement getDisposition() method.
    }

    /**
     * Part description.
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * Part bytes.
     *
     * @return null|int|string
     */
    public function getBytes()
    {
        // TODO: Implement getBytes() method.
    }

    /**
     * Part lines.
     *
     * @return null|string
     */
    public function getLines(): ?string
    {
        // TODO: Implement getLines() method.
    }

    /**
     * Part parameters.
     *
     * @return Parameters
     */
    public function getParameters(): Parameters
    {
        // TODO: Implement getParameters() method.
    }

    /**
     * Get decoded part content.
     *
     * @return string
     */
    public function getDecodedContent(): string
    {
        return $this->data['decodedContent'] ?? null;
    }

    /**
     * Part structure.
     *
     * @return \stdClass
     */
    public function getStructure(): \stdClass
    {
        // TODO: Implement getStructure() method.
    }

    /**
     * Get part number.
     *
     * @return string
     */
    public function getPartNumber(): string
    {
        // TODO: Implement getPartNumber() method.
    }

    /**
     * Get an array of all parts for this message.
     *
     * @return PartInterface[]
     */
    public function getParts(): array
    {
        // TODO: Implement getParts() method.
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link https://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     * @since 5.1.0
     */
    public function hasChildren()
    {
        // TODO: Implement hasChildren() method.
    }

    /**
     * Returns an iterator for the current entry.
     * @link https://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator An iterator for the current entry.
     * @since 5.1.0
     */
    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }
}