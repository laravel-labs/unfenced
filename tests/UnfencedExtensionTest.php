<?php

namespace Tests;

use Laravel\Unfenced\UnfencedExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;

class UnfencedExtensionTest extends TestCase
{
    public function test_with_filename()
    {
        $result = $this->convert(file_get_contents('tests/fixtures/with-filename.md'));

        $expected = file_get_contents('tests/fixtures/with-filename.html');

        $this->assertSame($expected, $result);
    }

    public function test_with_tabs_and_no_filename()
    {
        $result = $this->convert(
            file_get_contents('tests/fixtures/with-tabs.md'),
            [
                'unfenced' => [
                    'script' => false,
                ],
            ]
        );

        $expected = file_get_contents('tests/fixtures/with-tabs.html');

        $this->assertSame($expected, $result);
    }

    public function test_with_tabs_and_filename()
    {
        $result = $this->convert(
            file_get_contents('tests/fixtures/with-tabs-and-filename.md'),
            [
                'unfenced' => [
                    'script' => false,
                ],
            ]
        );

        $expected = file_get_contents('tests/fixtures/with-tabs-and-filename.html');

        $this->assertSame($expected, $result);
    }

    public function test_with_multiple_tab_groups()
    {
        $result = $this->convert(
            file_get_contents('tests/fixtures/with-multiple-tab-groups.md'),
            [
                'unfenced' => [
                    'script' => false,
                ],
            ]
        );

        $expected = file_get_contents('tests/fixtures/with-multiple-tab-groups.html');

        $this->assertSame($expected, $result);
    }

    public function test_with_tab_script()
    {
        $result = $this->convert(file_get_contents('tests/fixtures/with-tabs.md'));

        $expected = implode('', [
            rtrim(file_get_contents('tests/fixtures/with-tabs.html')),
            '<script>',
            PHP_EOL,
            file_get_contents('js/tabbed-code.js'),
            PHP_EOL,
            '</script>',
            PHP_EOL,
        ]);

        $this->assertSame($expected, $result);
    }

    protected function convert(string $markdown, array $config = []): string
    {
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new UnfencedExtension());

        $convertor = new MarkdownConverter($environment);

        return (string) $convertor->convert($markdown);
    }
}
