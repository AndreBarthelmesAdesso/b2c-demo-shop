<?php

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep as SprykerShopPlaceOrderStep;
use Symfony\Component\HttpFoundation\Request;

class PlaceOrderStep extends SprykerShopPlaceOrderStep
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = parent::execute($request, $quoteTransfer);

        if ($quoteTransfer->getPayment()->getPaymentSelection() !== ComputopConfig::PAYMENT_METHOD_PAY_NOW) {
            return $quoteTransfer;
        }

        $computopPaymentTransfer = $quoteTransfer->getPayment()->getComputopPayNow();
        $computopPaymentTransfer
            ->setData($this->checkoutResponseTransfer->getComputopInitPayment()->getData())
            ->setLen($this->checkoutResponseTransfer->getComputopInitPayment()->getLen());
        $quoteTransfer->getPayment()->setComputopPayNow($computopPaymentTransfer);

        return $quoteTransfer;
    }
}
