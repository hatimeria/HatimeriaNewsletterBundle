<?php

namespace Hatimeria\NewsletterBundle\Recipient;

interface MailingRecipientsProviderInterface
{
    /**
     * Retrieve collection of recipients for mailing
     * 
     * Method should return collection of strings (email addresses) or collection of objects implementing
     * MailingRecipientInterface
     *
     * @abstract
     * @return array
     */
    public function findRecipients();
    
}