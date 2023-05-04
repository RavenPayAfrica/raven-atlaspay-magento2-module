/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'raven_atlaspay',
                component: 'Raven_AtlasPay/js/view/payment/method-renderer/raven_atlaspay'
            }
        );

        /** Add view logic here if needed */

        return Component.extend({});
    }
);
