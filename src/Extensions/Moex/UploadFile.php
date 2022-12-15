<?php

namespace App\Extensions\Moex;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFile
{
    private $filePath;

    public function __construct(UploadedFile $file, $uploadDirectory)
    {
        $fileName = uniqid() . '_' . $file->getClientOriginalName();
        $filePath = $file->move($uploadDirectory, $fileName);
        $this->filePath = $filePath;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }
}