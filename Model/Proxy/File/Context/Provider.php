<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Context;

/**
 * Class Provider
 * @package Aheadworks\Buildify\Model\Proxy\File\Context
 */
class Provider
{
    /**
     * @var array
     */
    private $optionProviders;

    /**
     * @param array $optionProviders
     */
    public function __construct(
        array $optionProviders = []
    ) {
        $this->optionProviders = $optionProviders;
    }

    /**
     * Retrieve context
     *
     * @return resource
     */
    public function getContext()
    {
        try {
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $handle = stream_context_create();
            foreach ($this->optionProviders as $provider) {
                if ($options = $provider->getOptions()) {
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    stream_context_set_option($handle, $options);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $handle;
    }
}