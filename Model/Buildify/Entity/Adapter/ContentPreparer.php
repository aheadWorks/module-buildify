<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter;

/**
 * Class ContentPreparer
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter
 */
class ContentPreparer
{
    /**
     * @var array
     */
    private $isNeededMerge = [];

    /**
     * @param array $isNeededMerge
     */
    public function __construct(
        array $isNeededMerge
    ) {
        $this->isNeededMerge = $isNeededMerge;
    }

    /**
     * Merge html and css if it needs
     *
     * @param string $html
     * @param string $css
     * @param string $type
     *
     * @return string
     */
    public function prepareContent($html, $css, $type)
    {
        $content = $html;
        if (in_array($type, $this->isNeededMerge)) {
            $content .= $css;
        }

        return $content;
    }
}
