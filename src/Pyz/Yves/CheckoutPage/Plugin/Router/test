<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin as SprykerShopCheckoutPageRouteProviderPlugin;

class CheckoutPageRouteProviderPlugin extends SprykerShopCheckoutPageRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CHECKOUT_COMPUTOP_CREDIT_CARD_INIT = 'checkout-computop-credit-card-init';
    /**
     * @var string
     */
    public const ROUTE_NAME_CHECKOUT_COMPUTOP_EASY_CREDIT_INIT = 'checkout-computop-easy-credit-init';
    /**
     * @var string
     */
    public const ROUTE_NAME_CHECKOUT_COMPUTOP_PAY_NOW_INIT = 'checkout-computop-pay-now-init';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = parent::addRoutes($routeCollection);
        $routeCollection = $this->addComputopCreditCardInitRoute($routeCollection);
        $routeCollection = $this->addComputopPayNowInitRoute($routeCollection);
        $routeCollection = $this->addComputopEasyCreditInitRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addComputopCreditCardInitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/checkout/computop-credit-card-init',
            'CheckoutPage',
            'Checkout',
            'computopCreditCardInitAction'
        );
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_COMPUTOP_CREDIT_CARD_INIT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addComputopPayNowInitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/checkout/computop-pay-now-init',
            'CheckoutPage',
            'Checkout',
            'computopPayNowInitAction'
        );
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_COMPUTOP_PAY_NOW_INIT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addComputopEasyCreditInitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/checkout/computop-easy-credit-init',
            'CheckoutPage',
            'Checkout',
            'computopEasyCreditInitAction'
        );
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_COMPUTOP_EASY_CREDIT_INIT, $route);

        return $routeCollection;
    }
}
