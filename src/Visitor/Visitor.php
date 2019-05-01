<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu\Visitor;

use Meritoo\Menu\Base\BaseMenuPart;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;

/**
 * Visitor of any menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class Visitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(BaseMenuPart $menuPart): void
    {
        if ($menuPart instanceof Menu) {
            $this->visitMenu($menuPart);

            return;
        }

        if ($menuPart instanceof LinkContainer) {
            $this->visitLinkContainer($menuPart);

            return;
        }

        if ($menuPart instanceof Link) {
            $this->visitLink($menuPart);
        }
    }

    /**
     * Visits given menu
     *
     * @param Menu $menu The menu to visit
     */
    abstract protected function visitMenu(Menu $menu): void;

    /**
     * Visits given container for a link
     *
     * @param LinkContainer $linkContainer Container for a link to visit
     */
    abstract protected function visitLinkContainer(LinkContainer $linkContainer): void;

    /**
     * Visits given link
     *
     * @param Link $link Link to visit
     */
    abstract protected function visitLink(Link $link): void;
}
