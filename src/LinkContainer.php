<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu;

use Meritoo\Common\Collection\Templates;

/**
 * Container for a link
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
class LinkContainer extends MenuPart
{
    /**
     * Container's link
     *
     * @var Link
     */
    private $link;

    /**
     * Rendered and prepared to display container's link
     *
     * @var string
     */
    private $linkRendered;

    /**
     * Class constructor
     *
     * @param Link $link Container's link
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    /**
     * Creates new container for a link
     *
     * @param string     $linkName            Name of container's link
     * @param string     $linkUrl             Url of container's link
     * @param null|array $linkAttributes      (optional) Attributes of link. Default: null (not provided).
     * @param null|array $containerAttributes (optional) Attributes of the container. Default: null (not provided).
     * @return LinkContainer
     */
    public static function create(
        string $linkName,
        string $linkUrl,
        ?array $linkAttributes = null,
        ?array $containerAttributes = null
    ): LinkContainer {
        $link = new Link($linkName, $linkUrl);
        $container = new static($link);

        if (null !== $linkAttributes) {
            $link->addAttributes($linkAttributes);
        }

        if (null !== $containerAttributes) {
            $container->addAttributes($containerAttributes);
        }

        return $container;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        $linkRendered = $this->renderLink($templates);

        // Nothing to do, because rendered link is empty
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
     * Renders container's link
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
