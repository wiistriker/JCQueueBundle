<?php
namespace JC\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('jc_queue:process')
        ->setDescription('Process queue')
        ->addArgument('name', InputArgument::REQUIRED, 'Queue name')
        ->addOption('count', null, InputOption::VALUE_NONE, 'Count of messages to receive')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('jc_queue.manager');

        $queues = $manager->getQueues();
        $name = $input->getArgument('name');

        if (isset($queues[$name])) {
            $queue = $manager->getQueue($name);
            $queue_config = $queues[$name];
            $service_name = $queue_config['service'];

            $service = $this->getContainer()->get($service_name);

            $service->process($queue, 5, $input, $output);
        } else {
            $output->writeln('<error>queue not found</error>');
        }
    }
}