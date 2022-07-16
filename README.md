# PrestaShop Module Skeleton


This is a basic module skeleton for Prestashop; it is a collection of boilerplate code that
should make easier to develop a modern PrestaShop module. It uses Symfony services and some custom
classes developed in order to speed-up common tasks (such as creating SQL queries for object-model tables creation
)

It includes folders, empty ``index.php``s and many utility classes. 
It also includes a ``composer.phar`` to install the required dependencies.
It also comes with some boilerplate code useful to create symfony controllers in your application.

Test folder for this project contains tests for general skeleton functionalities. Test folder inside the moduleskeleton folder is for tests of the 
specific final module we provide the skeleton for.

## Project structure
The ``moduleskeleton`` folder contains the actual code. This folder is to be renamed according to your module specifications. 
The same is true for ``moduleskeleton.php`` and the ``ModuleSkeleton`` class it contains.
For more information on PrestaShop folder structure, [see the official documentation](https://devdocs.prestashop.com/1.7/modules/creation/module-file-structure/)

The ``examples`` folder contains some practical file examples mostly inspired from real projects, to be used as bases to copy when creating a new 
module.



Todo:
- add examples
- add unit tests

