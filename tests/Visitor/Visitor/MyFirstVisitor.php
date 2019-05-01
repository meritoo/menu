<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Visitor;

use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\Menu;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;
use Meritoo\Test\Menu\Base\MenuPart\MyFirstMenuPart;

/**
 * Visitor used by test case of \Meritoo\Menu\Base\Visitor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MyFirstVisitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
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

            return;
        }

        if ($menuPart instanceof MyFirstMenuPart) {
            $this->visitFirstMenuPart($menuPart);
        }
    }

    private function visitMenu(Menu $menu): void
    {
        $menu->addAttribute('id', 'just-testing');
    }

    private function visitLinkContainer(LinkContainer $linkContainer): void
    {
        $linkContainer->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'first-container');
    }

    private function visitLink(Link $link): void
    {
        $link->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);
    }

    private function visitFirstMenuPart(MyFirstMenuPart $menuPart): void
    {
        $attributes = $menuPart->getAttributesAsArray();

        if (0 === count($attributes)) {
            $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'visible');
        }
    }
}
