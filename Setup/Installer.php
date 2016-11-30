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
     * @var \Magento\CmsSampleData\Model\Page
     */
    private $page;

    /**
     * @var \Magento\CmsSampleData\Model\Block
     */
    private $block;

    /**
     * @var \Magento\CmsSampleData\Model\Block
     */
    private $updateRefBlocks;

    /**
     * @param \Magento\CmsSampleData\Model\Page $page
     * @param \Magento\CmsSampleData\Model\Block $block
     */
    public function __construct(
        \MagentoEse\LumaDECms\Model\Page $page,
        \MagentoEse\LumaDECms\Model\Block $block,
        \MagentoEse\LumaDECms\Model\UpdateRefBlocks $updateRefBlocks
    ) {
        $this->page = $page;
        $this->block = $block;
        $this->updateRefBlocks = $updateRefBlocks;
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
        $this->updateRefBlocks->install(['MagentoEse_LumaDECms::fixtures/blocks/ref_block_update.csv']);
    }
}
