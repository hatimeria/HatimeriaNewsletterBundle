<?php

namespace Hatimeria\NewsletterBundle\Mailing;

use Symfony\Component\DependencyInjection\ContainerAware;

use Hatimeria\NewsletterBundle\Mailing\SimpleMailing,
    Hatimeria\NewsletterBundle\Mailing\MailingInterface;

class MailingManager extends ContainerAware implements MailingManagerInterface
{
    /**
     * Gets list of mailing services
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public function getServices()
    {
        $container = $this->container;

        $services     = $this->getSimpleMailingServices();
        $userServices = $container->getParameter('hatimeria_newsletter.mailing_services');

        // user specified mailing services are merged with simple mailing services
        foreach ($userServices as $name) {
            $service = $container->get($name);

            if (!$service instanceof MailingInterface) {
                throw new \InvalidArgumentException('Mailing services for HatimeriaNewsletterBundle must implemenet MailingInterace');
            }

            $services[] = $service;
        }

        return $services;
    }

    /**
     * Creates collection of SimpleMailing services
     *
     * @return array
     */
    protected function getSimpleMailingServices()
    {
        $container = $this->container;
        // if we want to send simple mailing, we must provide recipient provider service in bundle configuration
        if (!$container->has('hatimeria_newsletter.recipient_provider')) {
            return array();
        }
        $recipientProvider = $container->get('hatimeria_newsletter.recipient_provider');
        
        /* @var \Doctrine\ORM\EntityManager $em */
        /* @var \Hatimeria\NewsletterBundle\Entity\Repository\MailingRepository $er */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $er = $em->getRepository('HatimeriaNewsletterBundle:Mailing');

        $services = array();
        foreach ($er->findMailingToSend() as $mailing) {
            $service = new SimpleMailing($mailing);
            $service->setRecipientProvider($recipientProvider);
            $service->setContainer($this->container);

            $services[] = $service;
        }

        return $services;
    }

}