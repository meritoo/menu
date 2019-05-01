<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First;

use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\Visitor\Factory\VisitorFactory;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Factory of visitors used by test case of \Meritoo\Menu\Visitor\Factory\VisitorFactory
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MyFirstVisitorFactory extends VisitorFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createLinkContainerVisitor(LinkContainer $linkContainer): VisitorInterface
    {
        return new MyFirstLinkContainerVisitor();
    }

    /**
     * {@inheritdoc}
     */
    protected function createLinkVisitor(Link $link): VisitorInterface
    {
        return new MyFirstLinkVisitor();
    }

    /**
     * {@inheritdoc}
     */
    protected function createMenuVisitor(Menu $menu): VisitorInterface
    {
        return new MyFirstMenuVisitor();
    }
}
