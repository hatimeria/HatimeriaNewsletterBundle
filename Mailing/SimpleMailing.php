<?php

namespace Hatimeria\NewsletterBundle\Mailing;

use Hatimeria\NewsletterBundle\Entity\Mailing,
    Hatimeria\NewsletterBundle\Recipient\MailingRecipientsProviderInterface;

class SimpleMailing implements MailingInterface
{
    /**
     * @var \Hatimeria\NewsletterBundle\Entity\Mailing
     */
    protected $mailing;
    /**
     * @var \Hatimeria\NewsletterBundle\Recipient\MailingRecipientsProviderInterface
     */
    protected $recipientProvider;

    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
    }

    public function getRecipients($scheduleType)
    {
        if (null === $this->recipientProvider) {
            throw new \InvalidArgumentException('You must provide MailingRecipientsProvider to be able to retrieve recipients');
        }

        return $this->recipientProvider->findRecipients();
    }

    public function setRecipientProvider(MailingRecipientsProviderInterface $provider)
    {
        $this->recipientProvider = $provider;
    }

    public function getBody($recipient)
    {
        return $this->mailing->getBody();
    }

    public function getSubject($recipient)
    {
        return $this->mailing->getSubject();
    }

    public function supportsSchedule($type)
    {
        return $type == 'default';
    }
    
}