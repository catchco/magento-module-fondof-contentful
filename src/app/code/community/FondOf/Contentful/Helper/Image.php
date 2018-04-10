<?php

/**
 * Image helper
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Helper_Image extends Mage_Core_Helper_Abstract
{
    const XML_PATH_IMAGE_MAX_WIDTH = 'fondof_contentful/image/max_width';
    const XML_PATH_IMAGE_JPEG_QUALITY = 'fondof_contentful/image/jpeg_quality';

    /**
     * @var null|\Contentful\Delivery\Asset
     */
    protected $_asset = null;

    /**
     * @var null|\Contentful\Delivery\ImageFile
     */
    protected $_file = null;

    /**
     * @var array
     */
    protected $_urlParameters = array();

    /**
     * @var string
     */
    protected $_locale = '';

    /**
     * @var string
     */
    protected $_defaultLocale = '';

    /**
     * FondOf_Contentful_Helper_Image constructor.
     */
    public function __construct()
    {
        $defaultHelper = Mage::helper('fondof_contentful');

        $this->_defaultLocale = $defaultHelper->getDefaultLocale();
        $this->_locale = $defaultHelper->getLocale();
    }


    /**
     * Init
     *
     * @param \Contentful\Delivery\Asset $asset
     * @return $this
     */
    public function init(\Contentful\Delivery\Asset $asset)
    {
        $this->_asset = $asset;
        $this->_file = $asset->getFile($this->_locale);

        if (($this->_file === null || !($this->_file instanceof Contentful\File\ImageFile))
            && $this->_defaultLocale != $this->_locale
        ) {
            $this->_file = $asset->getFile($this->_defaultLocale);
        }

        if ($this->_file === null || !($this->_file instanceof Contentful\File\ImageFile)) {
            Mage::throwException('Asset does not contain a valid image file!');
        }

        $this->_urlParameters = array();

        return $this;
    }

    /**
     * Resize image
     * $width *or* $height can be null - in this case, lacking dimension will be calculated.
     *
     * @param int $width
     * @param int $height
     *
     * @return FondOf_Contentful_Helper_Image
     */
    public function resize($width, $height = null)
    {
        if ($width !== null) {
            $this->_urlParameters['w'] = 'w=' . $width;
        }

        if ($height !== null) {
            $this->_urlParameters['h'] = 'h=' . $height;
        }

        return $this;
    }

    /**
     * Crop image
     *
     * @param $width
     * @param null $height
     * @return $this
     */
    public function crop($width, $height = null)
    {
        if ($this->_file === null || !($this->_file instanceof \Contentful\File\ImageFile)) {
            return $this;
        }

        if ($width === null && $height === null) {
            return $this;
        }

        if ($width === null) {
            $this->_urlParameters['w'] = 'w=' . $this->_file->getWidth();
        } else {
            $this->_urlParameters['w'] = 'w=' . $width;
        }

        if ($height === null) {
            $this->_urlParameters['h'] = 'h=' . $this->_file->getHeight();
        } else {
            $this->_urlParameters['h'] = 'h=' . $height;
        }

        $this->_urlParameters['fit'] = 'fit=crop';

        return $this;
    }

    /**
     * Set corner radius
     *
     * @param $radius
     * @return $this
     */
    public function setCornerRadius($radius)
    {
        $this->_urlParameters['r'] = 'r=' . $radius;

        return $this;
    }

    /**
     * Set background color
     *
     * @param string $color
     *
     * @return $this
     */
    public function setBackgroundColor($color)
    {
        $this->_urlParameters['bg'] = 'bg=rgb:' . $color;
        $this->_urlParameters['fit'] = 'fit=pad';

        return $this;
    }

    /**
     * Retrieve max width
     *
     * @param null $store
     * @return string
     */
    public function getMaxWidth($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_IMAGE_MAX_WIDTH, $store);
    }

    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {
        if (!$this->_file || !($this->_file instanceof Contentful\File\ImageFile)) {
            return '';
        }

        $src = $this->_file->getUrl();

        if (preg_match('/^.*\.(jpg|jpeg)$/i', $src) === 1) {
            $this->_urlParameters['fm'] = 'fm=jpg';
            $this->_urlParameters['q'] = 'q=' . Mage::getStoreConfig(self::XML_PATH_IMAGE_JPEG_QUALITY);
        }

        if (!count($this->_urlParameters)) {
            return $src;
        }

        return $src . '?' . implode('&', $this->_urlParameters);
    }
}
