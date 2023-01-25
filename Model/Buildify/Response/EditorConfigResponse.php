<?php
namespace Aheadworks\Buildify\Model\Buildify\Response;

use Aheadworks\Buildify\Api\Data\EditorConfigResponseInterface;
use Magento\Framework\DataObject;

/**
 * Class EditorConfigResponse
 * @package Aheadworks\Buildify\Model\Buildify\Response
 */
class EditorConfigResponse extends DataObject implements EditorConfigResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getHtml()
    {
        return $this->getData(self::HTML);
    }

    /**
     * {@inheritdoc}
     */
    public function setHtml($html)
    {
        return $this->setData(self::HTML, $html);
    }

    /**
     * {@inheritdoc}
     */
    public function getStyle()
    {
        return $this->getData(self::STYLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStyle($cssStyle)
    {
        return $this->setData(self::STYLE, $cssStyle);
    }

    /**
     * {@inheritdoc}
     */
    public function getEditor()
    {
        return $this->getData(self::EDITOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setEditor($editorConfig)
    {
        return $this->setData(self::EDITOR, $editorConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getEncodedContent()
    {
        return $this->getData(self::ENCODED_CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setEncodedContent($encodedContent)
    {
        return $this->setData(self::ENCODED_CONTENT, $encodedContent);
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage()
    {
        return $this->getData(self::EXCEPTION_MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setExceptionMessage($exceptionMessage)
    {
        return $this->setData(self::EXCEPTION_MESSAGE, $exceptionMessage);
    }
}