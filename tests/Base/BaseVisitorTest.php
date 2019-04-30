<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Base;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Menu\Base\BaseVisitor;
use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Test\Menu\Base\MenuPartVisitor\MyFirstVisitor;

/**
 * Test case for the base visitor of any menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\Base\BaseVisitor
 */
class BaseVisitorTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertHasNoConstructor(BaseVisitor::class);
    }

    /**
     * @param string      $description Description of test
     * @param BaseVisitor $visitor     The visitor
     * @param Menu        $menu        The menu to visit
     *
     * @dataProvider provideVisitorAndMenu
     */
    public function testVisitMenu(string $description, BaseVisitor $visitor, Menu $menu): void
    {
        $attributesBefore = $menu->getAttributesAsArray();
        $menu->accept($visitor);
        $attributesAfter = $menu->getAttributesAsArray();

        static::assertCount(0, $attributesBefore, $description);
        static::assertCount(1, $attributesAfter, $description);
        static::assertEquals(['id' => 'just-testing'], $attributesAfter, $description);
    }

    /**
     * @param string        $description   Description of test
     * @param BaseVisitor   $visitor       The visitor
     * @param LinkContainer $linkContainer The container for a link to visit
     *
     * @dataProvider provideVisitorAndLinkContainer
     */
    public function testVisitLinkContainer(
        string $description,
        BaseVisitor $visitor,
        LinkContainer $linkContainer
    ): void {
        $attributesBefore = $linkContainer->getAttributesAsArray();
        $linkContainer->accept($visitor);
        $attributesAfter = $linkContainer->getAttributesAsArray();

        static::assertCount(0, $attributesBefore, $description);
        static::assertCount(1, $attributesAfter, $description);
        static::assertEquals([Attributes::ATTRIBUTE_CSS_CLASS => 'first-container'], $attributesAfter, $description);
    }

    /**
     * @param string      $description Description of test
     * @param BaseVisitor $visitor     The visitor
     * @param Link        $link        The link to visit
     *
     * @dataProvider provideVisitorAndLink
     */
    public function testVisitLink(string $description, BaseVisitor $visitor, Link $link): void
    {
        $attributesBefore = $link->getAttributesAsArray();
        $link->accept($visitor);
        $attributesAfter = $link->getAttributesAsArray();

        static::assertCount(0, $attributesBefore, $description);
        static::assertCount(3, $attributesAfter, $description);

        static::assertEquals([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ], $attributesAfter, $description);
    }

    public function provideVisitorAndMenu(): ?\Generator
    {
        yield[
            'Menu without containers with links',
            new MyFirstVisitor(),
            new Menu([]),
        ];

        yield[
            'Menu with containers with links',
            new MyFirstVisitor(),
            new Menu([
                new LinkContainer(new Link('Test 1', '')),
                new LinkContainer(new Link('Test 2', '/')),
            ]),
        ];
    }

    public function provideVisitorAndLinkContainer(): ?\Generator
    {
        yield[
            '1st instance',
            new MyFirstVisitor(),
            new LinkContainer(new Link('', '')),
        ];

        yield[
            '2nd instance',
            new MyFirstVisitor(),
            new LinkContainer(new Link('Test', '/')),
        ];
    }

    public function provideVisitorAndLink(): ?\Generator
    {
        yield[
            '1st instance',
            new MyFirstVisitor(),
            new Link('', ''),
        ];

        yield[
            '2nd instance',
            new MyFirstVisitor(),
            new Link('Test', '/'),
        ];
    }
}
