<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig;

use Aheadworks\Buildify\Model\EntityField;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class ConfigModifier
{
    /**
     * @var string
     */
    private $pattern = '#\{\{(.*?)\}\}#';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * Replace url in editor config
     *
     * @param EntityField $entityField
     * @return EntityField
     */
    public function modifyLinkEntityField(EntityField $entityField): EntityField
    {
        $content = (string)$entityField->getEditorConfig();
        $entityField->setEditorConfig($this->modifyLinkContent($content));
        return $entityField;
    }

    /**
     * Modify link in content
     *
     * @param string $content
     * @return string
     */
    public function modifyLinkContent(string $content): string
    {
        preg_match_all($this->pattern, $content, $matches);
        foreach ($matches[0] ?? [] as $item) {
            $clearString = str_replace(['\\', '{{', '}}', '"'], '', $item);
            $types = explode(' ', (string) $clearString);
            $url = explode('=', $types[1] ?? '');
            $type = $types[0] ?? '';
            $pathUrl = $url[1] ?? '';
            if ($type == StoreManagerInterface::CONTEXT_STORE && $pathUrl) {
                $typeUrl = $this->storeManager->getStore()->getBaseUrl();
                $content = str_replace((string) $item, $typeUrl . $pathUrl, $content);
            } else if ($type == UrlInterface::URL_TYPE_MEDIA && $pathUrl) {
                $typeUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $content = str_replace((string) $item, $typeUrl . $pathUrl, $content);
            }
        }
        return $content;
    }
}
