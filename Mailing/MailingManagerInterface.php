<?php

namespace Hatimeria\NewsletterBundle\Mailing;

interface MailingManagerInterface
{
    /**
     * Gets list of mailing services
     *
     * @abstract
     * @return void
     */
    public function getServices();
    
}