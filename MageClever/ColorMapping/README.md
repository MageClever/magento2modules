# Mage2 Module MageClever ColorMapping

    ``mageclever/module-colormapping``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Mapping multi child colors to parent color

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/MageClever`
 - Enable the module by running `php bin/magento module:enable MageClever_ColorMapping`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require mageclever/module-colormapping`
 - enable the module by running `php bin/magento module:enable MageClever_ColorMapping`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - is_enabled (color_mapping/general/is_enabled)

 - parent_color (color_mapping/declare/parent_color)

 - child_color (color_mapping/declare/child_color)


## Specifications

 - Helper
	- MageClever\ColorMapping\Helper\Data

 - Observer
	- catalog_product_save_before > MageClever\ColorMapping\Observer\Backend\Catalog\ProductSaveBefore


## Attributes

 - Product - Parent color (parent_color)

