<?php

namespace App\Message;

use App\Entity\Post;

final class SendNewsletterOnPublishMessage
{
     private $emails;
     private $post;

     public function __construct(array $emailsSubscribedToNewsletter, Post $post)
     {
         $this->emails = $emailsSubscribedToNewsletter;
         $this->post = $post;
     }

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
