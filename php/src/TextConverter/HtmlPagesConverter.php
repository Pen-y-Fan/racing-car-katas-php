<?php

declare(strict_types=1);

namespace RacingCar\TextConverter;

use Exception;

/**
 * Class HtmlPages
 * This is a second, slightly harder problem on the same theme as the UnicodeFileToHtmlTextConverter
 *
 * @package RacingCar\TextConverter
 */
class HtmlPagesConverter
{
    private string $filename;

    private array $breaks;

    /**
     * HtmlPages constructor.
     * Reads the file and note the positions of the page breaks so we can access them quickly
     * @throws Exception
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->breaks = [0];
        $file = fopen($this->filename, 'r');
        if ($file === false) {
            throw new Exception('File could not be opened');
        }

        while (! feof($file)) {
            $line = fgets($file);
            if ($line === false) {
                continue;
            }
            $line = rtrim($line);
            if (strpos($line, 'PAGE_BREAK') !== false) {
//                $pageBreakPosition = ftell($f);
                $this->breaks[] = ftell($file);
            }
        }
        $this->breaks[] = ftell($file);
        fclose($file);
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
        $file = fopen($this->filename, 'r');
        if ($file === false) {
            throw new Exception('File could not be opened');
        }
        fseek($file, $pageStart);
        while (ftell($file) !== $pageEnd) {
            $line = fgets($file);
            if ($line === false) {
                continue;
            }
            $line = rtrim($line);
            if (strpos($line, 'PAGE_BREAK') !== false) {
                continue;
            }
            $html .= htmlspecialchars($line, ENT_QUOTES | ENT_HTML5);
            $html .= '<br />';
        }
        fclose($file);
        return $html;
    }
}
