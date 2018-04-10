<?php

class FondOf_Contentful_Test_Controller_CallbackController extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Helper_Callback_Authorization
     */
    protected $_callbackAuthorizationHelper;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|FondOf_Contentful_Test_Model_Callback_Handler_Chain
     */
    protected $_callbackHandlerChain;

    /**
     * Set up controller params
     * (non-PHPdoc)
     * @see EcomDev_PHPUnit_Test_Case::setUp()
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_callbackAuthorizationHelper = $this->getHelperMockBuilder('fondof_contentful/callback_authorization')
            ->disableOriginalConstructor()
            ->getMock();

        $this->_callbackHandlerChain = $this->getModelMockBuilder('fondof_contentful/callback_handler_chain')
            ->disableOriginalConstructor()
            ->getMock();

        $this->replaceByMock('helper', 'fondof_contentful/callback_authorization', $this->_callbackAuthorizationHelper);
        $this->replaceByMock('singleton', 'fondof_contentful/callback_handler_chain', $this->_callbackHandlerChain);
    }

    /**
     * @test
     */
    public function testHandleActionWithInvalidHttpMethod()
    {
        $this->getRequest()->setMethod('GET');

        $this->dispatch('fondof_contentful/callback/handle');

        $this->assertResponseHttpCode(405);
    }

    /**
     * @test
     */
    public function testHandleActionWithInvalidAuthorizationHeader()
    {
        $authHeader = 'Basic ' . base64_encode('Username:Password');

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeader('Authorization', $authHeader);

        $this->_callbackAuthorizationHelper->expects($this->atLeastOnce())
            ->method('validateAuthorizationHeader')
            ->with($authHeader)
            ->willReturn(false);

        $this->dispatch('fondof_contentful/callback/handle');

        $this->assertResponseHttpCode(403);
    }

    /**
     * @test
     */
    public function testHandleActionWithInvalidHeader()
    {
        $authHeader = 'Basic ' . base64_encode('Username:Password');

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeader('X-Contentful-Topic', null);
        $this->getRequest()->setHeader('Authorization', $authHeader);

        $this->_callbackAuthorizationHelper->expects($this->atLeastOnce())
            ->method('validateAuthorizationHeader')
            ->with($authHeader)
            ->willReturn(true);

        $this->dispatch('fondof_contentful/callback/handle');

        $this->assertResponseHttpCode(400);
    }

    /**
     * @test
     */
    public function testHandleAction()
    {
        $authHeader = 'Basic ' . base64_encode('Username:Password');

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeader('X-Contentful-Topic', 'ContentManagement.Entry.publish');
        $this->getRequest()->setHeader('Authorization', $authHeader);

        $this->_callbackAuthorizationHelper->expects($this->atLeastOnce())
            ->method('validateAuthorizationHeader')
            ->with($authHeader)
            ->willReturn(true);

        $this->_callbackHandlerChain->expects($this->atLeastOnce())
            ->method('execute')
            ->with($this->getRequest())
            ->willReturn($this->_callbackHandlerChain);

        $this->dispatch('fondof_contentful/callback/handle');

        $this->assertResponseHttpCode(200);
    }

    /**
     * @test
     */
    public function testHandleActionForFPM()
    {
        $authHeader = 'Basic ' . base64_encode('Username:Password');

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeader('X-Contentful-Topic', 'ContentManagement.Entry.publish');
        $this->getRequest()->setHeader('Authorization', '');

        $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = $authHeader;

        $this->_callbackAuthorizationHelper->expects($this->atLeastOnce())
            ->method('validateAuthorizationHeader')
            ->with($authHeader)
            ->willReturn(true);

        $this->_callbackHandlerChain->expects($this->atLeastOnce())
            ->method('execute')
            ->with($this->getRequest())
            ->willReturn($this->_callbackHandlerChain);

        $this->dispatch('fondof_contentful/callback/handle');

        $this->assertResponseHttpCode(200);
    }
}
