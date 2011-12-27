<?php
namespace Hatimeria\NewsletterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Hatimeria\FrameworkBundle\Command\Base\Command;

use Hatimeria\NewsletterBundle\Entity\Queue,
    Hatimeria\NewsletterBundle\Recipient\MailingRecipientInterface;

class PrepareMailingCommand extends Command {

    /**
     * Command configuration
     * 
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('hatimeria:newsletter:prepare_mailing')
             ->setDescription('Creates mailing queue from mailing services')
             ->setDefinition(array(
                new InputOption('type', null, InputOption::VALUE_OPTIONAL, 'Schedule type', 'default'),
             ));
    }

    /**
     * Command execution
     *
     * @throws \InvalidArgumentException
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
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
            foreach ($service->getRecipients($type) as $recipient) {
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

                $body = $service->getBody($recipient, $type);
                if (false === $body) {
                    continue;
                }
                
                $queue = new Queue();
                $queue->setEmail($email);
                $queue->setBody($body);
                $queue->setSubject($service->getSubject($recipient));
                
                $em->persist($queue);
            }

            $em->flush();

            $service->finalize();
        }
    }
    
}