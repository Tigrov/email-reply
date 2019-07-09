<?php

use \Ddeboer\Imap\Server;
use \tigrov\emailReply\Reader;
use \tigrov\emailReply\EmailReply;

$host = '127.0.0.1';
$port = '993';
$username = 'admin';
$password = '';

$server = new Server($host, $port);

$connection = $server->authenticate($username, $password);

$mailboxModels = Reader::getMailboxModels($connection);

$messages = Reader::getIterator($mailboxModels);

$config = require(__DIR__ . DIRECTORY_SEPARATOR . 'conf.php');
$emailReply = new EmailReply($config);

$emailReply->read($messages);

$connection->expunge();