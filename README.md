Email reply
===========

The library passes reply messages from email to predefined objects. It uses IMAP to connect email servers.

[![Latest Stable Version](https://poser.pugx.org/Tigrov/email-reply/v/stable)](https://packagist.org/packages/Tigrov/email-reply)
[![Build Status](https://travis-ci.org/Tigrov/email-reply.svg?branch=master)](https://travis-ci.org/Tigrov/email-reply)

Limitation
----------

The library uses [ddeboer/imap](https://github.com/ddeboer/imap) and it requires:

* PHP >= 7.1
* extensions `iconv`, `IMAP`, `mbstring`

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tigrov/email-reply
```

or add

```
"tigrov/email-reply": "~1.0"
```

to the require section of your `composer.json` file.

Usage
-----

First of all you need to configure your email server. Create special mailbox and redirect all the mails sent to non-existent mailboxes.
The server must accepts IMAP connections.

Then you can take steps:

1. Create a model class with `ModelInterface` interface and implement the necessary methods (see `examples/Model.php`).
    ```php
    class Model implements ModelInterface
    {
        public static function paramNames() {}
    
        public function paramValues() {}
    
        public static function buildFromParams($paramValues) {}
    
        public function emailReply($message) {}
    }
    ```
2. Create and configure `EmailReply` object.
    ```php
    $config = [
        'classesMap' => [
            // key will be used as prefix for email address
            'model' => \Model::class,
            // email for reply will be like model5@domain.com
            // or
            // 'm' => \Model::class,
            // email for reply will be like m5@domain.com
            // where 5 is id of a model
        ],
    ];
 
    $emailReply = new EmailReply($config);
    ```
3. Send a email message with a special reply email address.
    ```php
    $email = $emailReply->getReplyEmail($model, 'domain.com');
    
    // Send an email to somebody with the reply email $email
    // ...
    ```
4. Read your mailboxes using IMAP. For example as `cron` job.
    ```php
    $server = new Server($host, $port);
    
    $connection = $server->authenticate($username, $password);
    
    $mailboxModels = Reader::getMailboxModels($connection);
    
    $messages = Reader::getIterator($mailboxModels);
    
    $emailReply->read($messages);
    
    $connection->expunge();
    ```
5. Each message will be passed to `ModelInterface::emailReply($message)` where you can precess them.
    ```php
    class Model implements ModelInterface
    {
        // ...
    
        public function emailReply($message)
        {
            /** @var string $fromEmail email address of the sender */
            $fromEmail = $message->getFrom()->getAddress();
            
            /** @var string $fromName name of the sender */
            $fromName = $message->getFrom()->getName();
            
            /** @var string $content content from the replied message */
            $content = $message->getBodyHtml() ?: $message->getBodyText() ?: $message->getDecodedContent();
            
            // Parse the content to get only answer
            $content = EmailReplyParser::parseReply($content);
            
            // To do something with $content
            // e.g. add comment from $fromEmail to the object 
        }
    ```

See [examples](https://github.com/tigrov/email-repl/examples) directory for examples.

Suggests
--------
You can use [willdurand/email-reply-parser](https://github.com/willdurand/EmailReplyParser) to parse only reply text from email messages.
```php
$reply = EmailReplyParser::parseReply($content);
```

License
-------

[MIT](LICENSE)
