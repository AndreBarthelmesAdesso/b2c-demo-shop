<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Quote;

use Spryker\Client\Kernel\Container;
use Spryker\Client\Price\Plugin\PriceModeQuoteTransferExpanderPlugin;
use Spryker\Client\Quote\QuoteDependencyProvider as BaseQuoteDependencyProvider;
use Spryker\Client\Store\Plugin\StoreQuoteTransferExpanderPlugin;
use SprykerEco\Client\ComputopShipment\Quote\Dependency\Plugin\DefaultShippingMethodQuoteTransferExpanderPlugin;

class QuoteDependencyProvider extends BaseQuoteDependencyProvider
{
    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[]
     */
    protected function getQuoteTransferExpanderPlugins(Container $container)
    {
        return [
            new StoreQuoteTransferExpanderPlugin(),
            new PriceModeQuoteTransferExpanderPlugin(),
            new DefaultShippingMethodQuoteTransferExpanderPlugin(),
        ];
    }
}
