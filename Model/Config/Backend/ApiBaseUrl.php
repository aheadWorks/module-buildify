<?php
namespace Aheadworks\Buildify\Model\Config\Backend;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\App\State;

/**
 * Class ApiBaseUrl
 * @package Aheadworks\Buildify\Model\Config\Backend
 */
class ApiBaseUrl extends Field
{
    /**
     * {@inheritdoc}
     */
    public function render(AbstractElement $element)
    {
        if ($this->_appState->getMode() != State::MODE_PRODUCTION) {
            return parent::render($element);
        }

        return '';
    }
}