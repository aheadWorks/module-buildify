<?php
namespace Aheadworks\Buildify\Model\Api\Request;

use Aheadworks\Buildify\Model\Api\Mapper\MapperManager;
use Aheadworks\Buildify\Model\Api\Result\Response;
use Aheadworks\Buildify\Model\Api\Result\ResponseFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Adapter\CurlFactory as FrameworkCurlFactory;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Curl
 * @package Aheadworks\Buildify\Model\Api\Request
 */
class Curl
{
    /**
     * Connection timeout
     */
    const CONNECTION_TIMEOUT = 60;

    /**
     * @var FrameworkCurlFactory
     */
    private $curlFactory;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var MapperManager
     */
    private $mapperManager;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param FrameworkCurlFactory $curlFactory
     * @param ResponseFactory $responseFactory
     * @param MapperManager $mapperManager
     */
    public function __construct(
        FrameworkCurlFactory $curlFactory,
        ResponseFactory $responseFactory,
        MapperManager $mapperManager,
        Json $serializer
    ) {
        $this->curlFactory = $curlFactory;
        $this->responseFactory = $responseFactory;
        $this->mapperManager = $mapperManager;
        $this->serializer = $serializer;
    }

    /**
     * Perform api request
     *
     * @param string $url
     * @param array $params
     * @param string $method
     * @return Response
     * @throws LocalizedException
     * @throws \Exception
     */
    public function request($url, $params = [], $method = 'GET')
    {
        $curl = $this->curlFactory->create();
        $curl->setConfig(['timeout' => self::CONNECTION_TIMEOUT, 'header' => false, 'verifypeer' => false]);
        if ($method == 'DELETE') {
            $curl->addOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $this->httpBuildQuery($params, $postParams);
        $curl->write(
            $method,
            $url,
            '1.1',
            $this->getHeaders(),
            $postParams
        );

        try {
            $responseData = $curl->read();
            $responseData = !empty($responseData) ? $this->serializer->unserialize($responseData) : [];
            $responseData = is_array($responseData) ? $responseData : [];
        } catch (\Exception $e) {
            $curl->close();
            throw new LocalizedException(__('Unable to perform request.'));
        }
        $curl->close();

        $responseData = $this->mapperManager->fromApi($url, $responseData);
        $responseData = $this->responseFactory->create($url, $params, $responseData);

        return $responseData;
    }

    /**
     * Prepare query
     *
     * @param array $params
     * @param array $preparedParams
     * @param null $prefix
     */
    private function httpBuildQuery($params, &$preparedParams = [], $prefix = null)
    {
        foreach ($params AS $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value) || is_object($value)) {
                $this->httpBuildQuery($value, $preparedParams, $k);
            } else {
                $preparedParams[$k] = $value;
            }
        }
    }

    /**
     * Get http headers
     *
     * @return array
     */
    private function getHeaders()
    {
        $headers = [];
        $headersData = [
            //uncomment for developer mode
            'Cookie' => 'XDEBUG_SESSION=PHPSTORM'
        ];

        foreach ($headersData as $name => $value) {
            $headers[] = $name . ': ' . $value;
        }

        return $headers;
    }
}
