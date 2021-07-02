<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class PublishingSubscriber implements EventSubscriberInterface
{
    public function onPublish(Event $event)
    {
        $post = $event->getSubject();
        dump('onPublish', $event);

        $this->updateRssFeed($post);
        $this->addPostToSearchIndex($post);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.blog_publishing.transition.publish' => 'onPublish',
        ];
    }

    private function updateRssFeed(Post $post): void
    {
        dump('The RSS feed has been successfully updated with ' . $post->getTitle());
    }

    private function addPostToSearchIndex(Post $post): void
    {
        dump(sprintf('The article "%s" has been added to the search engin index', $post->getTitle()));
    }
}
