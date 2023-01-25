<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

use Aheadworks\Buildify\Api\Data\EntityFieldInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class LoadHandler
 * @package Aheadworks\Buildify\Model\Buildify\Entity
 */
class LoadHandler
{
    /**
     * @var EntityFieldInterfaceFactory
     */
    private $entityFieldFactory;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param EntityFieldInterfaceFactory $entityFieldFactory
     * @param Repository $repository
     */
    public function __construct(
        EntityFieldInterfaceFactory $entityFieldFactory,
        Repository $repository
    ) {
        $this->entityFieldFactory = $entityFieldFactory;
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @param string $type
     * @return \Aheadworks\Buildify\Api\Data\EntityFieldInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function load($id, $type)
    {
        try {
            $awEntityField = $this->repository->get($id, $type);
        } catch (NoSuchEntityException $e) {
            $awEntityField = $this->entityFieldFactory->create();
        }

        return $awEntityField;
    }
}
