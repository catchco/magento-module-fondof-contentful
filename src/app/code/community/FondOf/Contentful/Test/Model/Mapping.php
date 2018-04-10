<?php

class FondOf_Contentful_Test_Model_Mapping extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Model_Mapping
     */
    protected $_mapping;

    /**
     * @var EcomDev_PHPUnit_Mock_Proxy|FondOf_Contentful_Model_Resource_Mapping
     */
    protected $_mappingResourceMock;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_mapping = Mage::getModel('fondof_contentful/mapping');
        $this->_mappingResourceMock = $this->getResourceModelMockBuilder('fondof_contentful/mapping')
            ->disableOriginalConstructor()
            ->getMock();
        $this->replaceByMock('resource_singleton', 'fondof_contentful/mapping', $this->_mappingResourceMock);
    }

    /**
     * @test
     */
    public function testGetEntryIdByEntryIdentifierAndContentType()
    {
        $entryIdentifier = 'entry_identifier';
        $entryId = 'entry_id';

        $this->_mappingResourceMock->expects($this->atLeastOnce())
            ->method('getEntryIdByEntryIdentifierAndContentType')
            ->with($entryIdentifier)
            ->willReturn($entryId);

        $this->assertEquals($entryId, $this->_mapping->getEntryIdByEntryIdentifierAndContentType($entryIdentifier));
    }

    /**
     * @test
     */
    public function testDeleteByEntryId()
    {
        $entryId = 'entry_id';

        $this->_mappingResourceMock->expects($this->atLeastOnce())
            ->method('deleteByEntryId')
            ->with($entryId)
            ->willReturn($this->_mappingResourceMock);

        $this->assertEquals($this->_mapping, $this->_mapping->deleteByEntryId($entryId));
    }
}