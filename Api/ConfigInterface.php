<?php
namespace Raven\AtlasPay\Api;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Config Provider Interface
 *
 * @api
 */
interface ConfigInterface extends ConfigProviderInterface
{
    /**
     *
     * @return bool
     */
    public function getSecretKeyArray();
}
