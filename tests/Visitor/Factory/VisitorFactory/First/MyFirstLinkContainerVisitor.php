<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\First;

use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\LinkContainer;
use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Visitor used by test case of \Meritoo\Menu\Visitor\Factory\VisitorFactory
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MyFirstLinkContainerVisitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        if ($menuPart instanceof LinkContainer) {
            $menuPart->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'link-wrapper');
        }
    }
}
