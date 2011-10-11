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

class SendQueueCommand extends ContainerAwareCommand
{
    /**
     * @var string
     */
    protected $sender;

    /**
     * Command configuration
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('hatimeria:newsletter:send_queue')
             ->setDescription('Sends messages from queue');
    }

    /**
     * Command execution
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sender = $this->getContainer()->getParameter('hatimeria_newsletter.sender');

        /* @var \Doctrine\ORM\EntityManager $em */
        /* @var \Swift_Mailer $mailer */
        /* @var \Hatimeria\NewsletterBundle\Entity\Repository\QueueRepository $queue */
        $em     = $this->getContainer()->get('doctrine.orm.entity_manager');
        $mailer = $this->getContainer()->get('mailer');
        $queue  = $em->getRepository('HatimeriaNewsletterBundle:Queue');

        //@todo use limit 
        foreach ($queue->findPacket() as $mail) {
            /* @var \Hatimeria\NewsletterBundle\Entity\Queue $mail */
            $message = $this->createMessageFromQueue($mail);
            $mailer->send($message);
            $mail->setSent(true);

            $em->persist($mail);
        }
        
        $em->flush();
    }

    /**
     * Creates message from Queue object
     *
     * @param \Hatimeria\NewsletterBundle\Entity\Queue $queue
     * 
     * @return \Swift_Message
     */
    protected function createMessageFromQueue(Queue $queue)
    {
        $message = \Swift_Message::newInstance()
                    ->setSubject($queue->getSubject())
                    ->setFrom($this->sender)
                    ->setTo($queue->getEmail())
                    ->setBody($queue->getBody());

        return $message;
    }

}