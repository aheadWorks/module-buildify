<?php
namespace Aheadworks\Buildify\Model\Buildify\Template\Export;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

/**
 * Class FileManager
 * @package Aheadworks\Buildify\Model\Buildify\Template\Export
 */
class FileManager
{
    public const DEFAULT_FILE_NAME = 'export';
    public const DEFAULT_FILE_EXTENSION = 'json';

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var DateTimeFormatterInterface
     */
    private $dateTimeFormatter;

    /**
     * @param TimezoneInterface $localeDate
     * @param DateTimeFormatterInterface $dateTimeFormatter
     */
    public function __construct(
        TimezoneInterface $localeDate,
        DateTimeFormatterInterface $dateTimeFormatter
    ) {
        $this->localeDate = $localeDate;
        $this->dateTimeFormatter = $dateTimeFormatter;
    }

    /**
     * Retrieve name of export file
     *
     * @param TemplateInterface $template
     * @return string
     */
    public function getFileName($template)
    {
        $titlePrefix = $this->filterFilename($template->getTitle());
        $formattedDate = $this->filterFilename($this->getFormattedDate());

        return $titlePrefix . '_' . $formattedDate . '.' .self::DEFAULT_FILE_EXTENSION;
    }

    /**
     * Retrieve formatted date
     *
     * @return string
     */
    public function getFormattedDate()
    {
        return $this->dateTimeFormatter->formatObject(
            new \DateTime(),
            $this->localeDate->getDateFormat()
        );
    }

    /**
     * Retrieve filtered string
     *
     * @param string $string
     * @return string
     */
    private function filterFilename($string)
    {
        $string = preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $string);
        $string = preg_replace("([\.]{2,})", '', $string);

        return $string;
    }
}