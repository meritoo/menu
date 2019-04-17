<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu;

use Meritoo\Common\Collection\Templates;
use Meritoo\Menu\Base\BaseMenuPart;

/**
 * Item of menu (container for a link)
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class Item extends BaseMenuPart
{
    /**
     * Item's link
     *
     * @var Link
     */
    private $link;

    /**
     * Rendered and prepared to display item's link
     *
     * @var string
     */
    private $linkRendered;

    /**
     * Class constructor
     *
     * @param Link $link Item's link
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    /**
     * Creates new item
     *
     * @param string     $linkName       Name of item's link
     * @param string     $linkUrl        Url of item's link
     * @param null|array $linkAttributes (optional) Attributes of item's link. Default: null (not provided).
     * @param null|array $itemAttributes (optional) Attributes of the item. Default: null (not provided).
     * @return Item
     */
    public static function create(
        string $linkName,
        string $linkUrl,
        ?array $linkAttributes = null,
        ?array $itemAttributes = null
    ): Item {
        $link = new Link($linkName, $linkUrl);
        $item = new static($link);

        if (null !== $linkAttributes) {
            $link->addAttributes($linkAttributes);
        }

        if (null !== $itemAttributes) {
            $item->addAttributes($itemAttributes);
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        $linkRendered = $this->renderLink($templates);

        // Item without link?
        if ('' === $linkRendered) {
            return '';
        }

        return parent::render($templates);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareTemplateValues(Templates $templates): array
    {
        $linkRendered = $this->renderLink($templates);

        return [
            'link' => $linkRendered,
        ];
    }

    /**
     * Renders item's link
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return string
     */
    private function renderLink(Templates $templates): string
    {
        if (null === $this->linkRendered) {
            $this->linkRendered = $this->link->render($templates);
        }

        return $this->linkRendered;
    }
}