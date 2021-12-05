<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Api\Data;

interface ColorRelationshipInterface
{

    const PARENT_COLOR_OPTION_ID = 'parent_color_option_id';
    const CHILD_COLOR_OPTION_TEXT = 'child_color_option_text';
    const PARENT_COLOR_OPTION_TEXT = 'parent_color_option_text';
    const CHILD_COLOR_OPTION_ID = 'child_color_option_id';
    const COLORRELATIONSHIP_ID = 'colorrelationship_id';

    /**
     * Get colorrelationship_id
     * @return string|null
     */
    public function getColorrelationshipId();

    /**
     * Set colorrelationship_id
     * @param string $colorrelationshipId
     * @return \Api\Data\ColorRelationshipInterface
     */
    public function setColorrelationshipId($colorrelationshipId);

    /**
     * Get parent_color_option_id
     * @return string|null
     */
    public function getParentColorOptionId();

    /**
     * Set parent_color_option_id
     * @param string $parentColorOptionId
     * @return \Api\Data\ColorRelationshipInterface
     */
    public function setParentColorOptionId($parentColorOptionId);

    /**
     * Get parent_color_option_text
     * @return string|null
     */
    public function getParentColorOptionText();

    /**
     * Set parent_color_option_text
     * @param string $parentColorOptionText
     * @return \Api\Data\ColorRelationshipInterface
     */
    public function setParentColorOptionText($parentColorOptionText);

    /**
     * Get child_color_option_id
     * @return string|null
     */
    public function getChildColorOptionId();

    /**
     * Set child_color_option_id
     * @param string $childColorOptionId
     * @return \Api\Data\ColorRelationshipInterface
     */
    public function setChildColorOptionId($childColorOptionId);

    /**
     * Get child_color_option_text
     * @return string|null
     */
    public function getChildColorOptionText();

    /**
     * Set child_color_option_text
     * @param string $childColorOptionText
     * @return \Api\Data\ColorRelationshipInterface
     */
    public function setChildColorOptionText($childColorOptionText);
}

