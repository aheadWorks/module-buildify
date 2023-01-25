<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Copier;

/**
 * Class Page
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter
 */
class Page implements EntityAdapterInterface
{
    /**
     * @var TimezoneInterface
     */
    protected $localeDate;

    /**
     * @var Copier
     */
    protected $copier;

    /**
     * @param TimezoneInterface $localeDate
     * @param Copier $copier
     */
    public function __construct(
        TimezoneInterface $localeDate,
        Copier $copier
    ) {
        $this->localeDate = $localeDate;
        $this->copier = $copier;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityField($entity)
    {
        if ($entity->getBack() === 'duplicate'
            && $entity->isObjectNew()
            && $entity->getAwEntityField()
        ) {
            $currentAwEntityField = $entity->getAwEntityField();
            $newAwEntityField =  $this->copier->copy($currentAwEntityField);

            $entity->setAwEntityField($newAwEntityField);
        }

        return $entity->getAwEntityField();
    }

    /**
     * {@inheritDoc}
     */
    public function setHtml($entity, $html)
    {
        $entity->setContent($html);
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getHtml($entity)
    {
        return $entity->getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function getId($entity)
    {
        return $entity->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityType()
    {
        return EntityFieldInterface::CMS_PAGE_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntityField($entity, $newEntity)
    {
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomTheme($entity)
    {
        $inRange = $this->localeDate->isScopeDateInInterval(
            null,
            $entity->getCustomThemeFrom(),
            $entity->getCustomThemeTo()
        );

        return $inRange ? $entity->getCustomTheme() : null;
    }
}
