<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Bundle\Model\ResourceModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Bundle Selection Resource Model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Selection extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * Selection constructor.
     *
     * @param Context $context
     * @param MetadataPool $metadataPool
     * @param null|string $connectionName
     */
    public function __construct(Context $context, MetadataPool $metadataPool, $connectionName = null)
    {
        parent::__construct(
            $context,
            $connectionName
        );
        $this->metadataPool = $metadataPool;
    }

    /**
     * Define main table and id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('catalog_product_bundle_selection', 'selection_id');
    }

    /**
     * Retrieve Required children ids
     * Return grouped array, ex array(
     *   group => array(ids)
     * )
     *
     * @param int $parentId
     * @param bool $required
     * @return array
     */
    public function getChildrenIds($parentId, $required = true)
    {
        $childrenIds = [];
        $notRequired = [];
        $connection = $this->getConnection();
        $linkField = $this->metadataPool->getMetadata(ProductInterface::class)->getLinkField();
        $select = $connection->select()->from(
            ['tbl_selection' => $this->getMainTable()],
            ['product_id', 'parent_product_id', 'option_id']
        )->join(
            ['e' => $this->getTable('catalog_product_entity')],
            'e.entity_id = tbl_selection.product_id AND e.required_options=0',
            []
        )->join(
            ['parent' => $this->getTable('catalog_product_entity')],
            'tbl_selection.parent_product_id = parent.' . $linkField
        )->join(
            ['tbl_option' => $this->getTable('catalog_product_bundle_option')],
            'tbl_option.option_id = tbl_selection.option_id',
            ['required']
        )->where(
            'parent.entity_id = :parent_id'
        );
        foreach ($connection->fetchAll($select, ['parent_id' => $parentId]) as $row) {
            if ($row['required']) {
                $childrenIds[$row['option_id']][$row['product_id']] = $row['product_id'];
            } else {
                $notRequired[$row['option_id']][$row['product_id']] = $row['product_id'];
            }
        }

        if (!$required) {
            $childrenIds = array_merge($childrenIds, $notRequired);
        } else {
            if (!$childrenIds) {
                foreach ($notRequired as $groupedChildrenIds) {
                    foreach ($groupedChildrenIds as $childId) {
                        $childrenIds[0][$childId] = $childId;
                    }
                }
            }
            if (!$childrenIds) {
                $childrenIds = [[]];
            }
        }

        return $childrenIds;
    }

    /**
     * Retrieve array of related bundle product ids by selection product id(s)
     *
     * @param int|array $childId
     * @return array
     */
    public function getParentIdsByChild($childId)
    {
        $connection = $this->getConnection();
        $metadata = $this->metadataPool->getMetadata(ProductInterface::class);
        $select = $connection->select()->distinct(
            true
        )->from(
            $this->getMainTable(),
            ''
        )->join(
            ['e' => $this->metadataPool->getMetadata(ProductInterface::class)->getEntityTable()],
            'e.' . $metadata->getLinkField() . ' = ' .  $this->getMainTable() . '.parent_product_id',
            ['e.entity_id as parent_product_id']
        )->where(
            'e.entity_id IN(?)',
            $childId
        );

        return $connection->fetchCol($select);
    }

    /**
     * Save bundle item price per website
     *
     * @param \Magento\Bundle\Model\Selection $item
     * @return void
     */
    public function saveSelectionPrice($item)
    {
        $connection = $this->getConnection();
        if ($item->getDefaultPriceScope()) {
            $connection->delete(
                $this->getTable('catalog_product_bundle_selection_price'),
                ['selection_id = ?' => $item->getSelectionId(), 'website_id = ?' => $item->getWebsiteId()]
            );
        } else {
            $values = [
                'selection_id' => $item->getSelectionId(),
                'website_id' => $item->getWebsiteId(),
                'selection_price_type' => $item->getSelectionPriceType(),
                'selection_price_value' => $item->getSelectionPriceValue(),
            ];
            $connection->insertOnDuplicate(
                $this->getTable('catalog_product_bundle_selection_price'),
                $values,
                ['selection_price_type', 'selection_price_value']
            );
        }
    }
}
