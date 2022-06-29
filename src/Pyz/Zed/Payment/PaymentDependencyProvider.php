<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment;

use Spryker\Zed\GiftCard\Communication\Plugin\GiftCardPaymentMethodFilterPlugin;
use Spryker\Zed\Nopayment\Communication\Plugin\Payment\PriceToPayPaymentMethodFilterPlugin;
use Spryker\Zed\OauthClient\Communication\Plugin\Payment\AccessTokenPaymentAuthorizeRequestExpanderPlugin;
use Spryker\Zed\Payment\PaymentDependencyProvider as SprykerPaymentDependencyProvider;
use SprykerEco\Zed\Computop\Communication\Plugin\ComputopPaymentMethodFilterPlugin;
use SprykerEco\Zed\Computop\Communication\Plugin\Payment\ComputopCurrencyPaymentMethodFilterPlugin;

class PaymentDependencyProvider extends SprykerPaymentDependencyProvider
{
    /**
     * @return \Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins(): array
    {
        return [
            new PriceToPayPaymentMethodFilterPlugin(),
            new GiftCardPaymentMethodFilterPlugin(),
            new ComputopCurrencyPaymentMethodFilterPlugin(),
            new ComputopPaymentMethodFilterPlugin(),
        ];
    }

    /**
     * @return array<int, \Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentAuthorizeRequestExpanderPluginInterface>
     */
    protected function getPaymentAuthorizeRequestExpanderPlugins(): array
    {
        return [
            new AccessTokenPaymentAuthorizeRequestExpanderPlugin(),
        ];
    }
}
