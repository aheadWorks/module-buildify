<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

use Magento\Widget\Model\Widget\Config as WidgetConfig;
use Magento\Framework\DataObjectFactory;

/**
 * Class WidgetConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class WidgetConfigProvider implements ConfigProviderInterface
{
    /**
     * @var WidgetConfig
     */
    private $widgetConfig;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @param WidgetConfig $widgetConfig
     * @param DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        WidgetConfig $widgetConfig,
        DataObjectFactory $dataObjectFactory
    ) {
        $this->widgetConfig = $widgetConfig;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        $config = $this->dataObjectFactory->create();
        $output['widget']['openModalUrl'] = $this->widgetConfig->getWidgetWindowUrl($config);
        $output['widget']['types'] = $this->widgetConfig->getAvailableWidgets($config);
        $output['widget']['placeholders'] = $this->widgetConfig->getWidgetPlaceholderImageUrls();
        $output['widget']['error_image_url'] = $this->widgetConfig->getErrorImageUrl();

        return $output;
    }
}
