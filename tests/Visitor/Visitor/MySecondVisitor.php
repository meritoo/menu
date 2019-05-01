<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Visitor;

use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;
use Meritoo\Test\Menu\Base\MenuPart\MyFirstMenuPart;
use Meritoo\Test\Menu\Base\MenuPart\MySecondMenuPart;

/**
 * Visitor used by test case of \Meritoo\Menu\Base\Visitor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MySecondVisitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        if ($menuPart instanceof MyFirstMenuPart) {
            $this->visitFirstMenuPart($menuPart);

            return;
        }

        if ($menuPart instanceof MySecondMenuPart) {
            $this->visitSecondMenuPart($menuPart);
        }
    }

    private function visitFirstMenuPart(MyFirstMenuPart $menuPart): void
    {
        $attributes = $menuPart->getAttributesAsArray();

        if (0 === count($attributes)) {
            $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'visible');
        }
    }

    private function visitSecondMenuPart(MenuPart $menuPart): void
    {
        $attributes = $menuPart->getAttributesAsArray();

        if (0 < count($attributes)) {
            $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'second-menu');
        }
    }
}
