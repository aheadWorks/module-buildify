<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Url;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Encryption\EncryptorInterface;
use Aheadworks\Buildify\Model\Url\ParamEncryptor;

/**
 * Class ParamEncryptorTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Url
 */
class ParamEncryptorTest extends TestCase
{
    /**
     * @var EncryptorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $encryptorMock;

    /**
     * @var ParamEncryptor
     */
    private $paramEncryptor;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->encryptorMock = $this->getMockForAbstractClass(EncryptorInterface::class);
        $this->paramEncryptor = $objectManager->getObject(
            ParamEncryptor::class,
            [
                'encryptor' => $this->encryptorMock
            ]
        );
    }

    /**
     * Test encrypt method
     *
     * @dataProvider dataProviderEncryptTest
     * @param array $params
     * @param string $preparedParams
     * @param string $encodedRapams
     */
    public function testEncrypt($params, $preparedParams, $encodedRapams)
    {
        $this->encryptorMock->expects($this->once())
            ->method('encrypt')
            ->with($preparedParams)
            ->willReturn($preparedParams);

        $this->assertEquals($encodedRapams, $this->paramEncryptor->encrypt($params));
    }

    /**
     * Test decrypt method
     *
     * @dataProvider dataProviderDecryptTest
     * @param string $encodedRapams
     * @param string $preparedParams
     * @param string $paramKey
     * @param string $expected
     */
    public function testDecrypt($encodedRapams, $preparedParams, $paramKey, $expected)
    {
        $this->encryptorMock->expects($this->once())
            ->method('decrypt')
            ->willReturn($preparedParams);

        $this->assertEquals($expected, $this->paramEncryptor->decrypt($paramKey, $encodedRapams));
    }

    /**
     * Retrieve data provider for testEncrypt test
     *
     * @return array
     */
    public function dataProviderEncryptTest()
    {
        return [
            [
                [
                    'fileUrl' => 'https://example.com/fileUrl',
                    'baseThemeUrl' => 'https://example.com/baseThemeUrl',
                ],
                'fileUrl::https://example.com/fileUrl;baseThemeUrl::https://example.com/baseThemeUrl',
                'ZmlsZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9maWxlVXJsO2Jhc2VUaGVtZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9iYXNlVGhlbWVVcmw='
            ],
            [
                [
                    'fileUrl' => 'https://example.com/fileUrl'
                ],
                'fileUrl::https://example.com/fileUrl',
                'ZmlsZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9maWxlVXJs'
            ],
            [
                [],
                '',
                ''
            ],
        ];
    }

    /**
     * Retrieve data provider for testEncrypt test
     *
     * @return array
     */
    public function dataProviderDecryptTest()
    {
        return [
            [
                'ZmlsZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9maWxlVXJsO2Jhc2VUaGVtZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9iYXNlVGhlbWVVcmw=',
                'fileUrl::https://example.com/fileUrl;baseThemeUrl::https://example.com/baseThemeUrl',
                'fileUrl',
                'https://example.com/fileUrl',
            ],
            [
                'ZmlsZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9maWxlVXJsO2Jhc2VUaGVtZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9iYXNlVGhlbWVVcmw=',
                'fileUrl::https://example.com/fileUrl;baseThemeUrl::https://example.com/baseThemeUrl',
                'baseThemeUrl',
                'https://example.com/baseThemeUrl'
            ],
            [
                'ZmlsZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9maWxlVXJsO2Jhc2VUaGVtZVVybDo6aHR0cHM6Ly9leGFtcGxlLmNvbS9iYXNlVGhlbWVVcmw=',
                'fileUrl::https://example.com/fileUrl;baseThemeUrl::https://example.com/baseThemeUrl',
                'fakeKey',
                null
            ]
        ];
    }
}
