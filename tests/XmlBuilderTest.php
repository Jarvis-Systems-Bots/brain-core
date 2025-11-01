<?php

declare(strict_types=1);

namespace BrainCore\Tests;

use BrainCore\XmlBuilder;
use PHPUnit\Framework\TestCase;

class XmlBuilderTest extends TestCase
{
    public function testBuildsBrainXmlWithoutIndentation(): void
    {
        $structure = [
            'element' => 'system',
            'single' => false,
            'child' => [
                [
                    'element' => 'sections',
                    'single' => false,
                    'order' => 'strict',
                    'child' => [
                        [
                            'element' => 'section',
                            'name' => 'meta',
                            'brief' => 'Response metadata',
                            'required' => true,
                            'single' => true,
                            'child' => [],
                        ],
                    ],
                ],
                [
                    'element' => 'code_blocks',
                    'policy' => 'Strict formatting; no extraneous comments.',
                    'single' => true,
                    'child' => [],
                ],
            ],
        ];
        $xml = XmlBuilder::from($structure)->build();

        $this->assertStringStartsWith('<system>', $xml);
        $this->assertStringContainsString("\n<section name=\"meta\" brief=\"Response metadata\" required=\"true\"/>", $xml);
        $this->assertStringContainsString("</sections>\n\n<code_blocks", $xml);
        $this->assertStringNotContainsString("\t", $xml, 'XML output must not contain tab characters.');
        $this->assertStringNotContainsString(' </', $xml, 'XML output should not introduce indentation spaces.');
    }

    public function testSelfClosingForSingleNodes(): void
    {
        $structure = [
            'element' => 'root',
            'child' => [
                [
                    'element' => 'leaf',
                    'single' => true,
                    'child' => [],
                ],
            ],
            'single' => false,
        ];

        $xml = XmlBuilder::from($structure)->build();

        $this->assertSame("<root>\n<leaf/>\n</root>", $xml);
    }

    public function testInlineTextRendering(): void
    {
        $structure = [
            'element' => 'root',
            'child' => [
                [
                    'element' => 'title',
                    'text' => 'Hello & world',
                    'child' => [],
                    'single' => false,
                ],
            ],
            'single' => false,
        ];

        $xml = XmlBuilder::from($structure)->build();

        $this->assertSame("<root>\n<title>Hello &amp; world</title>\n</root>", $xml);
    }
}
