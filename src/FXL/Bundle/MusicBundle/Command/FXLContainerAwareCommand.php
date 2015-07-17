<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Output\OutputInterface;

use FXL\Bundle\MusicBundle\Command\CommandLogger;

/**
 * ContainerAware Command
 */
abstract class FXLContainerAwareCommand extends ContainerAwareCommand
{
    protected $logger;

    /**
     * log if verbose mode active
     *
     * @param OutputInterface $output  The output
     * @param string          $message The message
     */
    protected function log(OutputInterface $output, $message)
    {
        if (null === $this->logger) {
            $this->logger = new CommandLogger($output);
        }

        $this->logger->info($message);
    }

    /** 
     * Get Entity Manager proxy
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()
            ->get('doctrine')
            ->getEntityManager();
    }
}
