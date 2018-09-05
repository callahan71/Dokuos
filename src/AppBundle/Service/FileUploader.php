<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileUploader
 *
 * @author Jaime
 */
class FileUploader {
	
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $nameFile, $userName)
    {
        $fileName = $nameFile.'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory().'\\'.$userName, $fileName);

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
