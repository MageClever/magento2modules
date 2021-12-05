<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ColorRelationshipRepositoryInterface
{

    /**
     * Save ColorRelationship
     * @param \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
    );

    /**
     * Retrieve ColorRelationship
     * @param string $colorrelationshipId
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($colorrelationshipId);

    /**
     * Retrieve ColorRelationship matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageClever\ColorMapping\Api\Data\ColorRelationshipSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ColorRelationship
     * @param \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \MageClever\ColorMapping\Api\Data\ColorRelationshipInterface $colorRelationship
    );

    /**
     * Delete ColorRelationship by ID
     * @param string $colorrelationshipId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($colorrelationshipId);
}

