<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use stdClass;

/**
 * Test case for the link
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\Link
 */
class LinkTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Link::class,
            OopVisibilityType::IS_PUBLIC,
            2,
            2
        );
    }

    /**
     * @param Templates $templates       Collection/storage of templates that will be required while rendering
     * @param string    $expectedMessage Expected message of exception
     *
     * @dataProvider provideIncompleteTemplates
     */
    public function testRenderWithoutTemplates(Templates $templates, string $expectedMessage): void
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $link = new Link('Test', '/');
        $link->render($templates);
    }

    public function testRenderWithoutTemplate(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $link = new Link('Test 1', '/');
        $link->render(new Templates());
    }

    public function testRenderWithoutName(): void
    {
        $link = new Link('', '/');
        static::assertSame('', $link->render(new Templates()));
    }

    /**
     * @param string    $description Description of test
     * @param Templates $templates   Collection/storage of templates that will be required while rendering
     * @param Link      $link        Link to render
     * @param string    $expected    Expected rendered link
     *
     * @dataProvider provideTemplatesAndLinkToRender
     */
    public function testRender(string $description, Templates $templates, Link $link, string $expected): void
    {
        static::assertSame($expected, $link->render($templates), $description);
    }

    public function testAddAttribute(): void
    {
        $link = new Link('Test', '/');
        $link->addAttribute('id', 'test');
        $link->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($link, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function testAddAttributes(): void
    {
        $link = new Link('Test', '/');

        $link->addAttributes([
            'id' => 'test',
        ]);

        $link->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($link, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    /**
     * @param string $description Description of test
     * @param Link   $link        The link
     * @param string $expected    Expected name
     *
     * @dataProvider provideLinkToGetName
     */
    public function testGetName(string $description, Link $link, string $expected): void
    {
        static::assertSame($expected, $link->getName(), $description);
    }

    /**
     * @param string $description Description of test
     * @param Link   $link        The link
     * @param string $expected    Expected url
     *
     * @dataProvider provideLinkToGetUrl
     */
    public function testGetUrl(string $description, Link $link, string $expected): void
    {
        static::assertSame($expected, $link->getUrl(), $description);
    }

    public function provideIncompleteTemplates(): ?Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield[
            new Templates(),
            sprintf($template, Link::class),
        ];

        yield[
            new Templates([
                stdClass::class => new Template('<a href="%url%">%name%</a>'),
            ]),
            sprintf($template, Link::class),
        ];

        yield[
            new Templates([
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
            ]),
            sprintf($template, Link::class),
        ];
    }

    public function provideTemplatesAndLinkToRender(): ?Generator
    {
        $link1 = new Link('Test 1', '/');
        $link1->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue');

        $link2 = new Link('Test 2', '/');
        $link2->addAttribute('id', 'main-link');
        $link2->addAttribute('data-position', '12');

        yield[
            'Simple template',
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
            ]),
            new Link('Test', '/'),
            '<a href="/">Test</a>',
        ];

        yield[
            'Complex template',
            new Templates([
                Link::class => new Template('<a href="%url%" class="blue" data-title="test">%name%</a>'),
            ]),
            new Link('Test', '/'),
            '<a href="/" class="blue" data-title="test">Test</a>',
        ];

        yield[
            'With 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
            ]),
            $link1,
            '<a href="/" class="blue">Test 1</a>',
        ];

        yield[
            'With more than 1 attribute',
            new Templates([
                Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
            ]),
            $link2,
            '<a href="/" id="main-link" data-position="12">Test 2</a>',
        ];
    }

    public function provideLinkToGetName(): ?Generator
    {
        yield[
            'An empty name and url',
            new Link('', ''),
            '',
        ];

        yield[
            'Not empty name and empty url',
            new Link('Test', ''),
            'Test',
        ];

        yield[
            'Not empty name and not empty url',
            new Link('Test', '/'),
            'Test',
        ];
    }

    public function provideLinkToGetUrl(): ?Generator
    {
        yield[
            'An empty name and url',
            new Link('', ''),
            '',
        ];

        yield[
            'Not empty name and empty url',
            new Link('Test', ''),
            '',
        ];

        yield[
            'Not empty name and not empty url',
            new Link('Test', '/'),
            '/',
        ];
    }
}
