<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu\Visitor;

use Meritoo\Menu\MenuPart;

/**
 * Visitor for menu part.
 * Allows to run functionality outside the menu part, e.g. to make modifications.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
interface VisitorInterface
{
    /**
     * Visits given part of menu
     *
     * @param MenuPart $menuPart Part of menu to visit
     */
    public function visit(MenuPart $menuPart): void;
}
