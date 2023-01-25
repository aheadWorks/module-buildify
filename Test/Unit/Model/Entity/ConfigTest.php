<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Entity;

use Aheadworks\Buildify\Model\Entity\Config;
use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Config as ModuleConfig;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Entity
 */
class ConfigTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Config
     */
    private $configObj;

    /**
     * @var ModuleConfig
     */
    private $moduleConfigMock;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->moduleConfigMock = $this->createConfiguredMock(ModuleConfig::class, [
            'isEnabledForEntityType' => true
        ]);
        $this->configObj = $this->objectManager->getObject(Config::class, [
            'config' => [
                'cms_page_form' => [
                    'content' => [
                        'isModal' => false,
                        'extensionAttributesKey' => EntityFieldInterface::CMS_PAGE_ENTITY_TYPE
                    ]
                ],
                'cms_block_form' => [
                    'content' => [
                        'isModal' => false,
                        'extensionAttributesKey' => EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE
                    ]
                ],
                'category_form' => [
                    'description' => [
                        'isModal' => true,
                        'extensionAttributesKey' => EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE
                    ]
                ],
                'product_form' => [
                    'description' => [
                        'isModal' => true,
                        'extensionAttributesKey' => EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE
                    ]
                ]
            ],
            'moduleConfig' => $this->moduleConfigMock
        ]);
    }

    /**
     * Is allowed
     *
     * @dataProvider dataProviderIsTodayTest
     * @param string $htmlId
     * @param bool $expected
     * @covers Config::isAllowed
     */
    public function testIsAllowed($htmlId, $expected)
    {
        $this->assertEquals($expected, $this->configObj->isAllowed($htmlId));
    }

    /**
     * Retrieve data provider for testIsTodayDate test
     *
     * @return array
     */
    public function dataProviderIsTodayTest()
    {
        return [
            ['cms_page_form_content', true],
            ['cms_block_form_content', true],
            ['category_form_description', true],
            ['product_form_description', true],
            ['wrong_param', false]
        ];
    }
}
