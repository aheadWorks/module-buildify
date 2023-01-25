<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Model\Entity;

use Magento\Framework\App\View;

class Layout
{
    /**
     * @var array
     */
    private $layoutMap;

    /**
     * @var View
     */
    private $view;

    /**
     * @param View $view
     * @param array $layoutMap
     */
    public function __construct(View $view, array $layoutMap = [])
    {
        $this->view = $view;
        $this->layoutMap = $layoutMap;
    }

    /**
     * Get current entity type
     *
     * @return string
     */
    public function getCurrentEntityType(): string
    {
        return $this->layoutMap[$this->view->getDefaultLayoutHandle()] ?? '';
    }
}
