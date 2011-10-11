<?php

namespace Hatimeria\NewsletterBundle\Entity;

/**
 * Mailing object
 */
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets mailing subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets mailing subject
     *
     * @param $subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Gets mailing body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets mailing body
     *
     * @param $body
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Tells if mailing was already processed
     *
     * @return bool
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Sets informarion if mailing was processed
     * @param boolean $sent
     * @return void
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}