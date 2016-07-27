<?php

namespace Barbaron\FeaturedProducts\Block;

/**
 * Get featured products collection
 *
 * @category   Barbaron
 * @package    Barbaron
 * @author     Barbaron GmbH [mt]
 */
class ProductList extends \Magento\Catalog\Block\Product\AbstractProduct {

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_collection;


    /**
     * System configuration values
     *
     * @var array
     */
    protected $_config;


    /**
     * Initialize
     *
     * @param Magento\Catalog\Block\Product\Context $context
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        array $data = []
    ) {
        $this->_collection = clone $collection;
        $this->_config = $context->getScopeConfig()->getValue('featured_products/settings');

        parent::__construct($context, $data);
    }


    /**
     * Get product collection
     */
    public function getProducts() {
        $limit = $this->getProductLimit();

        $collection = $this->_collection
                           ->joinTable($this->_collection->getResource()->getTable('catalog_product_relation'), 'child_id=entity_id', array(
                                'pid' => 'parent_id'
                           ), null, 'left')
                           ->addMinimalPrice()
                           ->addFinalPrice()
                           ->addTaxPercents()
                           ->addAttributeToSelect('name')
                           ->addAttributeToSelect('featured_media')
                           ->addAttributeToFilter('is_saleable', 1, 'left')
                           ->addAttributeToFilter(array(
                                array(
                                    'attribute' => 'pid',
                                    'null' => null
                                )
                            ))
                           ->addAttributeToFilter('barbaron_featured_product', 1, 'left');

        $collection->getSelect()
                   ->limit($limit);

        return $collection;
    }


    /**
     * Get image helper
     */
    public function getImageHelper() {
        return $this->_imageHelper;
    }


    /**
     * Get module configuration
     */
    public function getConfig() {
        return $this->_config;
    }

    /**
     * Get the configured limit of products
     * @return int
     */
    public function getProductLimit() {
        return $this->_config["limit"];
    }
    
    /**
     * Get the configured width of images
     * @return int
     */
    public function getImageWidth() {
        return $this->_config["image_width"];
    }
    
}