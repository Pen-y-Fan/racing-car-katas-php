<?php

declare(strict_types=1);

namespace RacingCar\TextConverter;

class UnicodeFileToHtmlTextConverter
{
    private string $fullFileNameWithPath;

    public function __construct(string $fullFileNameWithPath)
    {
        $this->fullFileNameWithPath = $fullFileNameWithPath;
    }

    public function convertToHtml(): string
    {
        $html = '';
        $file = fopen($this->fullFileNameWithPath, 'r');
        if ($file === false) {
            return 'Error: File does not exist';
        }
        while (! feof($file)) {
            $line = fgets($file);
            if ($line === false) {
                continue;
            }
            $line = rtrim($line);
            $html .= htmlspecialchars($line, ENT_QUOTES | ENT_HTML5);
            $html .= '<br />';
        }
        fclose($file);
        return $html;
    }

    public function getFileName(): string
    {
        return $this->fullFileNameWithPath;
    }
}
