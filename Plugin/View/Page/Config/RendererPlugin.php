<?php
namespace Aheadworks\Buildify\Plugin\View\Page\Config;

use Magento\Framework\View\Page\Config\Renderer;
use Aheadworks\Buildify\ViewModel\Template\CssLoader;
use Magento\Framework\Exception\LocalizedException;
/**
 * Class RendererPlugin
 * @package Aheadworks\Buildify\Plugin\View\Page\Config
 */
class RendererPlugin
{
    /**
     * @var CssLoader
     */
    private $cssLoader;

    /**
     * @param CssLoader $cssLoader
     */
    public function __construct(
        CssLoader $cssLoader
    ) {
        $this->cssLoader = $cssLoader;
    }

    /**
     * @param Renderer $subject
     * @param string $result
     * @return string
     * @throws LocalizedException
     */
    public function afterRenderHeadContent(Renderer $subject, $result)
    {
        try {
            $cssStyle = $this->cssLoader->getCssStyle();
        } catch (LocalizedException $e) {
            $cssStyle = '';
        }
        return $cssStyle . $result;
    }
}