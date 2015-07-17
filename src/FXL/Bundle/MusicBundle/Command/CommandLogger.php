<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Component\HttpKernel\Log\LoggerInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Logger for commands
 */
class CommandLogger implements LoggerInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function emerg($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function alert($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function crit($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function err($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function warn($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function notice($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function info($message, array $context = array())
    {
        if (OutputInterface::VERBOSITY_VERBOSE !== $this->output->getVerbosity()) {
            return;
        }

        if (substr($message, -3) === '...') {
            $this->output->write($message . ' ');
        } else {
            $this->output->writeln($message);
        }
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @throws \RuntimeException
     */
    public function debug($message, array $context = array())
    {
        throw new \RuntimeException("Method not implemented yet");
    }
}
