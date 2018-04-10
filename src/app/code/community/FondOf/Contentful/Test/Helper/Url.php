<?php

class FondOf_Contentful_Test_Helper_Url extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Helper_Url
     */
    protected $_helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        @session_start();
        $this->_helper = Mage::helper('fondof_contentful/url');
    }

    /**
     * @test
     */
    public function testPrepareWithUrlPath()
    {
        $urlPath = '/imprint/';
        $url = $this->_helper->prepare($urlPath);
        $this->assertEquals(Mage::getUrl('imprint'), $url);
    }

    /**
     * @test
     */
    public function testPrepareWithExternalUrl()
    {
        $externalUrl = 'https://www.google.de/';
        $url = $this->_helper->prepare($externalUrl);
        $this->assertEquals($externalUrl, $url);
    }
}