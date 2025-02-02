<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\Router;

use Spryker\Glue\GlueApplication\Plugin\Rest\GlueRouterPlugin;
use Spryker\Glue\Router\RouterDependencyProvider as SprykerRouterDependencyProvider;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class RouterDependencyProvider extends SprykerRouterDependencyProvider
{
    /**
     * @return array
     */
    protected function getRouterPlugins(): array
    {
        return [
            new GlueRouterPlugin(),
            new ComputopRouteProviderPlugin(),
        ];
    }
}
