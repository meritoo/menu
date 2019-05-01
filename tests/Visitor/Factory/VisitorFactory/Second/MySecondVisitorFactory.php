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
use Meritoo\Menu\Visitor\Factory\VisitorFactoryInterface;
use Meritoo\Menu\Visitor\VisitorInterface;
use Meritoo\Test\Menu\Base\MenuPart\MySecondMenuPart;
use Meritoo\Test\Menu\Visitor\Visitor\MySecondVisitor;

/**
 * Factory of visitors used by test case of \Meritoo\Menu\Visitor\Factory\VisitorFactory
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MySecondVisitorFactory implements VisitorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createVisitor(MenuPart $menuPart): ?VisitorInterface
    {
        if ($menuPart instanceof Menu) {
            return new MySecondMenuVisitor();
        }

        if ($menuPart instanceof MySecondMenuPart) {
            return new MySecondVisitor();
        }

        return null;
    }
}
