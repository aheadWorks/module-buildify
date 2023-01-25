<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

use Magento\Framework\UrlInterface;

/**
 * Class TemplateConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class TemplateConfigProvider implements ConfigProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param UrlInterface $url
     */
    public function __construct(UrlInterface $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        $output['template']['saveUrl'] = $this->url->getUrl('aw_buildify/builder/template_save');
        $output['template']['importUrl'] = $this->url->getUrl('aw_buildify/builder/template_import');
        $output['template']['exportUrl'] = $this->url->getUrl('aw_buildify/builder/template_export');
        $output['template']['getUrl'] = $this->url->getUrl('aw_buildify/builder/template_getList');
        $output['template']['deleteUrl'] = $this->url->getUrl('aw_buildify/builder/template_delete');
        $output['template']['getContentUrl'] = $this->url->getUrl('aw_buildify/builder/template_getContent');

        return $output;
    }
}
