<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Base\MenuPart;

use Meritoo\Common\Collection\Templates;
use Meritoo\Menu\MenuPart;

/**
 * Part of menu used by test case of \Meritoo\Menu\Base\MenuPart
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MySecondMenuPart extends MenuPart
{
    /**
     * @var string
     */
    private $weight;

    /**
     * @var string
     */
    private $color;

    /**
     * Class constructor
     *
     * @param string $weight
     * @param string $color
     */
    public function __construct(string $weight, string $color)
    {
        $this->weight = $weight;
        $this->color = $color;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        return [
            'weight' => $this->weight,
            'color'  => $this->color,
        ];
    }
}
