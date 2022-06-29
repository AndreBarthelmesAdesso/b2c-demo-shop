<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutWidget\Widget\ProceedToCheckoutButtonWidget as SprykerProceedToCheckoutButtonWidget;

/**
 * @method \Pyz\Yves\CheckoutWidget\CheckoutWidgetFactory getFactory()
 */
class ProceedToCheckoutButtonWidget extends SprykerProceedToCheckoutButtonWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_CLIENT_ID = 'clientId';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        parent::__construct($quoteTransfer);
        $this->addClientId();
    }

    /**
     * @return void
     */
    protected function addClientId(): void
    {
        $this->addParameter(
            static::PARAMETER_CLIENT_ID,
            $this->getFactory()->getComputopClient()->getPayPalExpressClientId()
        );
    }
}
