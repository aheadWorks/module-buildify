<?php
namespace Aheadworks\Buildify\Ui\Component\Form\Element;

use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManager;
use Magento\Ui\Component\Form\Element\DataType\Media\Image as MagentoImage;

/**
 * Class Image
 * @package Aheadworks\Buildify\Ui\Component\Form\Element
 */
class Image extends MagentoImage
{
    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        parent::prepare();
        $data = array_replace_recursive(
            $this->getData(),
            [
                'config' => [
                    'mediaGallery' => [
                        'baseUrl' => $this->getStoreManager()->getStore()->getBaseUrl()
                    ],
                ],
            ]
        );

        $this->setData($data);
    }

    /**
     * Retrieve StoreManager instance
     *
     * @return StoreManager
     */
    public function getStoreManager() {
        return ObjectManager::getInstance()->get(StoreManager::class);
    }
}
