<?php

namespace Hatimeria\NewsletterBundle\Entity;

class Mailing
{
    protected $subject;

    protected $body;
    /**
     * @var boolean
     */
    protected $sent;

    public function __construct()
    {
        
    }

    public function isSent()
    {
        return $this->sent;
    }

    

}