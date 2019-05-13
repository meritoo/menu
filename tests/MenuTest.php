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
use Meritoo\Menu\Menu;
use stdClass;

/**
 * Test case for the menu
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\Menu
 */
class MenuTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Menu::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    /**
     * @param string $description Description of test
     * @param Menu   $menu        Menu to verify
     * @param array  $expected    Expected containers with links
     *
     * @dataProvider provideMenuToGetLinksContainers
     */
    public function testGetLinksContainers(string $description, Menu $menu, array $expected): void
    {
        static::assertEquals($expected, $menu->getLinksContainers(), $description);
    }

    /**
     * @param string $description Description of test
     * @param Menu   $menu        Menu to verify
     * @param array  $expected    Expected menu parts
     *
     * @dataProvider provideMenuToGetAllMenuParts
     */
    public function testGetAllMenuParts(string $description, Menu $menu, array $expected): void
    {
        static::assertEquals($expected, $menu->getAllMenuParts(), $description);
    }

    public function testRenderWithoutLinksContainers(): void
    {
        $menu = new Menu([]);
        static::assertSame('', $menu->render(new Templates()));
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

        $menu = new Menu([
            new LinkContainer(new Link('Test 1', '')),
            new LinkContainer(new Link('Test 2', '/')),
        ]);

        $menu->render($templates);
    }

    public function testRenderUsingLinksWithoutNames(): void
    {
        $menu = new Menu([
            new LinkContainer(new Link('', '')),
            new LinkContainer(new Link('', '/')),
        ]);

        static::assertSame('', $menu->render(new Templates()));
    }

    /**
     * @param string    $description Description of test
     * @param Templates $templates   Collection/storage of templates that will be required while rendering
     * @param Menu      $menu        Menu to render
     * @param string    $expected    Expected rendered menu
     *
     * @dataProvider provideTemplatesAndMenuToRender
     */
    public function testRender(string $description, Templates $templates, Menu $menu, string $expected): void
    {
        static::assertSame($expected, $menu->render($templates), $description);
    }

    public function testAddAttribute(): void
    {
        $menu = new Menu([
            new LinkContainer(new Link('Test 1', '')),
            new LinkContainer(new Link('Test 2', '/')),
        ]);

        $menu->addAttribute('id', 'test');
        $menu->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menu, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function testAddAttributes(): void
    {
        $menu = new Menu([
            new LinkContainer(new Link('Test 1', '')),
            new LinkContainer(new Link('Test 2', '/')),
        ]);

        $menu->addAttributes([
            'id' => 'test',
        ]);

        $menu->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menu, 'attributes', true);
        static::assertEquals($expected, $existing);
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
                Link::class => new Template('<a href="%url%">%name%</a>'),
            ]),
            sprintf($template, LinkContainer::class),
        ];

        yield[
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
            ]),
            sprintf($template, Menu::class),
        ];

        yield[
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
                stdClass::class      => new Template('<div class="container">%linksContainers%</div>'),
            ]),
            sprintf($template, Menu::class),
        ];
    }

    /**
     * @param string    $description Description of test
     * @param array     $links       An array of arrays (0-based indexes): [0] name of link, [1] url of link
     * @param null|Menu $expected    Expected Menu
     *
     * @dataProvider provideLinksContainersToCreate
     */
    public function testCreate(string $description, array $links, ?Menu $expected): void
    {
        static::assertEquals($expected, Menu::create($links), $description);
    }

    /**
     * @param string     $description    Description of test
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of link's container
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @param null|Menu  $expected       Expected Menu
     *
     * @dataProvider provideLinksContainersToCreateWithAttributes
     */
    public function testCreateWithAttributes(
        string $description,
        ?Menu $expected,
        array $links,
        ?array $menuAttributes = null
    ): void {
        static::assertEquals($expected, Menu::create($links, $menuAttributes), $description);
    }

    public function provideTemplatesAndMenuToRender(): ?Generator
    {
        $linksContainers = [
            new LinkContainer(new Link('Test 1', '/test1')),
        ];

        $menu1 = new Menu($linksContainers);
        $menu1->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'container');

        $menu2 = new Menu($linksContainers);
        $menu2->addAttribute('id', 'main-menu');
        $menu2->addAttribute('data-position', '12');

        yield[
            'Menu with 1 link\'s container only',
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
                Menu::class          => new Template('<div class="container">%linksContainers%</div>'),
            ]),
            new Menu($linksContainers),
            '<div class="container">'
            . '<div class="wrapper"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];

        yield[
            'Menu with 3 links\' containers',
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
                Menu::class          => new Template('<div class="container">%linksContainers%</div>'),
            ]),
            new Menu([
                new LinkContainer(new Link('Test 1', '/test1')),
                new LinkContainer(new Link('Test 2', '/test2')),
                new LinkContainer(new Link('Test 3', '/test3')),
            ]),
            '<div class="container">'
            . '<div class="wrapper"><a href="/test1">Test 1</a></div>'
            . '<div class="wrapper"><a href="/test2">Test 2</a></div>'
            . '<div class="wrapper"><a href="/test3">Test 3</a></div>'
            . '</div>',
        ];

        yield[
            'Menu with 3 links\' containers and unordered list as template',
            new Templates([
                Link::class          => new Template('<a href="%url%" class="blue">%name%</a>'),
                LinkContainer::class => new Template('<li class="wrapper">%link%</li>'),
                Menu::class          => new Template('<div class="container"><ul class="wrapper">%linksContainers%</ul></div>'),
            ]),
            new Menu([
                new LinkContainer(new Link('Test 1', '/test1')),
                new LinkContainer(new Link('Test 2', '/test2')),
                new LinkContainer(new Link('Test 3', '/test3')),
            ]),
            '<div class="container">'
            . '<ul class="wrapper">'
            . '<li class="wrapper"><a href="/test1" class="blue">Test 1</a></li>'
            . '<li class="wrapper"><a href="/test2" class="blue">Test 2</a></li>'
            . '<li class="wrapper"><a href="/test3" class="blue">Test 3</a></li>'
            . '</ul>'
            . '</div>',
        ];

        yield[
            'With 1 attribute',
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
                Menu::class          => new Template('<div%attributes%>%linksContainers%</div>'),
            ]),
            $menu1,
            '<div class="container">'
            . '<div class="wrapper"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];

        yield[
            'With more than 1 attribute',
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
                Menu::class          => new Template('<div%attributes%>%linksContainers%</div>'),
            ]),
            $menu2,
            '<div id="main-menu" data-position="12">'
            . '<div class="wrapper"><a href="/test1">Test 1</a></div>'
            . '</div>',
        ];
    }

    public function provideLinksContainersToCreate(): ?Generator
    {
        yield[
            'An empty array',
            [],
            null,
        ];

        yield[
            'Link\'s container with empty strings',
            [
                [
                    '',
                    '',
                ],
            ],
            new Menu([new LinkContainer(new Link('', ''))]),
        ];

        yield[
            'Link\'s container with incorrect indexes',
            [
                [
                    'x' => 'Test 1',
                    'y' => '/test',
                ],
            ],
            new Menu([new LinkContainer(new Link('', ''))]),
        ];

        yield[
            'Link\'s container with not empty name and empty url',
            [
                [
                    'Test',
                    '',
                ],
            ],
            new Menu([new LinkContainer(new Link('Test', ''))]),
        ];

        yield[
            'Link\'s container with not empty name and not empty url',
            [
                [
                    'Test',
                    '/',
                ],
            ],
            new Menu([new LinkContainer(new Link('Test', '/'))]),
        ];

        yield[
            'More than 1 link\'s container',
            [
                [
                    'Test 1',
                    '/test',
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                ],
            ],
            new Menu([
                new LinkContainer(new Link('Test 1', '/test')),
                new LinkContainer(new Link('Test 2', '/test/2')),
                new LinkContainer(new Link('Test 3', '/test/46/test')),
            ]),
        ];
    }

    public function provideLinksContainersToCreateWithAttributes(): ?Generator
    {
        $link1Attributes = [
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-class',
        ];

        $link3Attributes = [
            'id'                            => 'test-test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-another-class',
        ];

        $linkContainer1Attributes = [
            'data-show'                     => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
        ];

        $linkContainer2Attributes = [
            'data-show'                     => 'test-test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-next-class',
        ];

        $linkContainer3Attributes = [
            'id'                            => 'test-test',
            'data-show'                     => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-last-class',
        ];

        $menu1Attributes = [
            'id'                            => 'main',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-menu',
        ];

        $menu2Attributes = [
            'id'                            => 'left-navigation',
            Attributes::ATTRIBUTE_CSS_CLASS => 'hide-xs',
        ];

        $link1WithAttributes = new Link('Test 1', '/test');
        $link1WithAttributes->addAttributes($link1Attributes);

        $link3WithAttributes = new Link('Test 3', '/test/46/test');
        $link3WithAttributes->addAttributes($link3Attributes);

        $linkContainer1WithAttributes = new LinkContainer($link1WithAttributes);
        $linkContainer1WithAttributes->addAttributes($linkContainer1Attributes);

        $linkContainer2WithAttributes = new LinkContainer(new Link('Test 2', '/test/2'));
        $linkContainer2WithAttributes->addAttributes($linkContainer2Attributes);

        $linkContainer3WithAttributes = new LinkContainer($link3WithAttributes);
        $linkContainer3WithAttributes->addAttributes($linkContainer3Attributes);

        $menu1WithAttributes = new Menu([
            new LinkContainer(new Link('Test 1', '/test')),
            new LinkContainer(new Link('Test 2', '/test/2')),
            new LinkContainer(new Link('Test 3', '/test/46/test')),
        ]);

        $menu2WithAttributes = new Menu([
            new LinkContainer($link1WithAttributes),
            new LinkContainer(new Link('Test 2', '/test/2')),
            new LinkContainer($link3WithAttributes),
        ]);

        $menu1WithAttributes->addAttributes($menu1Attributes);
        $menu2WithAttributes->addAttributes($menu2Attributes);

        yield[
            'Links with attributes',
            new Menu([
                new LinkContainer($link1WithAttributes),
                new LinkContainer(new Link('Test 2', '/test/2')),
                new LinkContainer($link3WithAttributes),
            ]),
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                ],
            ],
        ];

        yield[
            'Links and links\' containers with attributes',
            new Menu([
                $linkContainer1WithAttributes,
                $linkContainer2WithAttributes,
                $linkContainer3WithAttributes,
            ]),
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                    $linkContainer1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                    null,
                    $linkContainer2Attributes,
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                    $linkContainer3Attributes,
                ],
            ],
        ];

        yield[
            'Menu only with attributes',
            $menu1WithAttributes,
            [
                [
                    'Test 1',
                    '/test',
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                ],
            ],
            $menu1Attributes,
        ];

        yield[
            'Menu, links and links\' containers with attributes',
            $menu2WithAttributes,
            [
                [
                    'Test 1',
                    '/test',
                    $link1Attributes,
                ],
                [
                    'Test 2',
                    '/test/2',
                ],
                [
                    'Test 3',
                    '/test/46/test',
                    $link3Attributes,
                ],
            ],
            $menu2Attributes,
        ];
    }

    public function provideMenuToGetLinksContainers(): ?Generator
    {
        yield[
            'No containers',
            new Menu([]),
            [],
        ];

        yield[
            '1 container only',
            new Menu([
                new LinkContainer(new Link('', '')),
            ]),
            [
                new LinkContainer(new Link('', '')),
            ],
        ];

        yield[
            '2 containers',
            new Menu([
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ]),
            [
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ],
        ];

        yield[
            '2 containers - created by static method create()',
            Menu::create([
                [
                    'Test 1',
                    '',
                ],
                [
                    'Test 2',
                    '/',
                ],
            ]),
            [
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ],
        ];
    }

    public function provideMenuToGetAllMenuParts(): ?Generator
    {
        yield[
            'No menu parts',
            new Menu([]),
            [
                new Menu([]),
            ],
        ];

        yield[
            '1 container only',
            new Menu([
                new LinkContainer(new Link('', '')),
            ]),
            [
                new Menu([
                    new LinkContainer(new Link('', '')),
                ]),
                new LinkContainer(new Link('', '')),
                new Link('', ''),
            ],
        ];

        yield[
            '2 containers',
            new Menu([
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ]),
            [
                new Menu([
                    new LinkContainer(new Link('Test 1', '')),
                    new LinkContainer(new Link('Test 2', '/')),
                ]),
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
                new Link('Test 1', ''),
                new Link('Test 2', '/'),
            ],
        ];

        yield[
            '2 containers - created by static method create()',
            Menu::create([
                [
                    'Test 1',
                    '',
                ],
                [
                    'Test 2',
                    '/',
                ],
            ]),
            [
                new Menu([
                    new LinkContainer(new Link('Test 1', '')),
                    new LinkContainer(new Link('Test 2', '/')),
                ]),
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
                new Link('Test 1', ''),
                new Link('Test 2', '/'),
            ],
        ];
    }
}
