<?php
namespace Aheadworks\Buildify\Model\Theme;

use Aheadworks\Buildify\Model\ResourceModel\Design\Config\Grid\CollectionFactory;

/**
 * Class Provider
 * @package Aheadworks\Buildify\Model\Theme
 */
class Provider
{
    /**
     * @var CollectionFactory
     */
    private $themeCollectionFactory;

    /**
     * @param CollectionFactory $themeCollectionFactory
     */
    public function __construct(
        CollectionFactory $themeCollectionFactory
    ) {
        $this->themeCollectionFactory = $themeCollectionFactory;
    }

    /**
     * Retrieve themes
     *
     * @return array
     */
    public function getThemes() {
        $response = [];
        $collection = $this->themeCollectionFactory->create();

        foreach ($collection->getStoreThemes() as $theme) {
            $response[$theme->getStoreName()] = $theme->getThemeThemeId();
        }

        return $response;
    }
}