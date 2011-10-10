<?php

namespace Hatimeria\NewsletterBundle\Mailing;

use \Hatimeria\NewsletterBundle\Entity\Mailing;

class SimpleMailing implements MailingInterface
{
    protected $mailing;

    protected $recipientProvider;

    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
    }

    public function getRecipients()
    {
        //@todo using recipient provider
    }

    public function setRecipientProvider($provider)
    {
        $this->recipientProvider = $provider;
    }

    public function getName()
    {
        return 'simple_mailing';
    }

    public function getBody($recipient)
    {
        
    }

    public function getSubject($recipient)
    {
        
    }

}