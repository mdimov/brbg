<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Barbaron\FeaturedProducts\Block;

/**
 * HTML heading element block
 *
 * @method string getLabel()
 * @method string getHeadingSize()
 */
class Heading extends \Magento\Framework\View\Element\Template
{
    /**
     * @var array
     */
    protected $allowedAttributes = [
        'title',
        'id',
        'class',
        'style', // %coreattrs
        'lang',
        'dir', // %i18n
    ];

    /**
     * Prepare heading attributes as serialized and formatted string
     *
     * @return string
     */
    public function getHeadingAttributes()
    {
        $attributes = [];
        foreach ($this->allowedAttributes as $attribute) {
            $value = $this->getDataUsingMethod($attribute);
            if ($value !== null) {
                $attributes[$attribute] = $this->escapeHtml($value);
            }
        }

        if (!empty($attributes)) {
            return $this->serialize($attributes);
        }

        return '';
    }

    /**
     * Serialize attributes
     *
     * @param   array $attributes
     * @param   string $valueSeparator
     * @param   string $fieldSeparator
     * @param   string $quote
     * @return  string
     */
    public function serialize($attributes = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $data = [];
        foreach ($attributes as $key => $value) {
            $data[] = $key . $valueSeparator . $quote . $value . $quote;
        }

        return implode($fieldSeparator, $data);
    }

    public function getHeadingSizeDefault() {
        return 6 >= $this->getHeadingSize() && $this->getHeadingSize() >= 1 ? $this->getHeadingSize() : 1;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        return '<h' . $this->getHeadingSizeDefault() . ' ' . $this->getHeadingAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</h' . $this->getHeadingSizeDefault() . '>';
    }
}
