<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Api\Data;

interface ColorRelationshipSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ColorRelationship list.
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface[]
     */
    public function getItems();

    /**
     * Set parent_color_option_id list.
     * @param \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

