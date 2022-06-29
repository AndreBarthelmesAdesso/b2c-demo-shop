<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep as SprykerShopPaymentStep;

class PaymentStep extends SprykerShopPaymentStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        return $this->isQuoteContainsPayPalExpressPayment($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if ($this->isQuoteContainsPayPalExpressPayment($quoteTransfer)) {
            return false;
        }

        return parent::requireInput($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteContainsPayPalExpressPayment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getPayment() !== null && $quoteTransfer->getPaymentOrFail()->getComputopPayPalExpress() !== null;
    }
}
