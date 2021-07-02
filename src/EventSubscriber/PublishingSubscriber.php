<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;

class PublishingSubscriber implements EventSubscriberInterface
{
    public function beforePublishGuard(GuardEvent $event)
    {

        /** @var Post $post */
        $post = $event->getSubject();
        if (!$this->isArticleDoesNotContainsBlacklistedWords($post)) {
            $event->setBlocked(true, 'The article contains some black listed words, please remove them to be able to publish.');
        }

        if (!$this->isTitleLengthOk($post)) {
            $event->setBlocked(true, 'The title is too short to publish it.');
        }

        dump('beforePublishGuard', $event);
    }

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
            'workflow.blog_publishing.guard.publish' => 'beforePublishGuard',
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

    private function isArticleDoesNotContainsBlacklistedWords(Post $post): bool
    {
        $words = ['connard', 'salaud', 'connasse', 'fils de ZZZ'];
        $pattern = implode('(\s|\.|,)|', $words);
        $matches = [];
        preg_match_all('/'. $pattern.'/', $post->getContent(), $matches);
        dump($matches, $pattern);

        return count($matches[0]) === 0;
    }

    private function isTitleLengthOk(Post $post): bool
    {
        return strlen($post->getTitle()) > 20;
    }
}
