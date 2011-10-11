<?php
namespace Hatimeria\NewsletterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Hatimeria\NewsletterBundle\Entity\Queue,
    Hatimeria\NewsletterBundle\Recipient\MailingRecipientInterface;

class PrepareMailingCommand extends ContainerAwareCommand {
    
    protected function configure()
    {
        parent::configure();

        $this->setName('hatimeria:newsletter:prepare_mailing')
             ->setDescription('Creates mailing queue from mailing services')
             ->setDefinition(array(
                new InputOption('type', null, InputOption::VALUE_OPTIONAL, 'Schedule type', 'default'),
             ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption('type');

        /* @var \Hatimeria\NewsletterBundle\Mailing\MailingManagerInterface $manager */
        /* @var \Doctrine\ORM\EntityManager $em */
        $manager = $this->getContainer()->get('hatimeria_newsletter.manager');
        $em      = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($manager->getServices() as $service) {
            /* @var \Hatimeria\NewsletterBundle\Mailing\MailingInterface $service */
            if (!$service->supportsSchedule($type)) {
                continue;
            }
            foreach ($service->getRecipients() as $recipient) {
                if (is_object($recipient) && ($recipient instanceof MailingRecipientInterface)) {
                    /* @var \Hatimeria\NewsletterBundle\Recipient\MailingRecipientInterface $recipient */
                    $email = $recipient->getEmail();
                } elseif (is_string($recipient)) {
                    $email = $recipient;
                } else {
                    $msg = sprintf('Mailing services have to return recipients as string which is email address
                    or objects implementing MailingRecipientInterface (service: %s)', get_class($service));
                    
                    throw new \InvalidArgumentException($msg);
                }

                $queue = new Queue();
                $queue->setEmail($email);
                $queue->setBody($service->getBody($recipient));
                $queue->setSubject($service->getSubject($recipient));

                $em->persist($queue);
            }
            
            $em->flush();
        }
    }
    
}