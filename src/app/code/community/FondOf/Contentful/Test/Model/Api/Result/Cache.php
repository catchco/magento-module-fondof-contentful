<?php

class FondOf_Contentful_Test_Model_Api_Result_Cache extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var FondOf_Contentful_Model_Api_Result_Cache
     */
    protected $_apiResultCache;

    /**
     * @var array
     */
    protected $_cacheKeyInfo = array(
        'id' => 'entry12345678901',
        'type' => 'Entry'
    );

    /**
     * @var string
     */
    protected $_apiResult = '{"sys": {"id": "entry12345678901", "type": "Entry"}}';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_apiResultCache = Mage::getModel('fondof_contentful/api_result_cache');
    }

    /**
     * @test
     * @cache off fondof_contentful_api_results
     */
    public function testSaveWithDeactivatedCache()
    {
        $this->assertEquals(
            $this->_apiResultCache,
            $this->_apiResultCache->save($this->_cacheKeyInfo, $this->_apiResult)
        );
    }

    /**
     * @test
     * @depends testSaveWithDeactivatedCache
     * @cache off fondof_contentful_api_results
     */
    public function testLoadWithDeactivatedCache()
    {
        $this->assertEquals(
            null,
            $this->_apiResultCache->load($this->_cacheKeyInfo)
        );
    }

    /**
     * @test
     * @depends testLoadWithDeactivatedCache
     * @cache off fondof_contentful_api_results
     */
    public function testDeleteWithDeactivatedCache()
    {
        $this->assertEquals(
            $this->_apiResultCache,
            $this->_apiResultCache->delete($this->_cacheKeyInfo)
        );
    }

    /**
     * @test
     * @depends testDeleteWithDeactivatedCache
     * @cache on fondof_contentful_api_results
     */
    public function testSaveWithActivatedCache()
    {
        $this->assertEquals(
            $this->_apiResultCache,
            $this->_apiResultCache->save($this->_cacheKeyInfo, $this->_apiResult)
        );
    }

    /**
     * @test
     * @depends testSaveWithActivatedCache
     * @cache on fondof_contentful_api_results
     */
    public function testLoadWithActivatedCache()
    {
        $this->assertEquals(
            '{"sys": {"id": "entry12345678901", "type": "Entry"}}',
            $this->_apiResultCache->load($this->_cacheKeyInfo)
        );
    }

    /**
     * @test
     * @depends testLoadWithActivatedCache
     * @cache on fondof_contentful_api_results
     */
    public function testDeleteWithActivatedCache()
    {
        $this->assertEquals(
            $this->_apiResultCache,
            $this->_apiResultCache->delete($this->_cacheKeyInfo)
        );
    }

    /**
     * @test
     */
    public function testGetCacheKey()
    {
        $cacheKeyInfo = array('id', 'tag');
        $cacheKey = '08d09629e870a5f9576ae937252bd4211c1c778a';

        $this->assertEquals($cacheKey, $this->_apiResultCache->getCacheKey($cacheKeyInfo));
    }
}
