<?php

class FondOf_Contentful_Test_Helper_Callback_Authorization extends EcomDev_PHPUnit_Test_Case {
    /**
     * @var FondOf_Contentful_Helper_Callback_Authorization
     */
    protected $_helper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_helper = Mage::helper('fondof_contentful/callback_authorization');
        $this->setCurrentStore('default');
    }

    /**
     * @test
     * @loadFixture testGetUsername
     */
    public function testGetUsername()
    {
        $username = $this->_helper->getUsername();
        $this->assertEquals('Username', $username);
    }

    /**
     * @test
     * @loadFixture testGetPassword
     */
    public function testGetPassword()
    {
        $password = $this->_helper->getPassword();
        $this->assertEquals('Password', $password);
    }

    /**
     * @test
     * @loadFixture testValidateAuthorizationHeader
     */
    public function testValidateAuthorizationHeader()
    {
        $authHeader = 'Basic ' . base64_encode('Username:Password');
        $this->assertTrue($this->_helper->validateAuthorizationHeader($authHeader));

        $authHeader = 'Basic ' . base64_encode('Username:password');
        $this->assertFalse($this->_helper->validateAuthorizationHeader($authHeader));
    }
}