<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;


/**
 * Crawl songs
 */
class renameUploadCommand extends FXLContainerAwareCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     *
     * @see Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('fxlmusic:rename:upload')
            ->setDescription('Crawl and save songs for blogart free fr');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $files = $this->find("/Users/fxlacroix/Sites/blogart/web/uploads/Document", "*%20*.mp3");

        foreach($files as $file){


            rename($file, str_replace("%20", "", $file));

        }

    }

    function find($dir, $pattern){

        // escape any character in a string that might be used to trick
        // a shell command into executing arbitrary commands
        $dir = escapeshellcmd($dir);
        // execute "find" and return string with found files
        $files = shell_exec("find $dir -name ".$pattern." -print");
        // create array from the returned string (trim will strip the last newline)
        $files = explode("\n", trim($files));
        // return array
        return $files;
    }
}
