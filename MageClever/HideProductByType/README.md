# Mage2 Module MageClever HideProductByType

    ``mageclever/module-hideproductbytype``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Hide product at front-end page by product type

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/MageClever`
 - Enable the module by running `php bin/magento module:enable MageClever_HideProductByType`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require mageclever/module-hideproductbytype`
 - enable the module by running `php bin/magento module:enable MageClever_HideProductByType`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Is enabled (hide_product_by_type/general/is_enabled)

 - Hide Product with type (hide_product_by_type/detail_config/product_type_id)

 - hide_in_page (hide_product_by_type/detail_config/hide_in_page)


## Specifications

 - Helper
	- MageClever\HideProductByType\Helper\Data

 - Plugin
	- beforeQuery - Magento\Elasticsearch7\Model\Client\Elasticsearch > MageClever\HideProductByType\Plugin\Magento\Elasticsearch7\Model\Client\Elasticsearch

 - Plugin
	- afterMap - Amasty\ElasticSearch\Model\Indexer\Data\Product\ProductDataMapper > MageClever\HideProductByType\Plugin\Amasty\ElasticSearch\Model\Indexer\Data\Product\ProductDataMapper

 - Plugin
	- beforeSearch - Amasty\ElasticSearch\Model\Client\Elasticsearch > MageClever\HideProductByType\Plugin\Amasty\ElasticSearch\Model\Client\Elasticsearch


## Function

 - Hide specific product type in specific page in admin:

[Admin] >> [Stores] >> [Configuration] >> [Mage Clever] >> [Hide product by type]

    - Product type:
      + Simple
      + Virtual
      + Bundle
      + Downloadable
      + Configurable
      + Grouped
    - Hide in page:
      + Search result page
      + Product listing page
      + Product detail page

## Requirements
 - Module work well with: 
   
   + Magento 2.4
   + Elastic search 7.x OR Amasty elastic search 1.13.1
   
 - For other elastic search version maybe need to test and customize to fit

## Contact to discuss and get support
 - Email: thainv.developer@gmail.com
 - Skype: thainv.developer


 


