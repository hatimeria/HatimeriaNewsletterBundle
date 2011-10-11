<?php

namespace Hatimeria\NewsletterBundle\Mailing;

interface MailingInterface
{
    public function getRecipients();

    public function getBody($recipient);

    public function getSubject($recipient);

    public function supportsSchedule($type);

}