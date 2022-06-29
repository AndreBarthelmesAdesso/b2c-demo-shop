<?php

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerEco\Yves\Computop\CheckoutPage\Process\Steps\ComputopCreditCardInitStep;
use SprykerEco\Yves\Computop\CheckoutPage\Process\Steps\ComputopEasyCreditInitStep;
use SprykerEco\Yves\Computop\CheckoutPage\Process\Steps\ComputopPayNowInitStep;
use SprykerEco\Yves\Computop\CheckoutPage\Process\Steps\ComputopPayPalExpressCompleteStep;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin as SprykerShopCheckoutPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerStepFactory;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFactory extends SprykerStepFactory
{
    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_NAME_HOME
     */
    protected const ROUTE_NAME_HOME = 'home';

    /**
     * @uses \SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin::ROUTE_NAME_PAY_PAL_EXPRESS_COMPLETE
     *
     * @var string
     */
    protected const ROUTE_NAME_COMPUTOP_PAYPAL_EXPRESS_COMPLETE = 'computop-pay-pal-express-complete';

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function getSteps(): array
    {
        return [
            $this->createEntryStep(),
            $this->createCustomerStep(),
            $this->createAddressStep(),
            $this->createShipmentStep(),
            $this->createPaymentStep(),
            //$this->createComputopEasyCreditInitStep(),
            $this->createSummaryStep(),
            $this->createPlaceOrderStep(),
            $this->createComputopCreditCardInitStep(),
            $this->createComputopPayNowInitStep(),
            $this->createSuccessStep(),
            $this->createErrorStep(),
        ];
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createPlaceOrderStep(): StepInterface
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessenger(),
            $this->getLocaleClient()->getCurrentLocale(),
            $this->getGlossaryStorageClient(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER,
            $this->getConfig()->getEscapeRoute(),
            [
                static::ERROR_CODE_GENERAL_FAILURE => static::ROUTE_CART,
                'payment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
                'shipment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            ]
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    public function createPaymentStep(): StepInterface
    {
        return new PaymentStep(
            $this->getPaymentClient(),
            $this->getPaymentMethodHandler(),
            SprykerShopCheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getFlashMessenger(),
            $this->getCalculationClient(),
            $this->getCheckoutPaymentStepEnterPreCheckPlugins(),
            $this->createPaymentMethodKeyExtractor(),
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createSummaryStep(): StepInterface
    {
        return new SummaryStep(
            $this->getProductBundleClient(),
            $this->getShipmentService(),
            $this->getConfig(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep
     */
    public function createShipmentStep(): StepInterface
    {
        return new ShipmentStep(
            $this->getCalculationClient(),
            $this->getShipmentPlugins(),
            $this->createShipmentStepPostConditionChecker(),
            $this->createGiftCardItemsChecker(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutShipmentStepEnterPreCheckPlugins(),
            $this->getComputopClient()
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createComputopCreditCardInitStep(): StepInterface
    {
        return new ComputopCreditCardInitStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_COMPUTOP_CREDIT_CARD_INIT,
            static::ROUTE_NAME_HOME
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createComputopPayNowInitStep(): StepInterface
    {
        return new ComputopPayNowInitStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_COMPUTOP_PAY_NOW_INIT,
            static::ROUTE_NAME_HOME
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createComputopEasyCreditInitStep(): StepInterface
    {
        return new ComputopEasyCreditInitStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_COMPUTOP_EASY_CREDIT_INIT,
            static::ROUTE_NAME_HOME
        );
    }

    /**
     * @return \SprykerEco\Client\Computop\ComputopClientInterface
     */
    public function getComputopClient(): ComputopClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_COMPUTOP);
    }

    /**
     * @return \SprykerEco\Yves\Computop\CheckoutPage\Process\Steps\ComputopPayPalExpressCompleteStep
     */
    public function createComputopPayPalExpressCompleteStep(): ComputopPayPalExpressCompleteStep
    {
        return new ComputopPayPalExpressCompleteStep(
            static::ROUTE_NAME_COMPUTOP_PAYPAL_EXPRESS_COMPLETE,
            static::ROUTE_NAME_HOME
        );
    }
}
