<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Factory;

use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\Factory\VisitorFactory;
use Meritoo\Menu\Visitor\Factory\VisitorFactoryInterface;
use Meritoo\Menu\Visitor\VisitorInterface;
use Meritoo\Test\Menu\Base\MenuPart\MyFirstMenuPart;
use Meritoo\Test\Menu\Base\MenuPart\MySecondMenuPart;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First\MyFirstLinkContainerVisitor;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First\MyFirstLinkVisitor;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First\MyFirstMenuVisitor;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First\MyFirstVisitorFactory;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\Second\MySecondMenuVisitor;
use Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\Second\MySecondVisitorFactory;
use Meritoo\Test\Menu\Visitor\Visitor\MySecondVisitor;

/**
 * Test case for the factory of visitors for each supported menu parts
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\Visitor\Factory\VisitorFactory
 */
class VisitorFactoryTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertHasNoConstructor(VisitorFactory::class);
    }

    /**
     * @param string                  $description Description of test
     * @param VisitorFactoryInterface $factory     Factory of visitors
     * @param MenuPart                $menuPart    The menu part
     * @param null|VisitorInterface   $expected    Expected visitor
     *
     * @dataProvider provideMenuPartAndVisitor
     */
    public function testCreateVisitor(
        string $description,
        VisitorFactoryInterface $factory,
        MenuPart $menuPart,
        ?VisitorInterface $expected
    ): void {
        $visitor = $factory->createVisitor($menuPart);
        static::assertEquals($expected, $visitor, $description);
    }

    public function provideMenuPartAndVisitor(): ?\Generator
    {
        yield[
            'First visitor factory & Menu',
            new MyFirstVisitorFactory(),
            new Menu([]),
            new MyFirstMenuVisitor(),
        ];

        yield[
            'First visitor factory & LinkContainer',
            new MyFirstVisitorFactory(),
            new LinkContainer(new Link('Test', '/')),
            new MyFirstLinkContainerVisitor(),
        ];

        yield[
            'First visitor factory & Link',
            new MyFirstVisitorFactory(),
            new Link('Test', '/'),
            new MyFirstLinkVisitor(),
        ];

        yield[
            'First visitor factory & MyFirstMenuPart',
            new MyFirstVisitorFactory(),
            new MyFirstMenuPart('Test'),
            null,
        ];

        yield[
            'Second visitor factory & Menu',
            new MySecondVisitorFactory(),
            new Menu([]),
            new MySecondMenuVisitor(),
        ];

        yield[
            'Second visitor factory & MySecondMenuPart',
            new MySecondVisitorFactory(),
            new MySecondMenuPart('100', 'blue'),
            new MySecondVisitor(),
        ];

        yield[
            'Second visitor factory & Link',
            new MySecondVisitorFactory(),
            new Link('Test', '/'),
            null,
        ];
    }
}
