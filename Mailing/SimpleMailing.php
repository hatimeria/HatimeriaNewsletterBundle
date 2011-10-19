<?php

namespace Hatimeria\NewsletterBundle\Mailing;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

use Hatimeria\NewsletterBundle\Entity\Mailing,
    Hatimeria\NewsletterBundle\Recipient\MailingRecipientsProviderInterface;

/**
 * Simple newsletter mailing implementation
 *
 * Body and subject are provided by database table
 * Recipients are provided by user configured service implementing MailingRecipientsProviderInterface
 */
class SimpleMailing implements MailingInterface, ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    /**
     * @var \Hatimeria\NewsletterBundle\Entity\Mailing
     */
    protected $mailing;
    /**
     * @var \Hatimeria\NewsletterBundle\Recipient\MailingRecipientsProviderInterface
     */
    protected $recipientProvider;

    /**
     * @param \Hatimeria\NewsletterBundle\Entity\Mailing $mailing
     */
    public function __construct(Mailing $mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * Gets collection of email address or objects implementing MailingRecipientInterface
     *
     * @throws \InvalidArgumentException
     * @param $scheduleType
     * @return array
     */
    public function getRecipients($scheduleType)
    {
        if (null === $this->recipientProvider) {
            throw new \InvalidArgumentException('You must provide MailingRecipientsProvider to be able to retrieve recipients');
        }

        return $this->recipientProvider->findRecipients();
    }

    /**
     * Sets recipient provider
     *
     * @param \Hatimeria\NewsletterBundle\Recipient\MailingRecipientsProviderInterface $provider
     * @return void
     */
    public function setRecipientProvider(MailingRecipientsProviderInterface $provider)
    {
        $this->recipientProvider = $provider;
    }

    /**
     * Gets body of mailing
     *
     * @param $recipient
     * @return string
     */
    public function getBody($recipient, $scheduleType)
    {
        return $this->mailing->getBody();
    }

    /**
     * Gets subject of mailing
     *
     * @param $recipient
     * @return string
     */
    public function getSubject($recipient)
    {
        return $this->mailing->getSubject();
    }

    /**
     * Tells if mailing service supports provided schedule type
     *
     * @param $type
     * @return bool
     */
    public function supportsSchedule($type)
    {
        return $type == 'default';
    }

    /**
     * Finalize mailing process
     *
     * @return void
     */
    public function finalize()
    {
        /* @var \Doctrine\ORM\EntityManager $em */
        $em      = $this->container->get('doctrine.orm.entity_manager');
        $mailing = $this->mailing;

        // we mark mailing object as sent
        $mailing->setSent(true);

        $em->persist($mailing);
        $em->flush();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
}