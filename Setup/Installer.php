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
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param \Magento\CmsSampleData\Model\Page $page
     * @param \Magento\CmsSampleData\Model\Block $block
     * @param \MagentoEse\LumaDECms\Model\UpdateRefBlocks
     * @param \Magento\Framework\App\ResourceConnection
     */
    public function __construct(
        \MagentoEse\LumaDECms\Model\Page $page,
        \MagentoEse\LumaDECms\Model\Block $block,
        \MagentoEse\LumaDECms\Model\UpdateRefBlocks $updateRefBlocks,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->page = $page;
        $this->block = $block;
        $this->updateRefBlocks = $updateRefBlocks;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        // due to 2.2 deployment method described here http://devdocs.magento.com/guides/v2.2/cloud/live/sens-data-over.html
        // luma sample data creates urls for all stores now created early in the installation process
        // so delete the urls created for the wrong store ids
        $this->resourceConnection->getConnection()->query("delete from url_rewrite where store_id != 1;");

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
