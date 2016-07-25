<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDECms\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * Class Block
 */
class Block
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
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param Block\Converter $converter
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
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
        $this->converter = $converter;
        $this->categoryRepository = $categoryRepository;
        $this->config = require 'Config.php';
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
                $row = $data;
                $data = $this->converter->convertRow($row);
                $cmsBlock = $this->saveCmsBlock($data['block']);
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
        $cmsBlock->getResource()->load($cmsBlock, $data['identifier']);
        
        //get view id from view code
        $_viewId = $this->storeView->load($this->config['viewCode'])->getStoreId();
        
        if (!$cmsBlock->getData()) {
            $cmsBlock->setData($data);
        } else {
            $cmsBlock->addData($data);
        }
        //need to add luma_de as store view (ultimatly id)
        $cmsBlock->setStoreId($_viewId);
        $cmsBlock->setIsActive(1);
        $cmsBlock->save();
        return $cmsBlock;
    }

    /**
     * @param string $blockId
     * @param string $categoryId
     * @return void
     */
    protected function setCategoryLandingPage($blockId, $categoryId)
    {
        $categoryCms = [
            'landing_page' => $blockId,
            'display_mode' => 'PRODUCTS_AND_PAGE',
        ];
        if (!empty($categoryId)) {
            $category = $this->categoryRepository->get($categoryId);
            $category->setData($categoryCms);
            $this->categoryRepository->save($categoryId);
        }
    }
}
