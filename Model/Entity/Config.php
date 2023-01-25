<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Model\Entity;

use Magento\Framework\DataObject;
use Aheadworks\Buildify\Model\Config as ModuleConfig;

/**
 * Class Config
 * @package Aheadworks\Buildify\Model\Entity
 */
class Config extends DataObject
{
    /**#@+
     * Constants keys of config array.
     */
    public const EXTENSION_ATTRIBUTE_KEY = 'extensionAttributesKey';
    public const IS_MODAL = 'isModal';
    /**#@-*/

    /**
     * @var array
     */
    private $config;

    /**
     * @var ModuleConfig
     */
    private $moduleConfig;

    /**
     * @param array $config
     * @param ModuleConfig $moduleConfig
     * @param array $data
     */
    public function __construct(
        array $config,
        ModuleConfig $moduleConfig,
        array $data = []
    ) {
        $this->config = $config;
        $this->moduleConfig = $moduleConfig;

        parent::__construct($data);
    }

    /**
     * Check whether Buildify is allowed
     *
     * @param string $htmlId
     * @return bool
     */
    public function isAllowed($htmlId)
    {
        foreach ($this->config as $componentType => $entityFieldsConfig) {
            foreach ($entityFieldsConfig as $fieldId => $entityFieldConfig) {
                $isEnable = $this->moduleConfig->isEnabledForEntityType($entityFieldConfig[self::EXTENSION_ATTRIBUTE_KEY]);
                if ($componentType . '_' . $fieldId == $htmlId && $isEnable) {
                    $this->setExtensionAttributesKey($entityFieldConfig[self::EXTENSION_ATTRIBUTE_KEY]);
                    $this->setIsModal($entityFieldConfig[self::IS_MODAL]);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check whether Buildify is allowed for special entity type
     *
     * @param string $type
     * @return bool
     */
    public function isEntityTypeEnable($type)
    {
        foreach ($this->config as $componentType => $entityFieldsConfig) {
            foreach ($entityFieldsConfig as $entityFieldConfig) {
                if ($entityFieldConfig[self::EXTENSION_ATTRIBUTE_KEY] == $type) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Retrieve enabled form types
     *
     * @return array
     */
    public function getEnabledComponentType()
    {
        $componentTypes = [];

        foreach ($this->config as $componentType => $entityFieldsConfig) {
            $componentTypes[] = $componentType;
        }

        return $componentTypes;
    }

    /**
     * Get extension attributes key
     *
     * @return string
     */
    public function getExtensionAttributesKey()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTE_KEY);
    }

    /**
     * Set extension attributes key
     *
     * @param string $extensionAttributesKey
     * @return Config
     */
    public function setExtensionAttributesKey($extensionAttributesKey)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTE_KEY, $extensionAttributesKey);
    }

    /**
     * Get is modal key
     *
     * @return bool
     */
    public function getIsModal()
    {
        return $this->getData(self::IS_MODAL);
    }

    /**
     * Set is modal key
     *
     * @param bool $isModal
     * @return Config
     */
    public function setIsModal($isModal)
    {
        return $this->setData(self::IS_MODAL, $isModal);
    }

    /**
     * Return config
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
