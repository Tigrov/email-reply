<?php

namespace tigrov\tests\unit\emailReply;

use Ddeboer\Imap\Message\EmailAddress;
use PHPUnit\Framework\TestCase;
use tigrov\emailReply\EmailReply;
use tigrov\tests\unit\emailReply\models\Message;
use tigrov\tests\unit\emailReply\models\Model;

class EmailReplyTest extends TestCase
{
    public function testConfigure()
    {
        $config = [
            'classesMap' => [
                'model' => Model::class,
            ],
            'allowedHosts' => ['domain.com'],
            'separator' => '.',
            'paramSeparator' => 'x',
            'defaultCallback' => function ($message) { $message->delete(); },
            'encodeCallback' => function ($email, $host) { return bin2hex($email); },
            'decodeCallback' => function ($email, $host) { return hex2bin($email); },
        ];

        $emailReply = new EmailReply();
        $emailReply->configure($config);

        foreach ($config as $key => $value) {
            $this->assertEquals($value, $emailReply->$key);
        }
    }

    public function testGetReplyEmail()
    {
        $model = new Model(5);
        $emailReply = new EmailReply(['classesMap' => [Model::class]]);

        $this->assertEquals('model5@domain.com', $emailReply->getReplyEmail($model, 'domain.com'));
    }

    public function testGetModel()
    {
        $emailReply = new EmailReply(['classesMap' => [Model::class]]);
        $model = $emailReply->getModel([new EmailAddress('model5', 'domain.com')]);

        $this->assertEquals(serialize(new Model(5)), serialize($model));

        $model2 = $emailReply->getModel([new EmailAddress('info', 'domain.com')], 'Hello, from model7@domain.com. The end.');

        $this->assertEquals(serialize(new Model(7)), serialize($model2));

        $model3 = $emailReply->getModel([new EmailAddress('info', 'domain.com')], 'Hello, The end.');

        $this->assertNull($model3);
    }

    public function testGetEmailRegex()
    {
        $emailReply = new EmailReply(['classesMap' => [Model::class]]);

        $this->assertEquals('(model)([\w\-]+)@([\w.\-]+)', $emailReply->getEmailRegex());

        $emailReply2 = new EmailReply([
            'classesMap' => ['a' => Model::class, 'b' => Model::class],
            'allowedHosts' => ['domain.com', 'example.net'],
        ]);

        $this->assertEquals('(a|b)([\w\-]+)@(domain\.com|example\.net)', $emailReply2->getEmailRegex());
    }

    public function testRead()
    {
        $emailReply = new EmailReply(['classesMap' => [Model::class], 'allowedHosts' => ['domain.com']]);

        $message = new Message([
            'from' => new EmailAddress('info', 'gmail.com'),
            'to' => [new EmailAddress('model12', 'domain.com')],
            'bodyHtml' => '<p>Hello</p>',
            'bodyText' => 'Hello',
        ]);

        $logFile = __DIR__ . '/log/reply.log';
        unlink($logFile);

        $emailReply->read([$message]);

        $content = file_get_contents($logFile);
        $this->assertEquals("#12  (info@gmail.com) replied:\n<p>Hello</p>\n\n", $content);
    }
}