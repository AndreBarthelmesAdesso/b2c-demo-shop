<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutWidget;

use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CheckoutWidget\CheckoutWidgetDependencyProvider as SprykerCheckoutWidgetDependencyProvider;

class CheckoutWidgetDependencyProvider extends SprykerCheckoutWidgetDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_COMPUTOP = 'CLIENT_COMPUTOP';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);

        $container = $this->addComputopClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addComputopClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPUTOP, function (Container $container) {
            return $container->getLocator()->computop()->client();
        });

        return $container;
    }
}
