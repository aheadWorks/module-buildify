<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Plugin\PageBuilder\Model;

use Aheadworks\Buildify\Model\Entity\Layout;
use Magento\PageBuilder\Model\Config;
use Aheadworks\Buildify\Model\Entity\EnableChecker;

class ConfigPlugin
{
    /**
     * @var EnableChecker
     */
    private $enableChecker;

    /**
     * @var Layout
     */
    private $layout;

    /**
     * @param EnableChecker $enableChecker
     * @param Layout $layout
     */
    public function __construct(EnableChecker $enableChecker, Layout $layout)
    {
        $this->enableChecker = $enableChecker;
        $this->layout = $layout;
    }

    /**
     * Returns config setting if page builder enabled
     *
     * @param Config $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsEnabled(Config $subject, bool $result): bool
    {
        $type = $this->layout->getCurrentEntityType();
        return $this->enableChecker->isEnabled($type) ? false : $result;
    }
}
