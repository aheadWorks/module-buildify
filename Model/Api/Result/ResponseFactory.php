<?php
namespace Aheadworks\Buildify\Model\Api\Result;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Buildify\Api\Data\EditorConfigResponseInterface;

/**
 * Class ResponseFactory
 * @package Aheadworks\Buildify\Model\Api\Result
 */
class ResponseFactory
{
    /**
     * Use definParams if your response depends on request parameter. Set into definParams request parameter name.
     *
     * If your response does not depend on request parameter just leave definParams empty and add config to default
     * parameter. For example $responseClassMapper['path']['config']['default']
     *
     * @var array
     */
    private $responseClassMapper = [
        '/builder/act' => [
            'definParams' => 'action' ,
            'config' => [
                'buildify_process_builder_data' => [
                    'class' => EditorConfigResponseInterface::class,
                    'type' => '',
                    'value' => '',
                    'dataProcessor' => ''
                ],
                'buildify_encode_template_data' => [
                    'class' => EditorConfigResponseInterface::class,
                    'type' => '',
                    'value' => '',
                    'dataProcessor' => ''
                ],
                'buildify_decode_process_builder_data' => [
                    'class' => TemplateInterface::class,
                    'type' => 'array',
                    'value' => '',
                    'dataProcessor' => ''
                ]
            ]
        ],
    ];

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->objectManager = $objectManager;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Create response object
     *
     * @param  string $url
     * @param array $params
     * @param array $response
     * @return mixed
     */
    public function create($url, $params, $response)
    {
        if ($config = $this->getConfig($url, $params)) {
            $type = $config['type'];
            $value = $config['value'];
            $response = $value ? $response[$value] : $response;

            if ($type == 'array') {
                $object = [];
                foreach ($response as $row) {
                    $object[] = $this->convertToObject($row, $config);
                }
            } else {
                $object = $this->convertToObject($response, $config);
            }
        } else {
            $object = $this->objectManager->create(Response::class, ['data' => $response]);
        }

        return $object;
    }

    /**
     * Convert to object
     *
     * @param array $row
     * @param array $config
     * @return mixed
     */
    private function convertToObject($row, $config)
    {
        $class = $config['class'];
        if ($config['dataProcessor']) {
            $dataProcessor = $this->objectManager->create($config['dataProcessor']);
            $row = $dataProcessor->process($row);
        }

        $object = $this->objectManager->create($class);
        $this->dataObjectHelper->populateWithArray(
            $object,
            $row,
            $class
        );
        return $object;
    }

    /**
     * Get config
     *
     * @param string $url
     * @param array $params
     * @return array|null
     */
    private function getConfig($url, $params)
    {
        $config = null;
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        $path = parse_url($url, PHP_URL_PATH);
        if (isset($this->responseClassMapper[$path])) {
            $configKey = 'default';

            if (
                !empty($this->responseClassMapper[$path]['definParams'])
                && isset($params[$this->responseClassMapper[$path]['definParams']])
            ) {
                $configKey = $params[$this->responseClassMapper[$path]['definParams']];
            }

            $config = $this->responseClassMapper[$path]['config'][$configKey] ?? null;
        }

        return $config;
    }
}
