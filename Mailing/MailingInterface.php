<?php

namespace Hatimeria\NewsletterBundle\Mailing;

interface MailingInterface
{
    public function getName();

    public function getRecipients();

    public function getBody($recpient);

    public function getSubject($recipient);

}