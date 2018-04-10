<?php

class FondOf_Contentful_Test_Helper_Markdown extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Helper_Markdown
     */
    protected $_helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_helper = Mage::helper('fondof_contentful/markdown');
    }

    /**
     * @test
     */
    public function testToHtml()
    {
        $markdown = '# Heading 1';
        $html = '<h1>Heading 1</h1>';

        $this->assertEquals($html, $this->_helper->toHtml($markdown));
    }
}