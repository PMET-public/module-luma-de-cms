<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\LumaDECms\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;


class OldInstallData implements DataPatchInterface, PatchVersionInterface
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
     * OldInstallData constructor.
     * @param \MagentoEse\LumaDECms\Model\Page $page
     * @param \MagentoEse\LumaDECms\Model\Block $block
     * @param \MagentoEse\LumaDECms\Model\UpdateRefBlocks $updateRefBlocks
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
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

    public function apply()
    {
        //due to 2.2 deployment method described here http://devdocs.magento.com/guides/v2.2/cloud/live/sens-data-over.html
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

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
    public static function getVersion()
    {
        return '0.0.1';
    }
}