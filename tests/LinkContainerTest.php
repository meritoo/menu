<?php

/**
 * (c) Meritoo.pl, http://www.meritoo.pl
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Meritoo\Test\Menu;

use Generator;
use Meritoo\Common\Collection\Templates;
use Meritoo\Common\Exception\ValueObject\Template\TemplateNotFoundException;
use Meritoo\Common\Test\Base\BaseTestCase;
use Meritoo\Common\Type\OopVisibilityType;
use Meritoo\Common\Utilities\Reflection;
use Meritoo\Common\ValueObject\Template;
use Meritoo\Menu\Html\Attributes;
use Meritoo\Menu\Link;
use Meritoo\Menu\LinkContainer;
use stdClass;

/**
 * Test case for the container for a link
 *
 * @author    Meritoo <github@meritoo.pl>
 * @copyright Meritoo <http://www.meritoo.pl>
 *
 * @internal
 * @covers    \Meritoo\Menu\LinkContainer
 */
class LinkContainerTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        static::assertConstructorVisibilityAndArguments(
            LinkContainer::class,
            OopVisibilityType::IS_PUBLIC,
            1,
            1
        );
    }

    /**
     * @param Templates $templates       Collection/storage of templates that will be required while rendering
     * @param string    $expectedMessage Expected message of exception
     *
     * @dataProvider provideIncompleteTemplates
     */
    public function testRenderWithoutTemplates(Templates $templates, string $expectedMessage): void
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage($expectedMessage);

        $linkContainer = new LinkContainer(new Link('Test', '/'));
        $linkContainer->render($templates);
    }

    public function testRenderWithoutTemplate(): void
    {
        $this->expectException(TemplateNotFoundException::class);

        $linkContainer = new LinkContainer(new Link('Test', ''));
        $linkContainer->render(new Templates());
    }

    public function testRenderUsingLinkWithoutName(): void
    {
        $linkContainer = new LinkContainer(new Link('', '/'));
        static::assertSame('', $linkContainer->render(new Templates()));
    }

    public function testAddAttribute(): void
    {
        $linkContainer = new LinkContainer(new Link('', '/'));
        $linkContainer->addAttribute('id', 'test');
        $linkContainer->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue-box');

        $expected = new Attributes([
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($linkContainer, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function testAddAttributes(): void
    {
        $linkContainer = new LinkContainer(new Link('', '/'));

        $linkContainer->addAttributes([
            'id' => 'test',
        ]);

        $linkContainer->addAttributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $expected = new Attributes([
            'id'                            => 'test',
            'data-start'                    => 'true',
            Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
        ]);

        $existing = Reflection::getPropertyValue($linkContainer, 'attributes', true);
        static::assertEquals($expected, $existing);
    }

    public function provideIncompleteTemplates(): ?Generator
    {
        $template = 'Template with \'%s\' index was not found. Did you provide all required templates?';

        yield[
            new Templates(),
            sprintf($template, Link::class),
        ];

        yield[
            new Templates([
                Link::class => new Template('<a href="%url%">%name%</a>'),
            ]),
            sprintf($template, LinkContainer::class),
        ];

        yield[
            new Templates([
                Link::class     => new Template('<a href="%url%">%name%</a>'),
                stdClass::class => new Template('<div class="container">%linksContainers%</div>'),
            ]),
            sprintf($template, LinkContainer::class),
        ];
    }

    /**
     * @param string        $description   Description of test
     * @param Templates     $templates     Collection/storage of templates that will be required while rendering
     * @param LinkContainer $linkContainer Container for a link to render
     * @param string        $expected      Expected rendered container for a link
     *
     * @dataProvider provideTemplatesAndLinkContainerToRender
     */
    public function testRender(
        string $description,
        Templates $templates,
        LinkContainer $linkContainer,
        string $expected
    ): void {
        static::assertSame($expected, $linkContainer->render($templates), $description);
    }

    public function provideTemplatesAndLinkContainerToRender(): ?Generator
    {
        $link = new Link('Test', '/');

        $linkContainer1 = new LinkContainer($link);
        $linkContainer1->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'blue');

        $linkContainer2 = new LinkContainer($link);
        $linkContainer2->addAttribute('id', 'just-testing');
        $linkContainer2->addAttribute('data-position', '12');

        yield[
            'Simple template',
            new Templates([
                Link::class          => new Template('<a href="%url%">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
            ]),
            new LinkContainer(new Link('Test', '/')),
            '<div class="wrapper"><a href="/">Test</a></div>',
        ];

        yield[
            'Complex template',
            new Templates([
                Link::class          => new Template('<a href="%url%" class="blue" data-title="test">%name%</a>'),
                LinkContainer::class => new Template('<div class="wrapper" data-placement="top">%link%</div>'),
            ]),
            new LinkContainer(new Link('Test', '/')),
            '<div class="wrapper" data-placement="top"><a href="/" class="blue" data-title="test">Test</a></div>',
        ];

        yield[
            'With 1 attribute',
            new Templates([
                Link::class          => new Template('<a href="%url%"%attributes%>%name%</a>'),
                LinkContainer::class => new Template('<div%attributes%>%link%</div>'),
            ]),
            $linkContainer1,
            '<div class="blue"><a href="/">Test</a></div>',
        ];

        yield[
            'With more than 1 attribute',
            new Templates([
                Link::class          => new Template('<a href="%url%"%attributes%>%name%</a>'),
                LinkContainer::class => new Template('<div%attributes%>%link%</div>'),
            ]),
            $linkContainer2,
            '<div id="just-testing" data-position="12"><a href="/">Test</a></div>',
        ];
    }

    /**
     * @param string        $description             Description of test
     * @param LinkContainer $expected                Expected container for a link
     * @param string        $linkName                Name of container's link
     * @param string        $linkUrl                 Url of container's link
     * @param null|array    $linkAttributes          (optional) Attributes of container's link. Default: null (not
     *                                               provided).
     * @param null|array    $linkContainerAttributes (optional) (optional) Attributes of the container. Default: null
     *                                               (not provided).
     *
     * @dataProvider provideDataToCreate
     */
    public function testCreate(
        string $description,
        LinkContainer $expected,
        string $linkName,
        string $linkUrl,
        ?array $linkAttributes = null,
        ?array $linkContainerAttributes = null
    ): void {
        $linkContainer = LinkContainer::create($linkName, $linkUrl, $linkAttributes, $linkContainerAttributes);
        static::assertEquals($expected, $linkContainer, $description);
    }

    /**
     * @param string        $description Description of test
     * @param LinkContainer $container   Container for a link
     * @param Link          $expected    Expected link
     *
     * @dataProvider provideContainerToGetLink
     */
    public function testGetLink(string $description, LinkContainer $container, Link $expected): void
    {
        static::assertEquals($expected, $container->getLink(), $description);
    }

    public function provideDataToCreate(): ?Generator
    {
        $linkAttributes = [
            'id'                            => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-class',
        ];

        $linkContainerAttributes = [
            'data-show'                     => 'test',
            Attributes::ATTRIBUTE_CSS_CLASS => 'my-big-class',
        ];

        $linkWithAttributes = new Link('Test', '/test');
        $linkWithAttributes->addAttributes($linkAttributes);

        $linkContainerWithAttributes = new LinkContainer(new Link('Test', '/test'));
        $linkContainerWithAttributes->addAttributes($linkContainerAttributes);

        $linkContainerWithAttributesAndLinkWithAttributes = new LinkContainer($linkWithAttributes);
        $linkContainerWithAttributesAndLinkWithAttributes->addAttributes($linkContainerAttributes);

        yield[
            'An empty name and url of link',
            new LinkContainer(new Link('', '')),
            '',
            '',
        ];

        yield[
            'Not empty name and empty url of link',
            new LinkContainer(new Link('Test', '')),
            'Test',
            '',
        ];

        yield[
            'An empty name and not empty url of link',
            new LinkContainer(new Link('', 'Test')),
            '',
            'Test',
        ];

        yield[
            'Not empty name and not empty url of link',
            new LinkContainer(new Link('Test', '/test')),
            'Test',
            '/test',
        ];

        yield[
            'Link with attributes',
            new LinkContainer($linkWithAttributes),
            'Test',
            '/test',
            $linkAttributes,
        ];

        yield[
            'Link\'s container with attributes',
            $linkContainerWithAttributes,
            'Test',
            '/test',
            null,
            $linkContainerAttributes,
        ];

        yield[
            'Link and link\'s container with attributes',
            $linkContainerWithAttributesAndLinkWithAttributes,
            'Test',
            '/test',
            $linkAttributes,
            $linkContainerAttributes,
        ];
    }

    public function provideContainerToGetLink(): ?Generator
    {
        yield[
            'An empty name and url of link',
            new LinkContainer(new Link('', '')),
            new Link('', ''),
        ];

        yield[
            'Not empty name and empty url of link',
            new LinkContainer(new Link('Test', '')),
            new Link('Test', ''),
        ];

        yield[
            'Container created using static method create()',
            LinkContainer::create('Test', ''),
            new Link('Test', ''),
        ];
    }
}
