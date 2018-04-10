<?php

class FondOf_Contentful_Test_Controller_Router extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Controller_Router
     */
    protected $_router;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Varien_Event
     */
    protected $_event;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Varien_Event_Observer
     */
    protected $_eventObserver;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Mage_Core_Controller_Varien_Front
     */
    protected $_front;
    
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_router = new FondOf_Contentful_Controller_Router();
        $this->_event = $this->getMock(Varien_Event::class);
        $this->_eventObserver = $this->getMock(Varien_Event_Observer::class);
        $this->_front = new Mage_Core_Controller_Varien_Front();
    }

    /**
     * @test
     */
    public function testInitControllerRouters()
    {
        $this->_event->expects($this->atLeastOnce())
            ->method('getData')
            ->with('front')
            ->willReturn($this->_front);

        $this->_eventObserver->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->_event);

        $this->_router->initControllerRouters($this->_eventObserver);

        $this->assertEquals(array('fondof_contentful' => $this->_router), $this->_front->getRouters());
    }

    /**
     * @test
     */
    public function testInitControllerRoutersWithoutEvent()
    {
        $this->_event->expects($this->never())
            ->method('getData')
            ->with('front');

        $this->_eventObserver->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn(null);

        $this->_router->initControllerRouters($this->_eventObserver);

        $this->assertEquals(array(), $this->_front->getRouters());
    }

    /**
     * @test
     */
    public function testInitControllerRoutersWithoutFront()
    {
        $this->_event->expects($this->atLeastOnce())
            ->method('getData')
            ->with('front')
            ->willReturn(null);

        $this->_eventObserver->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->_event);

        $this->_router->initControllerRouters($this->_eventObserver);

        $this->assertEquals(array(), $this->_front->getRouters());
    }
}