<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Utilities\Arrays;

/**
 * Menu. Has containers with links.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Menu extends MenuPart
{
    /**
     * Containers with links
     *
     * @var LinkContainer[]
     */
    private $linksContainers;

    /**
     * Rendered and prepared to display links' containers
     *
     * @var string
     */
    private $linksContainersRendered;

    /**
     * Class constructor
     *
     * @param LinkContainer[] $linksContainers Containers with links
     */
    public function __construct(array $linksContainers)
    {
        $this->linksContainers = $linksContainers;
    }

    /**
     * Returns containers with links
     *
     * @return LinkContainer[]
     */
    public function getLinksContainers(): array
    {
        return $this->linksContainers;
    }

    /**
     * Returns all menu parts (menu, containers with links and links)
     *
     * @return MenuPart[]
     */
    public function getAllMenuParts(): array
    {
        $links = [];

        foreach ($this->linksContainers as $container) {
            $links[] = $container->getLink();
        }

        $result = array_merge(
            [
                $this,
            ],
            $this->linksContainers,
            $links
        );

        return Arrays::getNonEmptyValues($result);
    }

    /**
     * Creates new menu
     *
     * @param array      $links          An array of arrays (0-based indexes): [0] name of link, [1] url of link, [2]
     *                                   (optional) attributes of link, [3] (optional) attributes of link's container
     * @param null|array $menuAttributes (optional) Attributes of the main container. It's an array of key-value pairs,
     *                                   where key - attribute, value - value of attribute
     * @return null|Menu
     */
    public static function create(array $links, ?array $menuAttributes = null): ?Menu
    {
        if (empty($links)) {
            return null;
        }

        $linksContainers = [];

        foreach ($links as $link) {
            [
                $name,
                $url,
                $linkAttributes,
                $containerAttributes,
            ] = static::getCreateLinkContainerArguments($link);

            $linksContainers[] = LinkContainer::create($name, $url, $linkAttributes, $containerAttributes);
        }

        $menu = new static($linksContainers);

        if (null !== $menuAttributes) {
            $menu->addAttributes($menuAttributes);
        }

        return $menu;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        // Nothing to do, because menu is empty
        if (empty($this->linksContainers)) {
            return '';
        }

        $rendered = $this->renderLinksContainers($templates);

        // Nothing to do, because menu is empty
        if ('' === $rendered) {
            return '';
        }

        return parent::render($templates);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        $rendered = $this->renderLinksContainers($templates);

        return [
            'linksContainers' => $rendered,
        ];
    }

    /**
     * Returns arguments used to create container for a link
     *
     * @param array $link Data of link. Should contain 4 keys (0-indexed): name, url, link's attributes, container's
     *                    attributes.
     * @return array
     */
    private static function getCreateLinkContainerArguments(array $link): array
    {
        return [
            $link[0] ?? '',
            $link[1] ?? '',
            $link[2] ?? null,
            $link[3] ?? null,
        ];
    }

    /**
     * Renders containers with links
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    private function renderLinksContainers(Templates $templates): string
    {
        if (null === $this->linksContainersRendered) {
            $this->linksContainersRendered = '';

            if (!empty($this->linksContainers)) {
                foreach ($this->linksContainers as $linkContainer) {
                    $this->linksContainersRendered .= $linkContainer->render($templates);
                }

                $this->linksContainersRendered = trim($this->linksContainersRendered);
            }
        }

        return $this->linksContainersRendered;
    }
}
