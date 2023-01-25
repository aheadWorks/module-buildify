<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Model\Entity;

use Aheadworks\Buildify\Model\Config as ModuleConfig;

class EnableChecker
{
    /**
     * @var ModuleConfig
     */
    private $moduleConfig;

    /**
     * @param ModuleConfig $moduleConfig
     * @param Layout $layout
     */
    public function __construct(ModuleConfig $moduleConfig, Layout $layout)
    {
        $this->moduleConfig = $moduleConfig;
        $this->layout = $layout;
    }

    /**
     * Check is enable for current entity type
     *
     * @return bool
     */
    public function isEnabled($type): bool
    {
        return $type && $this->moduleConfig->isEnabled() && $this->moduleConfig->isEnabledForEntityType($type);
    }
}
