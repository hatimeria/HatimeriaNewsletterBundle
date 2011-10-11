<?php

namespace Hatimeria\NewsletterBundle\Recipient;

interface MailingRecipientInterface
{
    /**
     * Gets the user email address
     *
     * @abstract
     * @return void
     */
    function getEmail();
    
}