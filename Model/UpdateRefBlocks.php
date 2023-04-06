<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDECms\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use MagentoEse\LumaDECms\Model\Block\Converter;
use Magento\Store\Model\Store;

/**
 * Class Block
 */
class UpdateRefBlocks
{
    /**
     * @var \Magento\Framework\Setup\SampleData\FixtureManager
     */
    private $fixtureManager;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvReader;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var Block\Converter
     */
    protected $converter;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * 
     * @var Store
     */
    protected $storeView;

    /**
     * 
     * @param SampleDataContext $sampleDataContext 
     * @param Magento\Cms\Model\BlockFactory $blockFactory 
     * @param Converter $converter 
     * @param CategoryRepositoryInterface $categoryRepository 
     * @param Store $storeView 
     * @return void 
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        Block\Converter $converter,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Store\Model\Store $storeView
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->blockFactory = $blockFactory;
        $this->storeView = $storeView;
    }

    public function install(array $fixtures)
    {
        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!file_exists($fileName)) {
                continue;
            }

            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }

                $cmsBlock = $this->saveCmsBlock($data);
                $cmsBlock->unsetData();
            }
        }
    }

    /**
     * @param array $data
     * @return \Magento\Cms\Model\Block
     */
    protected function saveCmsBlock($data)
    {
        $cmsBlock = $this->blockFactory->create();

        $cmsBlock->load($data['identifier']);
        $cmsBlock->setStoreId([1]);
        $cmsBlock->setData('stores',[1]);
        $cmsBlock->save();
        return $cmsBlock;

    }

}
