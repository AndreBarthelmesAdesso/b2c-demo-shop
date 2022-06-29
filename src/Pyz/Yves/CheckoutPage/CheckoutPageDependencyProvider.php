<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Plugin\CheckoutPage\PayuCeeSingleSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\ComputopPaymentHandlerPlugin;
use SprykerEco\Yves\Computop\Plugin\CreditCardSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\DirectDebitSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\EasyCreditSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\IdealSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\PaydirektSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\PayNowSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\PayPalSubFormPlugin;
use SprykerEco\Yves\Computop\Plugin\SofortSubFormPlugin;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider as SprykerShopCheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Plugin\StepEngine\PaymentForeignHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm;
use SprykerShop\Yves\CustomerPage\Form\CustomerCheckoutForm;
use SprykerShop\Yves\CustomerPage\Form\GuestForm;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use SprykerShop\Yves\CustomerPage\Form\RegisterForm;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CheckoutAddressFormDataProviderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CustomerAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerStepHandler;
use SprykerShop\Yves\PaymentPage\Plugin\PaymentPage\PaymentForeignPaymentCollectionExtenderPlugin;
use SprykerShop\Yves\SalesOrderThresholdWidget\Plugin\CheckoutPage\SalesOrderThresholdWidgetPlugin;
use Symfony\Component\Form\FormFactory;

class CheckoutPageDependencyProvider extends SprykerShopCheckoutPageDependencyProvider
{
    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     */
    protected const PYZ_SERVICE_FORM_FACTORY = 'form.factory';

    public const CLIENT_COMPUTOP = 'CLIENT_COMPUTOP';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->extendPyzPaymentMethodHandler($container);
        $container = $this->extendSubFormPluginCollection($container);
        $container = $this->addComputopClient($container);

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getSummaryPageWidgetPlugins(): array
    {
        return [
            SalesOrderThresholdWidgetPlugin::class, #SalesOrderThresholdFeature
        ];
    }

    /**
     * @phpstan-return array<int, class-string<\Symfony\Component\Form\FormTypeInterface>|\Symfony\Component\Form\FormInterface>
     *
     * @return \Symfony\Component\Form\FormTypeInterface[]|string[]
     */
    protected function getCustomerStepSubForms(): array
    {
        return [
            LoginForm::class,
            $this->getPyzCustomerCheckoutForm(RegisterForm::class, RegisterForm::BLOCK_PREFIX),
            $this->getPyzCustomerCheckoutForm(GuestForm::class, GuestForm::BLOCK_PREFIX),
        ];
    }

    /**
     * @param string $subForm
     * @param string $blockPrefix
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getPyzCustomerCheckoutForm($subForm, $blockPrefix)
    {
        return $this->getPyzFormFactory()->createNamed(
            $blockPrefix,
            CustomerCheckoutForm::class,
            null,
            [CustomerCheckoutForm::SUB_FORM_CUSTOMER => $subForm]
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getPyzFormFactory(): FormFactory
    {
        return (new GlobalContainer())->get(static::PYZ_SERVICE_FORM_FACTORY);
    }

    /**
     * @return string[]
     */
    protected function getAddressStepSubForms(): array
    {
        return [
            CheckoutAddressCollectionForm::class,
        ];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendPyzPaymentMethodHandler(Container $container): Container
    {
        $container->extend(static::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $paymentMethodHandler->add(new NopaymentHandlerPlugin(), NopaymentConfig::PAYMENT_PROVIDER_NAME);
            $paymentMethodHandler->add(new PaymentForeignHandlerPlugin(), PaymentTransfer::FOREIGN_PAYMENTS);
            // --- Computop
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_CREDIT_CARD);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_DIRECT_DEBIT);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_EASY_CREDIT);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_IDEAL);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_PAYDIREKT);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_PAY_NOW);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_PAY_PAL);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_PAY_PAL_EXPRESS);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_SOFORT);
            $paymentMethodHandler->add(new ComputopPaymentHandlerPlugin(), ComputopConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE);
            return $paymentMethodHandler;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function extendSubFormPluginCollection(Container $container): Container
    {
        $container->extend(static::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubFormPluginCollection) {
            // --- Computop
            $paymentSubFormPluginCollection->add(new CreditCardSubFormPlugin());
            $paymentSubFormPluginCollection->add(new DirectDebitSubFormPlugin());
            $paymentSubFormPluginCollection->add(new EasyCreditSubFormPlugin());
            $paymentSubFormPluginCollection->add(new IdealSubFormPlugin());
            $paymentSubFormPluginCollection->add(new PaydirektSubFormPlugin());
            $paymentSubFormPluginCollection->add(new PayNowSubFormPlugin());
            $paymentSubFormPluginCollection->add(new PayPalSubFormPlugin());
            $paymentSubFormPluginCollection->add(new SofortSubFormPlugin());
            $paymentSubFormPluginCollection->add(new PayuCeeSingleSubFormPlugin());

            return $paymentSubFormPluginCollection;
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[]
     */
    protected function getAddressStepExecutorAddressExpanderPlugins(): array
    {
        return [
            new CustomerAddressExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function getCheckoutAddressFormDataProviderPlugin(): StepEngineFormDataProviderInterface
    {
        return new CheckoutAddressFormDataProviderPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    protected function getCustomerStepHandler(): StepHandlerPluginInterface
    {
        return new CustomerStepHandler();
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\PaymentCollectionExtenderPluginInterface>
     */
    protected function getPaymentCollectionExtenderPlugins(): array
    {
        return [
            new PaymentForeignPaymentCollectionExtenderPlugin(),
        ];
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
