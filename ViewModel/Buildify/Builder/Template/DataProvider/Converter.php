<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Converter
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider
 */
class Converter
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * Retrieve data
     *
     * @param TemplateInterface $template
     * @return array
     */
    public function convert($template)
    {
        $date = $this->timezone->date($template->getCreatedAt());
        return [
            'date' => $date->format(DateTime::DATETIME_PHP_FORMAT),
            'source' => $template->getSource(),
            'template_id' => $template->getExternalId(),
            'title' => $template->getTitle(),
            'type' => $template->getType()
        ];
    }
}
