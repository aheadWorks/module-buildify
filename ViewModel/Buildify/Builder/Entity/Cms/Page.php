<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Cms;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocatorInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\Registry;

/**
 * Class Page
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Cms
 */
class Page implements EntityLocatorInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntity()
    {
        /** @var PageInterface $page */
        if ($page = $this->registry->registry('cms_page')) {
            return $page;
        }

        return false;
    }
}
