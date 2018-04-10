<?php

class FondOf_Contentful_Test_Helper_Page extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Helper_Page
     */
    protected $_helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_helper = Mage::helper('fondof_contentful/page');
        $this->setCurrentStore('default');
    }

    /**
     * @test
     * @loadFixture testGetEntryIdForHome
     */
    public function testGetEntryIdForHome()
    {
        $entryIdForHome = $this->_helper->getEntryIdForHome();
        $this->assertEquals('1234567890home', $entryIdForHome);
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