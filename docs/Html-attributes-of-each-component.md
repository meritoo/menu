# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# HTML attributes of each component

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

// Create items
$homeItem = new Item($homeLink);
$contactItem = new Item($contactLink);

// Create menu
$menu = new Menu([
    $homeItem,
    $contactItem,
]);

// Add all attributes
$homeLink->addAttribute('id', 'home');
$contactLink->addAttribute('id', 'contact');

$homeItem->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'first-item');
$contactItem->addAttribute(Attributes::ATTRIBUTE_CSS_CLASS, 'second-item');

$menu->addAttributes([
    'id'                            => 'left-navigation',
    'data-show'                     => 'true',
    Attributes::ATTRIBUTE_CSS_CLASS => 'blue-box',
]);

// Prepare templates (with `%attributes%` placeholders)
$templates = new Templates([
    Link::class => new Template('<a href="%url%"%attributes%>%name%</a>'),
    Item::class => new Template('<div%attributes%>%link%</div>'),
    Menu::class => new Template('<div%attributes%>%items%</div>'),
]);

// Render the menu
echo $menu->render($templates);
```

##### Result

```html
<div id="left-navigation" data-show="true" class="blue-box">
    <div class="first-item">
        <a href="/" id="home">Home</a>
    </div>
    <div class="second-item">
        <a href="/contact" id="contact">Contact</a>
    </div>
</div>
```

### Attributes while using `create()` static methods

The `create()` static methods are available in classes:

1. `Meritoo\Menu\Item`
2. `Meritoo\Menu\Menu`

##### Example of item creation

Link "Home" with "/" url and attributes (of link and the item):

```php
$linkAttributes = ['id' => 'home'];
$itemAttributes = [Attributes::ATTRIBUTE_CSS_CLASS => 'first-item'];

$item = Item::create('Home', '/', $linkAttributes, $itemAttributes);
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

        // Attributes of item
        [
            Attributes::ATTRIBUTE_CSS_CLASS => 'first-item',
        ],
    ],
    [
        'Contact',
        '/contact',
        [
            'id' => 'contact'
        ],
        [
            Attributes::ATTRIBUTE_CSS_CLASS => 'second-item'
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
    Item::class => new Template('<div%attributes%>%link%</div>'),
    Menu::class => new Template('<div%attributes%>%items%</div>'),
]);

// Render the menu
echo $menu->render($templates);
```

##### Result

```html
<div id="left-navigation" data-show="true" class="blue-box">
    <div class="first-item">
        <a href="/" id="home">Home</a>
    </div>
    <div class="second-item">
        <a href="/contact" id="contact">Contact</a>
    </div>
</div>
```

# More

1. [How it works?](How-it-works.md)
2. [How to create?](How-to-create.md)
3. [**HTML attributes of each component**](Html-attributes-of-each-component.md)

[&lsaquo; Back to `Readme`](../README.md)
