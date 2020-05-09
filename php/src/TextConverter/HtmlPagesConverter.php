<?php

declare(strict_types=1);

namespace RacingCar\TextConverter;

use RacingCar\TextConverter\Exception\FileException;
use Throwable;

/**
 * Class HtmlPages
 * This is a second, slightly harder problem on the same theme as the UnicodeFileToHtmlTextConverter
 *
 * @package RacingCar\TextConverter
 */
class HtmlPagesConverter
{
    private string $fullFileNameWithPath;

    private array $breaks;

    /**
     * @var resource
     */
    private $file;

    /**
     * HtmlPages constructor.
     * Reads the file and note the positions of the page breaks so we can access them quickly
     * @throws Throwable
     */
    public function __construct(string $filename)
    {
        $this->fullFileNameWithPath = $filename;
        $this->breaks = [0];
        $this->openFileOrFail();

        while (! feof($this->file)) {
            $line = $this->readLineFromFile();
            if (strpos($line, 'PAGE_BREAK') !== false) {
                $this->breaks[] = ftell($this->file);
            }
        }
        $this->breaks[] = ftell($this->file);
        fclose($this->file);
    }

    /**
     * @param int $page Page number (zero index)
     * @return string HTML page with the given number
     */
    public function getHtmlPage(int $page): string
    {
        $pageStart = $this->breaks[$page];
        $pageEnd = $this->breaks[$page + 1];
        $html = '';
        $this->openFileOrFail();
        fseek($this->file, $pageStart);
        while (ftell($this->file) !== $pageEnd) {
            $line = $this->readLineFromFile();
            if (strpos($line, 'PAGE_BREAK') !== false) {
                continue;
            }
            $html .= $this->processLineToHtml($line);
        }
        fclose($this->file);
        return $html;
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

    private function processLineToHtml(string $line): string
    {
        return htmlspecialchars($line, ENT_QUOTES | ENT_HTML5) . '<br>';
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
