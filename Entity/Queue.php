<?php

namespace Hatimeria\NewsletterBundle\Entity;

class Queue
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var \DateTime
     */
    protected $createdAt;
    /**
     * Recipient email address
     *
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string
     */
    protected $body;
    /**
     * @var bool
     */
    protected $sent;

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
     * Objects creation date and time
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets recipient email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets recipient email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Gets message subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets message subject
     *
     * @param string $subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Gets message body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets message body
     *
     * @param string $body
     * @return void
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Tells if message was already sent
     *
     * @return bool
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Sets if message was sent
     *
     * @param bool $sent
     * @return void
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    }

}