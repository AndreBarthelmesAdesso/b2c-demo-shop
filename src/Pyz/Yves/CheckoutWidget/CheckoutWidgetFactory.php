<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutWidget;

use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerShop\Yves\CheckoutWidget\CheckoutWidgetFactory as SprykerCheckoutWidgetFactory;

class CheckoutWidgetFactory extends SprykerCheckoutWidgetFactory
{
    /**
     * @return \SprykerEco\Client\Computop\ComputopClientInterface
     */
    public function getComputopClient(): ComputopClientInterface
    {
        return $this->getProvidedDependency(CheckoutWidgetDependencyProvider::CLIENT_COMPUTOP);
    }
}
