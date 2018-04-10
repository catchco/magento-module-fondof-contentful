<?php

class FondOf_Contentful_Test_Controller_PageController extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Model_Client
     */
    protected $_clientMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|\Contentful\Delivery\DynamicEntry
     */
    protected $_pageMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|\Contentful\Delivery\ContentType
     */
    protected $_contentTypeMock;

    /**
     * Set up controller params
     * (non-PHPdoc)
     * @see EcomDev_PHPUnit_Test_Case::setUp()
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_clientMock = $this->getModelMockBuilder('fondof_contentful/client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_pageMock = $this->getMockBuilder(\Contentful\Delivery\DynamicEntry::class)
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getId',
                    'getIdentifier',
                    'getTitle',
                    'getMetaDescription',
                    'getMetaKeywords',
                    'getRobots',
                    'getContent',
                    'getContentType'
                )
            )->getMock();

        $this->_contentTypeMock = $this->getMockBuilder(\Contentful\Delivery\ContentType::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->replaceByMock('singleton', 'fondof_contentful/client', $this->_clientMock);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();

        Mage::unregister('fondof_contentful_page');
    }

    /**
     * @test
     */
    public function testViewAction()
    {
        $entryId = 'e8403298eqwd809s...';

        $this->_clientMock->expects($this->atLeastOnce())
            ->method('getEntry')
            ->with($entryId)
            ->willReturn($this->_pageMock);

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entryId);

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getIdentifier')
            ->willReturn('Identifier');

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getTitle')
            ->willReturn('Title');

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getMetaDescription')
            ->willReturn('MetaDescription');

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getMetaKeywords')
            ->willReturn('MetaKeywords');

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getRobots')
            ->willReturn('noindex,nofollow');

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getContent')
            ->willReturn(array());

        $this->_pageMock->expects($this->atLeastOnce())
            ->method('getContentType')
            ->willReturn($this->_contentTypeMock);

        $this->_contentTypeMock->expects($this->atLeastOnce())
            ->method('getField')
            ->with('isActive')
            ->willReturn(null);

        $this->dispatch('fondof_contentful/page/view', array('_query' => array('id' => $entryId)));
    }

    /**
     * @test
     */
    public function testViewActionWithNonExistingPageId()
    {
        $entryId = 'e8403298eqwd809s...';

        $this->_clientMock->expects($this->atLeastOnce())
            ->method('getEntry')
            ->with($entryId)
            ->willReturn(null);

        $this->dispatch('fondof_contentful/page/view', array('_query' => array('id' => $entryId)));
    }
}
