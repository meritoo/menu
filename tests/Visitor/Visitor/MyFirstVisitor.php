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
use Meritoo\Menu\Visitor\Visitor;

/**
 * Visitor used by test case of \Meritoo\Menu\Base\Visitor
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MyFirstVisitor extends Visitor
{
    /**
     * {@inheritdoc}
     */
    protected function visitLink(Link $link): void
    {
        $link->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function visitLinkContainer(LinkContainer $linkContainer): void
    {
        $linkContainer->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'first-container');
    }

    /**
     * {@inheritdoc}
     */
    protected function visitMenu(Menu $menu): void
    {
        $menu->addAttribute('id', 'just-testing');
    }
}
