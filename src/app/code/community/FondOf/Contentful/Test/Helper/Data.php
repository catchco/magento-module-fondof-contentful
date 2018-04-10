<?php

class FondOf_Contentful_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Helper_Data
     */
    protected $_helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_helper = Mage::helper('fondof_contentful');
        $this->setCurrentStore('default');
    }

    /**
     * @test
     * @loadFixture testGetSpaceId
     */
    public function testGetSpaceId()
    {
        $spaceId = $this->_helper->getSpaceId();
        $this->assertEquals('spaceid1234567890', $spaceId);
    }

    /**
     * @test
     * @loadFixture testGetAccessToken
     */
    public function testGetAccessToken()
    {
        $accessToken = $this->_helper->getAccessToken();
        $this->assertEquals('accesstoken1234567890', $accessToken);
    }

    /**
     * @test
     * @loadFixture testGetLocale
     */
    public function testGetLocale()
    {
        $locale = $this->_helper->getLocale();
        $this->assertEquals('de', $locale);
    }

    /**
     * @test
     * @loadFixture testGetDefaultLocale
     */
    public function testGetDefaultLocale()
    {
        $locale = $this->_helper->getDefaultLocale();
        $this->assertEquals('en', $locale);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->setCurrentStore('admin');
    }
}
