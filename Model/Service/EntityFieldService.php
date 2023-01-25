<?php
namespace Aheadworks\Buildify\Model\Service;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Api\EntityFieldManagementInterface;
use Aheadworks\Buildify\Api\EntityRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Blog\Model\Post;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Buildify\Model\Entity\Config;

/**
 * Class EntityFieldService
 * @package Aheadworks\Buildify\Model\Service
 */
class EntityFieldService implements EntityFieldManagementInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ObjectManagerInterface $objectManager
     * @param Config $config
     */
    public function __construct(
        EntityRepositoryInterface $entityRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->entityRepository = $entityRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectManager = $objectManager;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function removeDomainFromImgPath()
    {
        return $this->reSaveEntities();
    }

    /**
     * {@inheritDoc}
     */
    public function reSaveEntities()
    {
        foreach ($this->config->getConfig() as $configEntities) {
            $entityTypeConfig = array_shift($configEntities);
            $entityType = $entityTypeConfig['extensionAttributesKey'];
            $this->searchCriteriaBuilder->addFilter(EntityFieldInterface::ENTITY_TYPE, $entityType);
            $bfyEntities = $this->entityRepository->getList($this->searchCriteriaBuilder->create())->getItems();

            try {
                foreach ($bfyEntities as $entity) {
                    $entityTypeModel = $this->objectManager->create($entityTypeConfig['model']);
                    $entityTypeRepo = $this->objectManager->create($entityTypeConfig['repo']);

                    if ($entityType == EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE) {
                        $entityTypeModel = $entityTypeModel->get($entity->getEntityId());
                    } else {
                        $entityTypeModel = $entityTypeModel->load($entity->getEntityId());
                    }

                    $entityTypeRepo->save($entityTypeModel);
                }
            } catch (\Exception $e) {
                continue;
            }

            // todo refactoring. use getList instead of load
//            if ($bfyEntityIds) {
//                $entityTypeRepo = $this->objectManager->create($entityTypeConfig['repo']);
//                $this->searchCriteriaBuilder
//                    ->addFilter($entityTypeConfig['id'], $bfyEntityIds, 'in');
//                $entities = $entityTypeRepo->getList($this->searchCriteriaBuilder->create())->getItems();
//                foreach ($entities as $entity) {
//                    $entityTypeRepo->save($entity);
//                }
//            }
        }

        return true;
    }
}
