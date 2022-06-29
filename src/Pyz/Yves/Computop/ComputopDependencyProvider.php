<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Computop;

use SprykerEco\Yves\Computop\ComputopDependencyProvider as SprykerComputopDependencyProvider;
use SprykerEco\Yves\ComputopShipment\Plugin\Computop\ExpandShipmentPayPalExpressInitPlugin;

class ComputopDependencyProvider extends SprykerComputopDependencyProvider
{
    /**
     * @return array<ExpandShipmentPayPalExpressInitPlugin>
     */
    public function getPayPalExpressInitPlugins(): array
    {
        return [
            new ExpandShipmentPayPalExpressInitPlugin(),
        ];
    }
}
