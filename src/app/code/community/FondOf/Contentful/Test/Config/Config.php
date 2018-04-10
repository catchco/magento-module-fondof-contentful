<?php

/**
 * Config test
 *
 * @category    FondOf
 * @package     FondOf_Contentful
 */
class FondOf_Contentful_Test_Config_Config extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @test
     */
    public function itShouldHaveAnObserver()
    {
        $this->assertEventObserverDefined(
            'global',
            'controller_front_init_routers',
            'FondOf_Contentful_Controller_Router',
            'initControllerRouters'
        );
    }

    /**
     * @test
     */
    public function itShouldHaveARouter()
    {
        $this->assertRouteFrontName('fondof_contentful', 'fondof_contentful', 'frontend');
    }

    /**
     * @test
     */
    public function itShouldHaveADefinedLayoutFile()
    {
        $this->assertLayoutFileDefined('frontend', 'fondof_contentful.xml', 'fondof_contentful');
    }

    /**
     * @test
     */
    public function itShouldHaveABlockDefinition()
    {
        $this->assertConfigNodeValue('global/blocks/fondof_contentful/class', 'FondOf_Contentful_Block');
    }

    /**
     * @test
     */
    public function itShouldHaveAModelDefinition()
    {
        $this->assertConfigNodeValue('global/models/fondof_contentful/class', 'FondOf_Contentful_Model');
    }

    /**
     * @test
     */
    public function itShouldHaveAResourceModelDefinition()
    {
        $this->assertConfigNodeValue('global/models/fondof_contentful_resource/class', 'FondOf_Contentful_Model_Resource');
    }

    /**
     * @test
     */
    public function itShouldHaveAHelperDefinition()
    {
        $this->assertConfigNodeValue('global/helpers/fondof_contentful/class', 'FondOf_Contentful_Helper');
    }

    /**
     * @test
     */
    public function itShouldDependOnMageCore()
    {
        $this->assertModuleDepends('Mage_Core');
    }

    /**
     * @test
     */
    public function itShouldDependOnMageCatalog()
    {
        $this->assertModuleDepends('Mage_Catalog');
    }
}