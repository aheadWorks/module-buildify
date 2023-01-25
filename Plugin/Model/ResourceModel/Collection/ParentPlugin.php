<?php
namespace Aheadworks\Buildify\Plugin\Model\ResourceModel\Collection;

use Aheadworks\Buildify\Model\ResourceModel\Buildify\Entity\ThirdParty\Collection;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class ParentPlugin
 * @package Aheadworks\Buildify\Plugin\Model\ResourceModel\Collection
 */
class ParentPlugin
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param Collection $collection
     */
    public function __construct(
        Collection $collection
    ) {
        $this->collection = $collection;
    }

    /**
     * Before load plugin
     *
     * @param AbstractDb $subject
     * @param bool $printQuery
     * @param bool $logQuery
     * @return array
     */
    public function beforeLoad(
        $subject,
        $printQuery = false,
        $logQuery = false
    ) {
        $this->collection->joinFieldsBeforeLoad($subject);

        return [$printQuery, $logQuery];
    }
}