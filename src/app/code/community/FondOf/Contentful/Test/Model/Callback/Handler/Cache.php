<?php

class FondOf_Contentful_Test_Model_Callback_Handler_Cache extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Model_Callback_Handler_Cache
     */
    protected $_callbackHandlerCache;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Model_Api_Result_Cache
     */
    protected $_apiResultCacheMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Contentful\Delivery\DynamicEntry
     */
    protected $_bodyMock;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_apiResultCacheMock = $this->getModelMockBuilder('fondof_contentful/api_result_cache')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_bodyMock = $this->getMockBuilder(\Contentful\Delivery\DynamicEntry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->replaceByMock('singleton', 'fondof_contentful/api_result_cache', $this->_apiResultCacheMock);

        $this->_callbackHandlerCache = Mage::getModel('fondof_contentful/callback_handler_cache');
    }

    /**
     * @test
     */
    public function testHandleWithInvalidParams()
    {
        $this->_apiResultCacheMock->expects($this->never())
            ->method('delete');

        $this->assertEquals($this->_callbackHandlerCache, $this->_callbackHandlerCache->handle('', ''));
    }

    /**
     * @test
     */
    public function testHandleWithValidParams()
    {
        $topic = 'ContentManagement.Entry.publish';
        $entryId = '5JzuLNLulqQeUaMOGKgaIS';
        $firstCacheKeyInfo = array('Entry', $entryId);
        $secondCacheKeyInfo = array('query');

        $this->_bodyMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entryId);

        $this->_apiResultCacheMock->expects($this->exactly(2))
            ->method('delete')
            ->withConsecutive(array($firstCacheKeyInfo), array($secondCacheKeyInfo))
            ->willReturn($this->_apiResultCacheMock);

        $this->assertEquals($this->_callbackHandlerCache, $this->_callbackHandlerCache->handle($topic, $this->_bodyMock));
    }
}
