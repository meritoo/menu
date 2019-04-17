# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# How to create?

### Simple instruction

1. Create link
    - instance of `Meritoo\Menu\Link` class
2. Create item
    - instance of `Meritoo\Menu\Item` class
    - container for link
    - pass link as the item's constructor argument
3. Create menu
    - instance of `Meritoo\Menu\Menu` class
    - container for items
    - pass item as the menu's constructor argument
4. Call the `render()` method on menu's instance

### Create using constructor

##### Example

1. Two links
2. CSS classes hardcoded in templates

```php
$menu = new Menu([
    new Item(new Link('Home', '/')),
    new Item(new Link('Contact', '/contact')),
]);

$templates = new Templates([
    Link::class => new Template('<a href="%url%">%name%</a>'),
    Item::class => new Template('<div class="item">%link%</div>'),
    Menu::class => new Template('<div class="container">%items%</div>'),
]);

echo $menu->render($templates);
```

##### Result

```html
<div class="container">
    <div class="item">
        <a href="/">Home</a>
    </div>
    <div class="item">
        <a href="/contact">Contact</a>
    </div>
</div>
```

### Create using `create()` static method

The `create()` static methods are available in classes:

1. `Meritoo\Menu\Item`
2. `Meritoo\Menu\Menu`

##### Example of item creation

Link "Home" with "/" url:

```php
$item = Item::create('Home', '/');
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
    Item::class => new Template('<div class="item">%link%</div>'),
    Menu::class => new Template('<div class="container">%items%</div>'),
]);

echo $menu->render($templates);
```

##### Result of menu creation

```html
<div class="container">
    <div class="item">
        <a href="/">Home</a>
        </div>
    <div class="item">
        <a href="/contact">Contact</a>
    </div>
</div>
```

# More

1. [How it works?](How-it-works.md)
2. [**How to create?**](How-to-create.md)
3. [HTML attributes of each component](Html-attributes-of-each-component.md)

[&lsaquo; Back to `Readme`](../README.md)
