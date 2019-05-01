<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu\Visitor\Factory;

use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Factory of visitors for each supported menu parts
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class VisitorFactory implements VisitorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createVisitor(MenuPart $menuPart): ?VisitorInterface
    {
        if ($menuPart instanceof Menu) {
            return $this->createMenuVisitor($menuPart);
        }

        if ($menuPart instanceof LinkContainer) {
            return $this->createLinkContainerVisitor($menuPart);
        }

        if ($menuPart instanceof Link) {
            return $this->createLinkVisitor($menuPart);
        }

        return null;
    }

    /**
     * Creates visitor for given menu
     *
     * @param Menu $menu The menu to visit
     * @return VisitorInterface
     */
    abstract protected function createMenuVisitor(Menu $menu): VisitorInterface;

    /**
     * Creates visitor for given container for a link
     *
     * @param LinkContainer $linkContainer The container for a link to visit
     * @return VisitorInterface
     */
    abstract protected function createLinkContainerVisitor(LinkContainer $linkContainer): VisitorInterface;

    /**
     * Creates visitor for given link
     *
     * @param Link $link The link to visit
     * @return VisitorInterface
     */
    abstract protected function createLinkVisitor(Link $link): VisitorInterface;
}
