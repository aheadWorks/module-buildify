<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder;

use Magento\Catalog\Helper\Image as ImageHelper;

/**
 * Class EntityLocator
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder
 */
class EntityLocator implements EntityLocatorInterface
{
    /**
     * @var EntityLocatorInterface[]
     */
    private $locators;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @param ImageHelper $imageHelper
     * @param array $locators
     */
    public function __construct(
        ImageHelper $imageHelper,
        $locators = []
    ) {
        $this->imageHelper = $imageHelper;
        $this->locators = $locators;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        $entity = false;
        /** @var EntityLocatorInterface $locator */
        foreach ($this->locators as $locator) {
            if ($entity = $locator->getEntity()) {
                break;
            }
        }

        return $entity;
    }
}
