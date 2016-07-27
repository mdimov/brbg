<?php
namespace Barbaron\FeaturedProducts\Block\ProductList;

/**
 * Interceptor class for @see \Barbaron\FeaturedProducts\Block\ProductList
 */
class Interceptor extends \Barbaron\FeaturedProducts\Block\ProductList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $collection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
