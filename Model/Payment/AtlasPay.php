<?php
/**
 * AtlasPay Magento2 Module using \Magento\Payment\Model\Method\AbstractMethod
 * Copyright (C) 2019 AtlasPay.com
 *
 * This file is part of Raven/AtlasPay.
 *
 * Raven/AtlasPay is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Raven\AtlasPay\Model\Payment;

/**
 * AtlasPay main payment method model
 *
 * @author Olayode Ezekiel <kielsoft@gmail.com>
 */
class AtlasPay extends \Magento\Payment\Model\Method\AbstractMethod
{

    const CODE = 'raven_atlaspay';

    protected $_code = self::CODE;
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
