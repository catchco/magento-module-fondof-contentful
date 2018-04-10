<?php

class FondOf_Contentful_Test_Model_Callback_Handler_Chain extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Model_Callback_Handler_Chain
     */
    protected $_callbackHandlerChain;

    /**
     * @var FondOf_Contentful_Model_Callback_Handler_Cache|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_callbackHandlerCacheMock;

    /**
     * @var FondOf_Contentful_Model_Callback_Handler_Page|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_callbackHandlerPageMock;

    /**
     * @var Mage_Core_Controller_Request_Http|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_requestMock;

    /**
     * @var FondOf_Contentful_Model_Client|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_clientMock;

    /**
     * @var \Contentful\Delivery\DynamicEntry|PHPUnit_Framework_MockObject_MockObject
     */
    protected $_bodyMock;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_requestMock = $this->getMockBuilder('Mage_Core_Controller_Request_Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_bodyMock = $this->getMockBuilder(\Contentful\Delivery\DynamicEntry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_callbackHandlerCacheMock = $this->getModelMockBuilder('fondof_contentful/callback_handler_cache')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_callbackHandlerPageMock = $this->getModelMockBuilder('fondof_contentful/callback_handler_page')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_clientMock = $this->getModelMockBuilder('fondof_contentful/client')
            ->disableOriginalConstructor()
            ->setMethods(array('reviveJson'))
            ->getMock();

        $this->replaceByMock('singleton', 'fondof_contentful/client', $this->_clientMock);
        $this->replaceByMock('model', 'fondof_contentful/callback_handler_cache', $this->_callbackHandlerCacheMock);
        $this->replaceByMock('model', 'fondof_contentful/callback_handler_page', $this->_callbackHandlerPageMock);

        $this->_callbackHandlerChain = Mage::getModel('fondof_contentful/callback_handler_chain');
    }

    /**
     * @test
     */
    public function testExecuteWithInvalidRequest()
    {
        $this->_requestMock->expects($this->atLeastOnce())
            ->method('getHeader')
            ->with('X-Contentful-Topic')
            ->willReturn(null);

        $this->_requestMock->expects($this->never())
            ->method('getRawBody');

        $this->_clientMock->expects($this->never())
            ->method('reviveJson');

        $this->_callbackHandlerCacheMock->expects($this->never())
            ->method('handle');

        $this->_callbackHandlerPageMock->expects($this->never())
            ->method('handle');

        $this->assertEquals($this->_callbackHandlerChain, $this->_callbackHandlerChain->execute($this->_requestMock));
    }

    /**
     * @test
     */
    public function testExecuteWithValidRequest()
    {
        $rawBody = '{"sys": {...}, "fields": {...}}';
        $topic = 'ContentManagement.Entry.publish';

        $this->_requestMock->expects($this->atLeastOnce())
            ->method('getHeader')
            ->with('X-Contentful-Topic')
            ->willReturn($topic);

        $this->_requestMock->expects($this->atLeastOnce())
            ->method('getRawBody')
            ->willReturn($rawBody);

        $this->_clientMock->expects($this->atLeastOnce())
            ->method('reviveJson')
            ->with($rawBody)
            ->willReturn($this->_bodyMock);

        $this->_callbackHandlerCacheMock->expects($this->atLeastOnce())
            ->method('handle')
            ->with($topic, $this->_bodyMock)
            ->willReturn($this->_callbackHandlerCacheMock);

        $this->_callbackHandlerPageMock->expects($this->atLeastOnce())
            ->method('handle')
            ->with($topic, $this->_bodyMock)
            ->willReturn($this->_callbackHandlerPageMock);

        $this->assertEquals($this->_callbackHandlerChain, $this->_callbackHandlerChain->execute($this->_requestMock));
    }
}