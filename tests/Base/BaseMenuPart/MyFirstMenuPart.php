<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu\Base\BaseMenuPart;

use Meritoo\Common\Collection\Templates;
use Meritoo\Menu\Base\BaseMenuPart;

/**
 * Part of menu used by test case of \Meritoo\Menu\Base\BaseMenuPart
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @coversNothing
 */
class MyFirstMenuPart extends BaseMenuPart
{
    /**
     * @var string
     */
    private $name;

    /**
     * Class constructor
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
