<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
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
 * Test case for the visitor of any menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\Visitor\Visitor
 */
class VisitorTest extends BaseTestCase
{
    public function testConstructorVisibilityAndArguments(): void
    {
        static::assertConstructorVisibilityAndArguments(
            Visitor::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    public function testConstructor(): void
    {
        $menu = new Menu([]);

        $visitor = new Visitor(new MyFirstVisitorFactory());
        $visitor->visit($menu);

        static::assertCount(1, $menu->getAttributesAsArray());
    }

    /**
     * @param string           $description        Description of test
     * @param VisitorInterface $visitor            The visitor
     * @param MenuPart         $menuPart           The menu part to visit
     * @param array            $expectedAttributes Expected attributes of menu part
     *
     * @dataProvider provideVisitorAndMenuPart
     */
    public function testVisit(
        string $description,
        VisitorInterface $visitor,
        MenuPart $menuPart,
        array $expectedAttributes
    ): void {
        $attributesBefore = $menuPart->getAttributesAsArray();
        $menuPart->accept($visitor);
        $attributesAfter = $menuPart->getAttributesAsArray();

        static::assertEquals([], $attributesBefore, $description);
        static::assertEquals($expectedAttributes, $attributesAfter, $description);
    }

    public function provideVisitorAndMenuPart(): ?\Generator
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
