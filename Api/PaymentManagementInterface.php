<?php
namespace Raven\AtlasPay\Api;

/**
 * PaymentManagementInterface
 *
 * @api
 */
interface PaymentManagementInterface
{
    /**
     * @param string $reference
     * @return string
     */
    public function verifyPayment($reference);
}
