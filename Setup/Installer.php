<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\LumaDECms\Setup;

use Magento\Framework\Setup;

class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * @var \Magento\CatalogSampleData\Model\Category
     */
    private $category;

    /**
     * Setup class for css
     *
     * @var \Magento\ThemeSampleData\Model\Css
     */
    private $css;

    /**
     * @var \Magento\CmsSampleData\Model\Page
     */
    private $page;

    /**
     * @var \Magento\CmsSampleData\Model\Block
     */
    private $block;

    /**
     * @param \Magento\CatalogSampleData\Model\Category $category
     * @param \Magento\ThemeSampleData\Model\Css $css
     * @param \Magento\CmsSampleData\Model\Page $page
     * @param \Magento\CmsSampleData\Model\Block $block
     */
    public function __construct(
        \MagentoEse\LumaDECms\Model\Page $page,
        \MagentoEse\LumaDECms\Model\Block $block
    ) {
        $this->page = $page;
        $this->block = $block;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->page->install(['MagentoEse_LumaDECms::fixtures/pages/pages.csv']);
        $this->block->install(
            [
                'MagentoEse_LumaDECms::fixtures/blocks/categories_static_blocks.csv',
                'MagentoEse_LumaDECms::fixtures/blocks/categories_static_blocks_giftcard.csv',
                'MagentoEse_LumaDECms::fixtures/blocks/pages_static_blocks.csv',
            ]
        );
    }
}