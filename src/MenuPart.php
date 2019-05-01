<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Menu;

use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\ValueObject\Template\MissingPlaceholdersInValuesException;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Renderable\RenderableInterface;
use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Visitor\VisitorInterface;

/**
 * Part of menu, e.g. link, link\'s container.
 * Base class for any of menu part.
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 */
abstract class MenuPart implements RenderableInterface
{
    /**
     * HTML attributes, e.g. "class", "id"
     *
     * @var Attributes
     */
    private $attributes;

    /**
     * {@inheritdoc}
     */
    public function render(Templates $templates): string
    {
        return $this->findAndFillTemplate($templates);
    }

    /**
     * Adds attribute
     *
     * @param string $name  Name of attribute
     * @param string $value Value of attribute
     */
    public function addAttribute(string $name, string $value): void
    {
        $this->getAttributes()->add($value, $name);
    }

    /**
     * Adds attributes
     *
     * @param array $attributes Key-value pairs, where key - name of attribute, value-value of attribute
     */
    public function addAttributes(array $attributes): void
    {
        foreach ($attributes as $name => $value) {
            $this->addAttribute($name, $value);
        }
    }

    /**
     * Runs given visitor
     *
     * @param VisitorInterface $visitor The visitor to run
     */
    public function accept(VisitorInterface $visitor): void
    {
        $visitor->visit($this);
    }

    /**
     * Returns attributes exported to array
     *
     * @return array
     */
    public function getAttributesAsArray(): array
    {
        if ($this->getAttributes()->isEmpty()) {
            return [];
        }

        return $this->getAttributes()->toArray();
    }

    /**
     * Prepares and returns values used to fill template while rendering
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return array
     */
    abstract protected function prepareTemplateValues(Templates $templates): array;

    /**
     * Returns HTML attributes, e.g. "class", "id"
     *
     * @return Attributes
     */
    private function getAttributes(): Attributes
    {
        if (null === $this->attributes) {
            $this->attributes = new Attributes();
        }

        return $this->attributes;
    }

    /**
     * Returns attributes prepared to use/display as string
     *
     * @return string
     */
    private function getAttributesAsString(): string
    {
        if ($this->getAttributes()->isEmpty()) {
            return '';
        }

        return ' ' . $this->getAttributes()->asString();
    }

    /**
     * Prepares and returns common values used to fill template while rendering
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @return array
     */
    private function prepareCommonTemplateValues(Templates $templates): array
    {
        $values = $this->prepareTemplateValues($templates);
        $attributes = $this->getAttributesAsString();

        $commonValues = [
            'attributes' => $attributes,
        ];

        return array_merge($commonValues, $values);
    }

    /**
     * Finds and fills template used while rendering
     *
     * @param Templates $templates Collection/storage of templates that will be required while rendering this and
     *                             related objects, e.g. children of this object
     * @throws MissingPlaceholdersInValuesException
     * @throws TemplateNotFoundException
     * @return string
     */
    private function findAndFillTemplate(Templates $templates): string
    {
        $template = $templates->findTemplate(static::class);
        $values = $this->prepareCommonTemplateValues($templates);

        return $template->fill($values);
    }
}
