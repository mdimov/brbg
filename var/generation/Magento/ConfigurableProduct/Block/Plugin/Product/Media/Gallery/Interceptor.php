<?php
namespace Magento\ConfigurableProduct\Block\Plugin\Product\Media\Gallery;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Block\Plugin\Product\Media\Gallery
 */
class Interceptor extends \Magento\ConfigurableProduct\Block\Plugin\Product\Media\Gallery implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, \Magento\Catalog\Model\Product\Gallery\ReadHandler $productGalleryReadHandler, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Framework\Json\DecoderInterface $jsonDecoder, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $arrayUtils, $productGalleryReadHandler, $jsonEncoder, $jsonDecoder, $data);
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
