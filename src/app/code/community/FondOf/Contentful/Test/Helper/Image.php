<?php

class FondOf_Contentful_Test_Helper_Image extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Helper_Image
     */
    protected $_helper;

    /**
     * @var FondOf_Contentful_Helper_Data|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_defaultHelperMock;

    /**
     * @var \Contentful\Delivery\Asset|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_asset;

    /**
     * @var \Contentful\File\ImageFile|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_file;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        Mage::unregister('_helper/fondof_contentful');

        $this->_asset = $this->getMockBuilder(\Contentful\Delivery\Asset::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_file = $this->getMockBuilder(\Contentful\File\ImageFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_defaultHelperMock = $this->getHelperMockBuilder('fondof_contentful')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_defaultHelperMock->expects($this->atLeastOnce())
            ->method('getLocale')
            ->willReturn('de');

        $this->_defaultHelperMock->expects($this->atLeastOnce())
            ->method('getDefaultLocale')
            ->willReturn('en');

        $this->replaceByMock('helper', 'fondof_contentful', $this->_defaultHelperMock);
        $this->setCurrentStore('default');

        $this->_helper = Mage::helper('fondof_contentful/image');
    }

    /**
     * @test
     * @loadFixture testGetMaxWidth
     */
    public function testGetMaxWidth()
    {
        $maxWidth = $this->_helper->getMaxWidth();
        $this->assertEquals(1000, $maxWidth);
    }

    /**
     * @test
     */
    public function testInit()
    {
        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->with('de')
            ->willReturn($this->_file);

        $this->assertEquals($this->_helper, $this->_helper->init($this->_asset));
    }

    /**
     * @test
     */
    public function testInitWithFallback()
    {
        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->withConsecutive(array('de'), array('en'))
            ->willReturnOnConsecutiveCalls(null, $this->_file);

        $this->assertEquals($this->_helper, $this->_helper->init($this->_asset));
    }

    /**
     * @test
     * @expectedException Mage_Core_Exception
     * @expectedExceptionMessage Asset does not contain a valid image file!
     */
    public function testInitWithInvalidAsset()
    {
        $this->_helper->init($this->_asset);
    }

    /**
     * @test
     */
    public function testToStringWithoutUrlParameters()
    {
        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn('//images.contentful.com/space_id/asset_id/xyz/example.png');

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);

        $this->assertEquals('//images.contentful.com/space_id/asset_id/xyz/example.png', $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testToStringWithInvalidAsset()
    {
        $this->assertEquals('', $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testResizeWithWidth()
    {
        $width = 100;
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->resize($width);

        $this->assertEquals($url . '?w=' . $width, $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testResizeWithWidthAndHeight()
    {
        $width = 100;
        $height = 200;
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->resize($width, $height);

        $this->assertEquals($url . '?w=' . $width . '&h=' . $height, $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testCropWithoutWidthAndHeight()
    {
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->crop(null, null);

        $this->assertEquals($url, $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testCropWithWidth()
    {
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';
        $width = 100;

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_file->expects($this->atLeastOnce())
            ->method('getHeight')
            ->willReturn(500);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->crop($width, null);

        $this->assertEquals($url. '?w=' . $width . '&h=500&fit=crop', $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testCropWithHeight()
    {
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';
        $height = 500;

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_file->expects($this->atLeastOnce())
            ->method('getWidth')
            ->willReturn(100);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->crop(null, $height);

        $this->assertEquals($url. '?w=100&h=' . $height . '&fit=crop', $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testCropWithoutInit()
    {
        $height = 500;
        $this->_helper->crop(null, $height);
        $this->assertEquals('', $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testSetCornerRadius()
    {
        $cornerRadius = 2;
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->setCornerRadius($cornerRadius);

        $this->assertEquals($url . '?r=' . $cornerRadius, $this->_helper->__toString());
    }

    /**
     * @test
     */
    public function testSetBackgroundColor()
    {
        $color = '9090ff';
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.png';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);
        $this->_helper->setBackgroundColor($color);

        $this->assertEquals($url . '?bg=rgb:' . $color . '&fit=pad', $this->_helper->__toString());
    }

    /**
     * @test
     * @loadFixture testReduceJPEGQuality
     */
    public function testReduceJPEGQuality()
    {
        $url = '//images.contentful.com/space_id/asset_id/xyz/example.jpg';

        $this->_file->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturn($url);

        $this->_asset->expects($this->atLeastOnce())
            ->method('getFile')
            ->willReturn($this->_file);

        $this->_helper->init($this->_asset);

        $this->assertEquals($url . '?fm=jpg&q=75', $this->_helper->__toString());
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        Mage::unregister('_helper/fondof_contentful/image');
        $this->setCurrentStore('admin');
    }
}
