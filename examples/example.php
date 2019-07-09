<?php

use \tigrov\emailReply\EmailReply;

$config = require(__DIR__ . DIRECTORY_SEPARATOR . 'conf.php');
$emailReply = new EmailReply($config);

$id = 5;
$model = new \Model($id);
$email = $emailReply->getReplyEmail($model, 'domain.com');

// Send an email to somebody with the reply email $email
// ...