# Mage2 Module MageClever SearchCmsPage

    ``mageclever/module-searchcmspage``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Search CMS page with elastic search

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/MageClever`
 - Enable the module by running `php bin/magento module:enable MageClever_SearchCmsPage`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require mageclever/module-searchcmspage`
 - enable the module by running `php bin/magento module:enable MageClever_SearchCmsPage`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Is enabled (search_cms_page/general/is_enabled)


## Specifications

 - Helper
	- MageClever\SearchCmsPage\Helper\Data


## Attributes



