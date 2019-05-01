<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Base;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\Visitor;
use Meritoo\Menu\Visitor\VisitorInterface;
use Meritoo\Test\Menu\Base\MenuPart\MyFirstMenuPart;
use Meritoo\Test\Menu\Base\MenuPart\MySecondMenuPart;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First\MyFirstVisitorFactory;
use Meritoo\Test\Menu\Visitor\Visitor\MyFirstVisitor;
use Meritoo\Test\Menu\Visitor\Visitor\MySecondVisitor;

/**
 * Test case for the part of menu, e.g. link, link\'s container
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\MenuPart
 */
class MenuPartTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertHasNoConstructor(MenuPart::class);
    }

    /**
     * @param string    $description    Description of test
     * @param MenuPart  $menuPart       The part of menu
     * @param Templates $templates      Collection/storage of templates that will be required while rendering this and
     *                                  related objects, e.g. children of this object
     * @param string    $expected       Expected result of rendering
     *
     * @dataProvider provideMenuPartForRender
     */
    public function testRender(string $description, MenuPart $menuPart, Templates $templates, string $expected): void
    {
        static::assertSame($expected, $menuPart->render($templates), $description);
    }

    /**
     * @param string     $description Description of test
     * @param MenuPart   $menuPart    The part of menu
     * @param string     $name        Name of attribute
     * @param string     $value       Value of attribute
     * @param Attributes $expected    Expected attributes
     *
     * @dataProvider provideAttributeToAdd
     */
    public function testAddAttribute(
        string $description,
        MenuPart $menuPart,
        string $name,
        string $value,
        Attributes $expected
    ): void {
        $menuPart->addAttribute($name, $value);

        $attributes = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $attributes, $description);
    }

    public function testAddAttributeMoreThanOnce(): void
    {
        $menuPart = new MySecondMenuPart('100', 'blue');
        $menuPart->addAttribute('id', 'test');
        $menuPart->addAttribute('data-start', 'true');
        $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    /**
     * @param string     $description Description of test
     * @param MenuPart   $menuPart    The part of menu
     * @param array      $attributes  Key-value pairs, where key - name of attribute, value-value of attribute
     * @param Attributes $expected    Expected attributes
     *
     * @dataProvider provideAttributesToAdd
     */
    public function testAddAttributes(
        string $description,
        MenuPart $menuPart,
        array $attributes,
        Attributes $expected
    ): void {
        $menuPart->addAttributes($attributes);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing, $description);
    }

    public function testAddAttributesMoreThanOnce(): void
    {
        $menuPart = new MySecondMenuPart('100', 'blue');

        $menuPart->addAttributes([
            'id' => 'test',
        ]);

        $menuPart->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($menuPart, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    /**
     * @param string   $description Description of test
     * @param MenuPart $menuPart    The part of menu
     * @param array    $expected    Expected attributes
     *
     * @dataProvider provideMenuPartAndAttributes
     */
    public function testGetAttributesAsArray(string $description, MenuPart $menuPart, array $expected): void
    {
        static::assertEquals($expected, $menuPart->getAttributesAsArray(), $description);
    }

    /**
     * @param string           $description        Description of test
     * @param VisitorInterface $visitor            The visitor
     * @param MenuPart         $menuPart           The menu part to visit
     * @param array            $expectedAttributes Expected attributes of menu part
     *
     * @dataProvider provideMenuPartAndVisitor
     */
    public function testAccept(
        string $description,
        VisitorInterface $visitor,
        MenuPart $menuPart,
        array $expectedAttributes
    ): void {
        $menuPart->accept($visitor);
        static::assertEquals($expectedAttributes, $menuPart->getAttributesAsArray(), $description);
    }

    public function provideMenuPartForRender(): ?Generator
    {
        $menuPart = new MyFirstMenuPart('Home');

        $menuPart->addAttributes([
            'id'                            => 'click-me',
            Attributes::ATTRIBUTE_CSS_CLASS => 'hide-sometimes',
        ]);

        yield[
            'First part of menu - Home',
            new MyFirstMenuPart('Home'),
            new Templates([
                MyFirstMenuPart::class => new Template('<span>%name%</span>'),
            ]),
            '<span>Home</span>',
        ];

        yield[
            'First part of menu - Offer',
            new MyFirstMenuPart('Offer'),
            new Templates([
                MyFirstMenuPart::class => new Template('<div>%name%</div>'),
            ]),
            '<div>Offer</div>',
        ];

        yield[
            'Second part of menu - 100g',
            new MySecondMenuPart('100g', 'white'),
            new Templates([
                MySecondMenuPart::class => new Template('<span>%weight% and %color%</span>'),
            ]),
            '<span>100g and white</span>',
        ];

        yield[
            'Second part of menu - 1t',
            new MySecondMenuPart('1t', 'black'),
            new Templates([
                MySecondMenuPart::class => new Template('<div>%weight% and %color%</div>'),
            ]),
            '<div>1t and black</div>',
        ];

        yield[
            'Part of menu with attributes',
            $menuPart,
            new Templates([
                MyFirstMenuPart::class => new Template('<span%attributes%>%name%</span>'),
            ]),
            '<span id="click-me" class="hide-sometimes">Home</span>',
        ];
    }

    public function provideAttributeToAdd(): ?Generator
    {
        yield[
            '1st instance',
            new MyFirstMenuPart('Home'),
            'id',
            'test',
            new Attributes(['id' => 'test']),
        ];

        yield[
            '2nd instance',
            new MyFirstMenuPart('Home'),
            Attributes::ATTRIBUTE_CSS_CLASS,
            'blue-box',
            new Attributes([Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box']),
        ];
    }

    public function provideAttributesToAdd(): ?Generator
    {
        yield[
            '1st instance',
            new MyFirstMenuPart('Home'),
            [
                'id' => 'test',
            ],
            new Attributes([
                'id' => 'test',
            ]),
        ];

        yield[
            '2nd instance',
            new MySecondMenuPart('100', 'blue'),
            [
                'id'                            => 'test',
                'data-start'                    => 'true',
                Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
            ],
            new Attributes([
                'id'                            => 'test',
                'data-start'                    => 'true',
                Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
            ]),
        ];
    }

    public function provideMenuPartAndAttributes(): ?Generator
    {
        $first = new MyFirstMenuPart('Test');

        $attributes = [
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ];

        $second = new MySecondMenuPart('100', 'blue');
        $second->addAttributes($attributes);

        yield[
            '1st instance',
            $first,
            [],
        ];

        yield[
            '2nd instance',
            $second,
            $attributes,
        ];
    }

    public function provideMenuPartAndVisitor(): ?Generator
    {
        yield[
            'Default Visitor & Menu',
            new Visitor(new MyFirstVisitorFactory()),
            new Menu([]),
            [
                'id' => 'main-menu',
            ],
        ];

        yield[
            'Default Visitor & MyFirstMenuPart',
            new Visitor(new MyFirstVisitorFactory()),
            new MyFirstMenuPart('Test'),
            [],
        ];

        yield[
            'Default Visitor & MySecondMenuPart',
            new Visitor(new MyFirstVisitorFactory()),
            new MySecondMenuPart('100', 'blue'),
            [],
        ];

        yield[
            'Custom Visitor & Menu 1',
            new MyFirstVisitor(),
            new Menu([]),
            [
                'id' => 'just-testing',
            ],
        ];

        yield[
            'Custom Visitor & Menu 2',
            new MyFirstVisitor(),
            new Menu([
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ]),
            [
                'id' => 'just-testing',
            ],
        ];

        yield[
            'Default Visitor & LinkContainer',
            new Visitor(new MyFirstVisitorFactory()),
            new LinkContainer(new Link('Test', '')),
            [
                Attributes::ATTRIBUTE_CSS_CLASS => 'link-wrapper',
            ],
        ];

        yield[
            'Custom Visitor & LinkContainer 1',
            new MyFirstVisitor(),
            new LinkContainer(new Link('', '')),
            [
                Attributes::ATTRIBUTE_CSS_CLASS => 'first-container',
            ],
        ];

        yield[
            'Custom Visitor & LinkContainer 2',
            new MyFirstVisitor(),
            new LinkContainer(new Link('Test', '/')),
            [
                Attributes::ATTRIBUTE_CSS_CLASS => 'first-container',
            ],
        ];

        yield[
            'Default Visitor & Link',
            new Visitor(new MyFirstVisitorFactory()),
            new Link('Test', ''),
            [
                'data-start' => 'true',
            ],
        ];

        yield[
            'Custom Visitor & Link 1',
            new MyFirstVisitor(),
            new Link('', ''),
            [
                'id'                            => 'test',
                'data-start'                    => 'true',
                Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
            ],
        ];

        yield[
            'Custom Visitor & Link 2',
            new MySecondVisitor(),
            new Link('Test', '/'),
            [],
        ];
    }
}
