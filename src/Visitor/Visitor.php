<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu\Visitor;

use Meritoo\Menu\MenuPart;
use Meritoo\Menu\Visitor\Factory\VisitorFactory;

/**
 * Visitor of any menu part
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Visitor implements VisitorInterface
{
    /**
     * Factory of visitors for each supported menu parts
     *
     * @var VisitorFactory
     */
    private $visitorFactory;

    /**
     * Class constructor
     *
     * @param VisitorFactory $visitorFactory Factory of visitors for each supported menu parts
     */
    public function __construct(VisitorFactory $visitorFactory)
    {
        $this->visitorFactory = $visitorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(MenuPart $menuPart): void
    {
        $visitor = $this->visitorFactory->createVisitor($menuPart);

        if (null === $visitor) {
            return;
        }

        $visitor->visit($menuPart);
    }
}
