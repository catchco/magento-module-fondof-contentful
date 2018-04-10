<?php

class FondOf_Contentful_Test_Model_Callback_Handler_Page extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Model_Callback_Handler_Page
     */
    protected $_callbackHandlerPage;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Model_Mapping
     */
    protected $_mappingMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Contentful\Delivery\DynamicEntry
     */
    protected $_bodyMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Contentful\Delivery\ContentType
     */
    protected $_contentTypeMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Helper_Data
     */
    protected $_defaultHelper;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_callbackHandlerPage = Mage::getModel('fondof_contentful/callback_handler_page');

        $this->_mappingMock = $this->getModelMockBuilder('fondof_contentful/mapping')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_defaultHelper = $this->getHelperMockBuilder('fondof_contentful')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_bodyMock = $this->getMockBuilder(\Contentful\Delivery\DynamicEntry::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getId', 'getContentType', 'getIdentifier'))
            ->getMock();

        $this->_contentTypeMock = $this->getMockBuilder(\Contentful\Delivery\ContentType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->replaceByMock('model', 'fondof_contentful/mapping', $this->_mappingMock);
        $this->replaceByMock('helper', 'fondof_contentful', $this->_defaultHelper);
    }

    /**
     * @test
     */
    public function testHandleWithInvalidParams()
    {
        $this->_mappingMock->expects($this->never())
            ->method('setData');

        $this->_mappingMock->expects($this->never())
            ->method('save');

        $this->assertEquals($this->_callbackHandlerPage, $this->_callbackHandlerPage->handle('', ''));
    }

    /**
     * @test
     */
    public function testHandleWithValidParams()
    {
        $topic = 'ContentManagement.Entry.publish';
        $entryId = '5JzuLNLulqQeUaMOGKgaIS';

        $identifiers = array(
            'de' => 'ueberuns',
            'en' => 'aboutus'
        );

        $mappingDataList = array(
            array(
                'entry_id' => $entryId,
                'entry_identifier' => $identifiers['en'],
                'store_id' => 0
            ),
            array(
                'entry_id' => $entryId,
                'entry_identifier' => $identifiers['de'],
                'store_id' => 1
            )
        );

        $this->_defaultHelper->expects($this->atLeastOnce())
            ->method('getDefaultLocale')
            ->willReturn('en');

        $this->_defaultHelper->expects($this->atLeastOnce())
            ->method('getLocale')
            ->willReturn('de');

        $this->_contentTypeMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn('page');

        $this->_bodyMock->expects($this->atLeastOnce())
            ->method('getContentType')
            ->willReturn($this->_contentTypeMock);

        $this->_bodyMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entryId);

        $this->_bodyMock->expects($this->exactly(2))
            ->method('getIdentifier')
            ->withConsecutive(array('en'), array('de'))
            ->willReturnOnConsecutiveCalls($identifiers['en'], $identifiers['de']);

        $this->_mappingMock->expects($this->exactly(2))
            ->method('setData')
            ->withConsecutive(array($mappingDataList[0]), array($mappingDataList[1]))
            ->willReturnOnConsecutiveCalls($this->_mappingMock, $this->_mappingMock);

        $this->_mappingMock->expects($this->exactly(2))
            ->method('save')
            ->willReturn($this->_mappingMock);

        $this->assertEquals($this->_callbackHandlerPage, $this->_callbackHandlerPage->handle($topic, $this->_bodyMock));
    }
}
