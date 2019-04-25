# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# How to create?

### Simple instruction

1. Create link
    - instance of `Meritoo\Menu\Link` class
2. Create link\'s container
    - instance of `Meritoo\Menu\LinkContainer` class
    - container for link
    - pass link as the container's constructor argument
3. Create menu
    - instance of `Meritoo\Menu\Menu` class
    - container for link\'s containers
    - pass link\'s container as the menu's constructor argument
4. Call the `render()` method on menu's instance

### Create using constructor

##### Example

1. Two links
2. CSS classes hardcoded in templates

```php
$menu = new Menu([
    new LinkContainer(new Link('Home', '/')),
    new LinkContainer(new Link('Contact', '/contact')),
]);

$templates = new Templates([
    Link::class => new Template('<a href="%url%">%name%</a>'),
    LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
    Menu::class => new Template('<div class="container">%linksContainers%</div>'),
]);

echo $menu->render($templates);
```

##### Result

```html
<div class="container">
    <div class="wrapper">
        <a href="/">Home</a>
    </div>
    <div class="wrapper">
        <a href="/contact">Contact</a>
    </div>
</div>
```

### Create using `create()` static method

The `create()` static methods are available in classes:

1. `Meritoo\Menu\LinkContainer`
2. `Meritoo\Menu\Menu`

##### Example of link\'s container creation

Link "Home" with "/" url:

```php
$linkContainer = LinkContainer::create('Home', '/');
```

##### Example of menu creation

1. Two links
2. CSS classes hardcoded in templates

```php
$menu = Menu::create([
    ['Home', '/'],
    ['Contact', '/contact'],
]);

$templates = new Templates([
    Link::class => new Template('<a href="%url%">%name%</a>'),
    LinkContainer::class => new Template('<div class="wrapper">%link%</div>'),
    Menu::class => new Template('<div class="container">%linksContainers%</div>'),
]);

echo $menu->render($templates);
```

##### Result of menu creation

```html
<div class="container">
    <div class="wrapper">
        <a href="/">Home</a>
        </div>
    <div class="wrapper">
        <a href="/contact">Contact</a>
    </div>
</div>
```

# More

1. [How it works?](How-it-works.md)
2. [**How to create?**](How-to-create.md)
3. [HTML attributes of each component](Html-attributes-of-each-component.md)

[&lsaquo; Back to `Readme`](../README.md)
