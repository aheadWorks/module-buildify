<?php
namespace Aheadworks\Buildify\Model\Buildify\Template\EntityProcessor;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Common
 * @package Aheadworks\Buildify\Model\Buildify\Template\EntityProcessor
 */
class Common
{
    /**
     * Set data before save
     *
     * @param AbstractModel|TemplateInterface $object
     * @return AbstractModel|TemplateInterface
     */
    public function beforeSave($object)
    {
        if (empty($object->getExternalId())) {
            $object->setExternalId(uniqid());
        }

        return $object;
    }

    /**
     * Set data after load
     *
     * @param AbstractModel|TemplateInterface $object
     * @return AbstractModel|TemplateInterface
     */
    public function afterLoad($object)
    {
        return $object;
    }
}
