<?php

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Client\Computop\ComputopClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep as SprykerShipmentStep;
use Symfony\Component\HttpFoundation\Request;

class ShipmentStep extends SprykerShipmentStep
{
    /**
     * @var \SprykerEco\Client\Computop\ComputopClientInterface
     */
    protected $computopClient;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface $calculationClient
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection $shipmentPlugins
     * @param \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface $postConditionChecker
     * @param \SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface $giftCardItemsChecker
     * @param string $stepRoute
     * @param string|null $escapeRoute
     * @param \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface[] $checkoutShipmentStepEnterPreCheckPlugins
     * @param \SprykerEco\Client\Computop\ComputopClientInterface $computopClient
     */
    public function __construct(
        CheckoutPageToCalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        PostConditionCheckerInterface $postConditionChecker,
        GiftCardItemsCheckerInterface $giftCardItemsChecker,
                                                 $stepRoute,
                                                 $escapeRoute,
        array $checkoutShipmentStepEnterPreCheckPlugins,
        ComputopClientInterface $computopClient
    ) {
        parent::__construct(
            $calculationClient,
            $shipmentPlugins,
            $postConditionChecker,
            $giftCardItemsChecker,
            $stepRoute,
            $escapeRoute,
            $checkoutShipmentStepEnterPreCheckPlugins
        );

        $this->computopClient = $computopClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::execute($request, $quoteTransfer);

        return $this->computopClient->performCrifApiCall($quoteTransfer);
    }
}
