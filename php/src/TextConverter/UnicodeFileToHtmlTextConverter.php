<?php

declare(strict_types=1);

namespace RacingCar\TextConverter;

use RacingCar\TextConverter\Exception\FileException;
use Throwable;

class UnicodeFileToHtmlTextConverter
{
    private string $fullFileNameWithPath;

    /**
     * @var resource
     */
    private $file;

    public function __construct(string $fullFileNameWithPath)
    {
        $this->fullFileNameWithPath = $fullFileNameWithPath;
    }

    public function convertToHtml(): string
    {
        $this->openFileOrFail();
        $html = $this->processFileToHtml();
        fclose($this->file);
        return $html;
    }

    public function getFileName(): string
    {
        return $this->fullFileNameWithPath;
    }

    /**
     * @throws FileException
     */
    private function openFileOrFail(): void
    {
        try {
            $file = fopen($this->fullFileNameWithPath, 'r');
        } catch (Throwable $e) {
            throw new FileException('There was a problem opening the file.');
        }
        if ($file === false) {
            throw new FileException('The file is empty');
        }
        $this->file = $file;
    }

    private function processFileToHtml(): string
    {
        $html = '';
        while (! feof($this->file)) {
            $line = $this->readLineFromFile();
            $html .= htmlspecialchars($line, ENT_QUOTES | ENT_HTML5);
            $html .= '<br>';
        }
        return $html;
    }

    private function readLineFromFile(): String
    {
        $line = fgets($this->file);
        if ($line === false) {
            return '';
        }
        return rtrim($line);
    }
}
