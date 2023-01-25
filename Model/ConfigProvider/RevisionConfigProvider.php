<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

use Magento\Framework\UrlInterface;

/**
 * Class RevisionConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class RevisionConfigProvider implements ConfigProviderInterface
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
        $output['revision']['deleteUrl'] = $this->url->getUrl('aw_buildify/builder/revision_delete');
        $output['revision']['getContentUrl'] = $this->url->getUrl('aw_buildify/builder/revision_getContent');

        return $output;
    }
}
