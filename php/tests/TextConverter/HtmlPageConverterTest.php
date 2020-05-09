<?php

declare(strict_types=1);

namespace Tests\TextConverter;

use PHPUnit\Framework\TestCase;
use RacingCar\TextConverter\Exception\FileException;
use RacingCar\TextConverter\HtmlPagesConverter;

/**
 * UnicodeFileToHtmlTextConverter exercise: write the unit tests for the UnicodeFileToHtmlTextConverter class.
 * The UnicodeFileToHtmlTextConverter class is designed to reformat a plain text file for display in a browser.
 * For the Python and Java versions, there is an additional class "HtmlPagesConverter" which is slightly harder to
 * get under test. It not only converts text in a file to html, it also supports pagination. It's meant as a follow
 * up exercise.

 * Class HtmlPageConverterTest
 * @package Tests\TextConverter
 */
class HtmlPageConverterTest extends TestCase
{
    public function testTextFileCanBeConverted(): void
    {
        $file = __DIR__ . '/../_Fixture_/ASCII_Data.txt';
        $converter = new HtmlPagesConverter($file);
        $html = $converter->getHtmlPage(0);
        $this->assertSame('This is some text<br>', $html);
    }

    public function testTextFileWithUnicodeCharactersCanBeConverted(): void
    {
        $file = __DIR__ . '/../_Fixture_/unicodeData.txt';
        $converter = new HtmlPagesConverter($file);
        $html = $converter->getHtmlPage(0);
        $this->assertSame('This is some text with a 1F600 😀 grinning face<br>', $html);
    }

    public function testTextFileWithPageBreaksCanBeIndexed(): void
    {
        $file = __DIR__ . '/../_Fixture_/README.md';
        $converter = new HtmlPagesConverter($file);
        $html = $converter->getHtmlPage(1);
        $this->assertStringStartsWith('## Get going quickly with Cyber-Dojo<br>', $html);
        $html = $converter->getHtmlPage(2);
        $this->assertStringStartsWith('## TDD with Mock Objects: Design Principles and Emerging Properties<br>', $html);
    }

    public function testInvalidFileWillThrowAnException(): void
    {
//        $this->markTestSkipped();
        $file = __DIR__ . 'foo';
        $this->expectException(FileException::class);
        $this->expectExceptionMessage('There was a problem opening the file.');
        new HtmlPagesConverter($file);
        $this->fail('Exception was not thrown for invalid file');
    }
}
