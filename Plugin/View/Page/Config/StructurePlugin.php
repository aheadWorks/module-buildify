<?php
namespace Aheadworks\Buildify\Plugin\View\Page\Config;

use Magento\Framework\View\Page\Config\Structure;

/**
 * Class StructurePlugin
 * @package Aheadworks\Buildify\Plugin\View\Page\Config
 */
class StructurePlugin
{
    /**
     * @param Structure $subject
     * @param array $result
     * @return array
     */
    public function afterGetAssets(Structure $subject, $result)
    {
        $bfyKey = 'Aheadworks_Buildify::css/buildify-full.css';

        if (array_key_exists($bfyKey, $result)) {
            $result = [$bfyKey => $result[$bfyKey]] + $result;
        }

        return $result;
    }
}