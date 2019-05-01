# Meritoo Menu

Build navigation easily, without any efforts. Library that provides tools to build a menu.

# 0.0.2

1. Rename `Item` class to `LinkContainer`
2. Visitor for menu part. Allows to run functionality outside the menu part, e.g. to make modifications.
3. Move and rename class: `BaseVisitor` -> `Visitor` (`Meritoo\Menu\Base` -> `Meritoo\Menu\Visitor`)
4. Move and rename class: `BaseMenuPart` -> `MenuPart` (`Meritoo\Menu\Base` -> `Meritoo\Menu`)
5. Visitor for menu part > use Abstract Factory to create visitors for different menu parts
6. Meritoo\Menu\MenuPart::getAttributes() method > make private (not protected), because it is internal method only

# 0.0.1

1. Let's start :)
