<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu\Visitor\Factory;

use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Factory of menu part's visitor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface VisitorFactoryInterface
{
    /**
     * Creates visitor for given menu part
     *
     * @param MenuPart $menuPart The for menu part to visit
     * @return null|VisitorInterface
     */
    public function createVisitor(MenuPart $menuPart): ?VisitorInterface;
}
