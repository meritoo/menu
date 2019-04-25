# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# How it works?

### Consists of 3 components

1. Link
2. Link\'s container
3. Menu

### Hierarchy of these components

- Menu
    - Link\'s container 1
        - Link 1
        - Link 2
    - Link\'s container 2
        - Link 3
        - Link 4
        - Link 5

### Link

1st and the lowermost component. Represents link that user clicks in menu. May contain name, url and attributes.
Class: `Meritoo\Menu\Link`

### Link\'s container

2nd and the midmost component. Container for a link (explained above). May contain link and attributes.
Class: `Meritoo\Menu\LinkContainer`

### Menu

3rd and the uppermost component. Container for links\' containers (explained above). May contain links\' containers and attributes.
Class: `Meritoo\Menu\Menu`

# More

1. [**How it works?**](How-it-works.md)
2. [How to create?](How-to-create.md)
3. [HTML attributes of each component](Html-attributes-of-each-component.md)

[&lsaquo; Back to `Readme`](../README.md)
