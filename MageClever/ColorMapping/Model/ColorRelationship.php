<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Model;

use MageClever\ColorMapping\Api\Data\ColorRelationshipInterface;

class ColorRelationship extends \Magento\Framework\Model\AbstractModel implements ColorRelationshipInterface
{
    protected $_eventPrefix = 'color_relationship';

    protected $_eventObject = 'color_relationship';

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\MageClever\ColorMapping\Model\ResourceModel\ColorRelationship::class);
    }

    /**
     * Get colorrelationship_id
     * @return string|null
     */
    public function getColorrelationshipId()
    {
        return $this->_get(self::COLORRELATIONSHIP_ID);
    }

    /**
     * Set colorrelationship_id
     * @param string $colorrelationshipId
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     */
    public function setColorrelationshipId($colorrelationshipId)
    {
        return $this->setData(self::COLORRELATIONSHIP_ID, $colorrelationshipId);
    }

    /**
     * Get parent_color_option_id
     * @return string|null
     */
    public function getParentColorOptionId()
    {
        return $this->_get(self::PARENT_COLOR_OPTION_ID);
    }

    /**
     * Set parent_color_option_id
     * @param string $parentColorOptionId
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     */
    public function setParentColorOptionId($parentColorOptionId)
    {
        return $this->setData(self::PARENT_COLOR_OPTION_ID, $parentColorOptionId);
    }

    /**
     * Get parent_color_option_text
     * @return string|null
     */
    public function getParentColorOptionText()
    {
        return $this->_get(self::PARENT_COLOR_OPTION_TEXT);
    }

    /**
     * Set parent_color_option_text
     * @param string $parentColorOptionText
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     */
    public function setParentColorOptionText($parentColorOptionText)
    {
        return $this->setData(self::PARENT_COLOR_OPTION_TEXT, $parentColorOptionText);
    }

    /**
     * Get child_color_option_id
     * @return string|null
     */
    public function getChildColorOptionId()
    {
        return $this->_get(self::CHILD_COLOR_OPTION_ID);
    }

    /**
     * Set child_color_option_id
     * @param string $childColorOptionId
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     */
    public function setChildColorOptionId($childColorOptionId)
    {
        return $this->setData(self::CHILD_COLOR_OPTION_ID, $childColorOptionId);
    }

    /**
     * Get child_color_option_text
     * @return string|null
     */
    public function getChildColorOptionText()
    {
        return $this->_get(self::CHILD_COLOR_OPTION_TEXT);
    }

    /**
     * Set child_color_option_text
     * @param string $childColorOptionText
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     */
    public function setChildColorOptionText($childColorOptionText)
    {
        return $this->setData(self::CHILD_COLOR_OPTION_TEXT, $childColorOptionText);
    }
}

