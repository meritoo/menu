# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# 0.0.5

1. Minor refactoring
2. Menu, Link & LinkContainer > add getters (to get content)
3. Menu::getAllMenuParts() method > returns all menu parts (menu, containers with links and links)
4. Docker > use images (instead of Dockerfiles)
5. composer > squizlabs/php_codesniffer package > use ^3.4 (instead of ^2.9)
6. Update Kernel used by tests

# 0.0.4

1. HTML attributes > fix "Declaration of Meritoo\Menu\Html\Attributes::addMultiple($attributes, $useIndexes = false):
void must be compatible (...)" fatal error

# 0.0.3

1. Readme > review and fix "unknown" badges

# 0.0.2

1. Rename `Item` class to `LinkContainer`
2. Visitor for menu part. Allows to run functionality outside the menu part, e.g. to make modifications.
3. Move and rename class: `BaseVisitor` -> `Visitor` (`Meritoo\Menu\Base` -> `Meritoo\Menu\Visitor`)
4. Move and rename class: `BaseMenuPart` -> `MenuPart` (`Meritoo\Menu\Base` -> `Meritoo\Menu`)
5. Visitor for menu part > use Abstract Factory to create visitors for different menu parts
6. Meritoo\Menu\MenuPart::getAttributes() method > make private (not protected), because it is internal method only

# 0.0.1

1. Let's start :)
