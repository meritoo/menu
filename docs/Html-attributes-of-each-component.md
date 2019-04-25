# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# HTML attributes of each component

Based on `Meritoo\Common\ValueObject\Template` class from [meritoo/common-library](https://github.com/meritoo/common-library) package. [See more](https://github.com/meritoo/common-library/blob/master/docs/Value-Objects.md#template).

### Add 1 attribute

Call `addAttribute()` method of component:

```php
$link = new Link('Test', '/');
$link->addAttribute('id', 'test'); // id="test"
```

### Add more than 1 attributes

Call `addAttributes()` method of component:

```php
$link = new Link('Test', '/');
$link->addAttributes([
    'id'                            => 'test',
    'data-show'                     => 'true',
    Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
]); // id="test" data-start="true" class="blue-box"
```

### Use templates with `%attributes%` placeholder

##### Example

```php
// Create links
$homeLink = new Link('Home', '/');
$contactLink = new Link('Contact', '/contact');

// Create links\' containers
$homeLinkContainer = new LinkContainer($homeLink);
$contactLinkContainer = new LinkContainer($contactLink);

// Create menu
$menu = new Menu([
    $homeLinkContainer,
    $contactLinkContainer,
]);

// Add all attributes
$homeLink->addAttribute('id', 'home');
$contactLink->addAttribute('id', 'contact');

$homeLinkContainer->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'first-link');
$contactLinkContainer->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'second-link');

$menu->addAttributes([
    'id'                            => 'left-navigation',
    'data-show'                     => 'true',
    Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
]);

// Prepare templates (with `%attributes%` placeholders)
$templates = new Templates([
    Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
    LinkContainer::class => new Template('<div%attributes%>%link%</div>'),
    Menu::class => new Template('<div%attributes%>%linksContainers%</div>'),
]);

// Render the menu
echo $menu->render($templates);
```

##### Result

```html
<div id="left-navigation" data-show="true" class="blue-box">
    <div class="first-link">
        <a href="/" id="home">Home</a>
    </div>
    <div class="second-link">
        <a href="/contact" id="contact">Contact</a>
    </div>
</div>
```

### Attributes while using `create()` static methods

The `create()` static methods are available in classes:

1. `Meritoo\Menu\LinkContainer`
2. `Meritoo\Menu\Menu`

##### Example of link\'s container creation

Link "Home" with "/" url and attributes (of link and the link\'s container):

```php
$linkAttributes = ['id' => 'home'];
$linkContainerAttributes = [Attributes::ATTRIBUTE_CSS_CLASS => 'first-link'];

$linkContainer = LinkContainer::create('Home', '/', $linkAttributes, $linkContainerAttributes);
```

##### Example of menu creation

```
$links = [
    [
        // Name of link
        'Home',

        // Url of link
        '/',

        // Attributes of link
        [
            'id' => 'home',
        ],

        // Attributes of link's container
        [
            Attributes::ATTRIBUTE_CSS_CLASS => 'first-link',
        ],
    ],
    [
        'Contact',
        '/contact',
        [
            'id' => 'contact'
        ],
        [
            Attributes::ATTRIBUTE_CSS_CLASS => 'second-link'
        ],
    ],
];

$menuAttributes = [
    'id'                            => 'left-navigation',
    'data-show'                     => 'true',
    Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
];

$menu = Menu::create($links, $menuAttributes);

// Prepare templates (with `%attributes%` placeholders)
$templates = new Templates([
    Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
    LinkContainer::class => new Template('<div%attributes%>%link%</div>'),
    Menu::class => new Template('<div%attributes%>%linksContainers%</div>'),
]);

// Render the menu
echo $menu->render($templates);
```

##### Result

```html
<div id="left-navigation" data-show="true" class="blue-box">
    <div class="first-link">
        <a href="/" id="home">Home</a>
    </div>
    <div class="second-link">
        <a href="/contact" id="contact">Contact</a>
    </div>
</div>
```

# More

1. [How it works?](How-it-works.md)
2. [How to create?](How-to-create.md)
3. [**HTML attributes of each component**](Html-attributes-of-each-component.md)

[&lsaquo; Back to `Readme`](../README.md)
