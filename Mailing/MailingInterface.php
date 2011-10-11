<?php

namespace Hatimeria\NewsletterBundle\Mailing;

/**
 * Mailing service interface
 */
interface MailingInterface
{
    /**
     * Gets collection of email address or objects implementing MailingRecipientInterface
     *
     * @abstract
     * @param string $scheduleType
     * @return void
     */
    public function getRecipients($scheduleType);
    /**
     * Gets body of mailing.
     *
     * Recipient is provided for more complex body generation
     *
     * @abstract
     * @param mixed $recipient
     * @return void
     */
    public function getBody($recipient);
    /**
     * Gets subject of mailing.
     *
     * Recipient is provided for more complex subject generation
     *
     * @abstract
     * @param mixed $recipient
     * @return void
     */
    public function getSubject($recipient);
    /**
     * Tells if mailing service supports provided schedule type
     *
     * @abstract
     * @param string $type
     * @return void
     */
    public function supportsSchedule($type);
    /**
     * Finalize mailing process
     *
     * Method is called after all recipients messages was queued
     *
     * @abstract
     * @return void
     */
    public function finalize();

}