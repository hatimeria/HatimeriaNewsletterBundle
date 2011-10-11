<?php

namespace Hatimeria\NewsletterBundle\Entity;

class Mailing
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string
     */
    protected $body;
    /**
     * @var boolean
     */
    protected $sent;
    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->sent      = false;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function isSent()
    {
        return $this->sent;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}