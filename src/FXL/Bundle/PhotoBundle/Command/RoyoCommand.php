<?php

namespace FXL\Bundle\PhotoBundle\Command;

use FXL\Component\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class RoyoCommand extends BaseCommand
{
    private $host = "http://michel.royo.pagesperso-orange.fr/";

    protected function configure()
    {
        $this
            ->setName('fxlphoto:crawl:royo')
            ->setDescription('Crawl and save images');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $royoConsumer = $this->getContainer()->get('fxl_photo.consumer.royo');
        $folders = $royoConsumer->find('url');

        $this->save($folders);
    }

    protected function save($folders)
    {

        $rootDir = $this->getContainer()->get('kernel')->getRootDir();
        $fs = new Filesystem();
        $directory = sprintf("%s/../web/uploads/fxlphoto/%s/photo", $rootDir, 'michel-royo');
        $fs->remove($directory);
        echo "suppression dossier photo royo\n";
        $fs->mkdir($directory);
        //$fs->chmod($directory, 0777);
        echo "creation dossier photo royo\n";

        foreach ($folders as $name => $folder) {
            if (!empty($name)) {
                $nameDisk = preg_replace("/\\s\\s+/", " ", $name);

                $pathToImage = sprintf("%s/../web/uploads/fxlphoto/%s/photo/%s/", $rootDir, 'michel-royo', $nameDisk);

                if (!file_exists($pathToImage)) {
                    ;
                    try {
                        $fs->mkdir($pathToImage);
                        //$fs->chmod($pathToImage, 0777);
                        echo "Creation du répertoire " . $pathToImage . "\n";
                    } catch (IOException $e) {
                        echo "An error occured while creating your directory " . $pathToImage . "\n";
                    }
                    foreach ($folder as $image) {
                        $imageDisk = basename($image);
                        $imageDisk = rawurldecode($imageDisk);
                        $imageDisk = utf8_encode($imageDisk);
                        $imageDisk = trim($imageDisk);
                        $imageDisk = rtrim($imageDisk);
                        $imageDisk = str_replace('.', '-', $imageDisk);
                        $imageDisk = str_replace(array('-jpg', '-JPG'), '.jpg', $imageDisk);

                        if (!file_exists($pathToImage . $image)) {

                            echo "creation " . $pathToImage . $imageDisk . " depuis " . $this->host . $image . "\n";

                            if (!file_put_contents($pathToImage . $imageDisk, file_get_contents($this->host . $image))) {
                                echo "La copie " . $this->host . $image . " vers " . $pathToImage . basename($image) . " du fichier a échoué...\n";
                            } else {


                                $mime = mime_content_type($pathToImage . $imageDisk);
                                if (!preg_match("/image/", $mime)) {
                                    $fs->remove($pathToImage . $imageDisk);
                                    echo "rejected not image\n";
                                } else {
                                    echo "accepted real image\n";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
