<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision\DataProvider;

use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Converter
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision\DataProvider
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
     * Convert revision to buildify data array
     *
     * @param RevisionInterface $revision
     * @return array
     */
    public function convert($revision)
    {
        $date = $this->timezone->date($revision->getCreatedAt());
        return [
            'date' => $date->format(DateTime::DATETIME_PHP_FORMAT),
            'id' => $revision->getId()
        ];
    }
}
