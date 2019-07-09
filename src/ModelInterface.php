<?php

namespace tigrov\emailReply;

interface ModelInterface
{
    /**
     * Returns the param **names** for this class.
     * @return string[]
     */
    public static function paramNames();

    /**
     * Returns the param values of the model as array according to param names from self::paramNames().
     * @return array
     */
    public function paramValues();

    /**
     * Returns a single model instance by param values.
     * @param array $paramValues param values as array according to param names from self::paramNames()
     * @return static|null
     */
    public static function buildFromParams($paramValues);

    /**
     * Makes actions with message from email
     * @param \Ddeboer\Imap\MessageInterface $message reply message object
     */
    public function emailReply($message);
}