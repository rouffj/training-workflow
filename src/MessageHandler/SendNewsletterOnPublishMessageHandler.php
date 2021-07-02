<?php

namespace App\MessageHandler;

use App\Message\SendNewsletterOnPublishMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendNewsletterOnPublishMessageHandler implements MessageHandlerInterface
{
    public function __invoke(SendNewsletterOnPublishMessage $message)
    {
        $emailTemplate = [
            'subject' => sprintf('One new article "%s" published!', $message->getPost()->getTitle()),
            'body' => sprintf('Hello, please checkout our new article published a few minutes ago. Read it here: %s',
                'http://127.0.0.1:8003/en/blog/posts/' . $message->getPost()->getSlug()
            ),
        ];

        $templates = [];
        foreach ($message->getEmails() as $email) {
            $templates[$email] = $emailTemplate;
        }

        // send emails via an smtp like Gmail or whatever.
        dump('The following emails have been sent', $templates);
    }
}
