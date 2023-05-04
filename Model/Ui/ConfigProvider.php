<?php
namespace Raven\AtlasPay\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Store\Model\Store as Store;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements \Raven\AtlasPay\Api\ConfigInterface
{

    protected $method;
    protected $scopeConfig;
    protected $serializer;

    public function __construct(
        PaymentHelper $paymentHelper, Store $store,
        // ScopeConfigInterface $scopeConfig,
        Json $serializer
        )
    {
        $this->method = $paymentHelper->getMethodInstance(\Raven\AtlasPay\Model\Payment\AtlasPay::CODE);
        $this->store = $store;
        // $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $publicKey = $this->method->getConfigData('live_public_key');
        if ($this->method->getConfigData('test_mode')) {
            $publicKey = $this->method->getConfigData('test_public_key');
        }

        $integrationType = $this->method->getConfigData('integration_type')?: 'inline';

        $config = [
            'payment' => [
                'raven_atlaspay' => [
                    'secret_key' => $publicKey
                ]
            ]
        ];

        return [
            'payment' => [
                \Raven\AtlasPay\Model\Payment\AtlasPay::CODE => [
                    // 'secret_key' => $publicKey,
                    'integration_type' => $integrationType,
                    'api_url' => $this->store->getBaseUrl() . 'rest/',
                    'integration_type_standard_url' => $this->store->getBaseUrl() . 'atlaspay/payment/setup',
                    'recreate_quote_url' => $this->store->getBaseUrl() . 'atlaspay/payment/recreate',
                ]
                ],
            'config' => $config
        ];
    }

    public function getStore() {
        return $this->store;
    }

    /**
     * Get secret key for webhook process
     *
     * @return array
     */
    public function getSecretKeyArray(){
        $data = ["live" => $this->method->getConfigData('live_secret_key')];
        if ($this->method->getConfigData('test_mode')) {
            $data = ["test" => $this->method->getConfigData('test_secret_key')];
        }

        return $data;
    }

    public function getPublicKey(){
        $publicKey = $this->method->getConfigData('live_public_key');
        if ($this->method->getConfigData('test_mode')) {
            $publicKey = $this->method->getConfigData('test_public_key');
        }
        return $publicKey;
    }


}
