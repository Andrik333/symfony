<?php

namespace App\Extensions\Moex;

class ParseCsv
{
    private $filePath;
    private $data = [];

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->parseFile();
    }

    private function parseFile(): void
    {
        $row = 1;
        if (($handle = fopen($this->filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null)) !== FALSE) {
                if ($row > 1) {
                    $this->data[] = str_getcsv($data[0], ';');
                }
                $row++;
            }
            fclose($handle);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
}
