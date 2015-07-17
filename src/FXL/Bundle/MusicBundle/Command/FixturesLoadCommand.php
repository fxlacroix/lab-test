<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesLoadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('dbal:import')
        ->setDescription('Import SQL file(s) directly to Database.')
        ->setDefinition(array(
            new InputArgument(
                'file', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'File path(s) of SQL to be executed.'
            )
        ))
        ->setHelp(<<<EOT
Import SQL file(s) directly to Database.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
            'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
            'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
        ));

        $this->setHelperSet($helperSet);

        $conn = $this->getHelper('db')->getConnection();

        if (($fileNames = $input->getArgument('file')) !== null) {
            foreach ((array) $fileNames as $fileName) {
                $fileName = realpath($fileName);

                if ( ! file_exists($fileName)) {
                    throw new \InvalidArgumentException(
                        sprintf("SQL file '<info>%s</info>' does not exist.", $fileName)
                    );
                } elseif ( ! is_readable($fileName)) {
                    throw new \InvalidArgumentException(
                        sprintf("SQL file '<info>%s</info>' does not have read permissions.", $fileName)
                    );
                }

                $output->write(sprintf("Processing file '<info>%s</info>'... ", $fileName));
                $sql = file_get_contents($fileName);

                if ($conn instanceof \Doctrine\DBAL\Driver\PDOConnection) {
                    // PDO Drivers
                    try {
                        $lines = 0;

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        do {
                            // Required due to "MySQL has gone away!" issue
                            $stmt->fetch();
                            $stmt->closeCursor();

                            $lines++;
                        } while ($stmt->nextRowset());

                        $output->write(sprintf('%d statements executed!', $lines) . PHP_EOL);
                    } catch (\PDOException $e) {
                        $output->write('error!' . PHP_EOL);

                        throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                    }
                } else {
                    // Non-PDO Drivers (ie. OCI8 driver)
                    $stmt = $conn->prepare($sql);
                    $rs = $stmt->execute();

                    if ($rs) {
                        $output->writeln('OK!' . PHP_EOL);
                    } else {
                        $error = $stmt->errorInfo();

                        $output->write('error!' . PHP_EOL);

                        throw new \RuntimeException($error[2], $error[0]);
                    }

                    $stmt->closeCursor();
                }
            }
        }
    }
}

