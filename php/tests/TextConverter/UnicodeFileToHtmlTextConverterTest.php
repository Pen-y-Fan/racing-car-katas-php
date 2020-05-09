<?php

declare(strict_types=1);

namespace Tests\TextConverter;

use PHPUnit\Framework\TestCase;
use RacingCar\TextConverter\Exception\FileException;
use RacingCar\TextConverter\UnicodeFileToHtmlTextConverter;

/**
 * UnicodeFileToHtmlTextConverter exercise: write the unit tests for the UnicodeFileToHtmlTextConverter class.
 * The UnicodeFileToHtmlTextConverter class is designed to reformat a plain text file for display in a browser.
 * For the Python and Java versions, there is an additional class "HtmlPagesConverter" which is slightly harder to
 * get under test. It not only converts text in a file to html, it also supports pagination. It's meant as a follow
 * up exercise.

 * Class UnicodeFileToHtmlTextConverterTest
 * @package Tests\TextConverter
 */
class UnicodeFileToHtmlTextConverterTest extends TestCase
{
    public function testFileNameCanBeSet(): void
    {
        $converter = new UnicodeFileToHtmlTextConverter('foo');
        $this->assertSame('foo', $converter->getFileName());
    }

    public function testTextFileCanBeConverted(): void
    {
        $file = __DIR__ . '/../_Fixture_/ASCII_Data.txt';
        $converter = new UnicodeFileToHtmlTextConverter($file);
        $html = $converter->convertToHtml();
        $this->assertSame('This is some text<br>', $html);
    }

    public function testTextFileWithUnicodeCharactersCanBeConverted(): void
    {
        $file = __DIR__ . '/../_Fixture_/unicodeData.txt';
        $converter = new UnicodeFileToHtmlTextConverter($file);
        $html = $converter->convertToHtml();
        $this->assertSame('This is some text with a 1F600 😀 grinning face<br>', $html);
    }

    public function testInvalidFileWillThrowAnException(): void
    {
        $file = __DIR__ . 'foo';
        $converter = new UnicodeFileToHtmlTextConverter($file);
        $this->expectException(FileException::class);
        $this->expectExceptionMessage('There was a problem opening the file.');
        $converter->convertToHtml();
        $this->fail('Exception was not thrown for invalid file');
    }
}
