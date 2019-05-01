<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Visitor\Factory\VisitorFactory\Second;

use Meritoo\Menu\Menu;
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
class MySecondMenuVisitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        if ($menuPart instanceof Menu) {
            $menuPart->addAttribute('id', 'second-level');
        }
    }
}
