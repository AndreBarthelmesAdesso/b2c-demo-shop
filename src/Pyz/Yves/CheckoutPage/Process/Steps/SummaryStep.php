<?php

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep as SprykerShopSummaryStep;

class SummaryStep extends SprykerShopSummaryStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $isPlaceableOrderResponseTransfer = $this->checkoutClient->isPlaceableOrder($quoteTransfer);

        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleClient->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            ),
            'shipmentGroups' => $this->expandShipmentGroupsWithCartItems($shipmentGroups, $quoteTransfer),
            'totalCosts' => $this->getShipmentTotalCosts($shipmentGroups, $quoteTransfer),
            'isPlaceableOrder' => $isPlaceableOrderResponseTransfer->getIsSuccess(),
            'isPlaceableOrderErrors' => $isPlaceableOrderResponseTransfer->getErrors(),
            'shipmentExpenses' => $this->getShipmentExpenses($quoteTransfer),
            'acceptTermsFieldName' => QuoteTransfer::ACCEPT_TERMS_AND_CONDITIONS,
            'additionalData' => $this->getAdditionalData($quoteTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAdditionalData(QuoteTransfer $quoteTransfer): array
    {
        if ($quoteTransfer->getPayment()->getPaymentSelection() !== PaymentTransfer::COMPUTOP_EASY_CREDIT) {
            return [];
        }

        $easyCreditStatusResponse = $quoteTransfer->getPayment()
            ->getComputopEasyCredit()
            ->getEasyCreditStatusResponse();

        $financing = $easyCreditStatusResponse->getFinancingData();
        $process = $easyCreditStatusResponse->getProcessData();

        return [
            'installmentPlanMoney' => [
                'Kaufbetrag' => (int)round($financing['finanzierung']['bestellwert'] * 100),
                '+ Zinsen' => (int)round($financing['ratenplan']['zinsen']['anfallendeZinsen'] * 100),
                '= Gesamtbetrag' => (int)round($financing['ratenplan']['gesamtsumme'] * 100),
                'Ihre monatliche Rate' => (int)round($financing['ratenplan']['zahlungsplan']['betragRate'] * 100),
                'letzte Rate' => (int)round($financing['ratenplan']['zahlungsplan']['betragLetzteRate'] * 100),
            ],
            'installmentPlanTax' => [
                'Sollzinssatz p.a. fest für die gesamte Laufzeit' => $financing['ratenplan']['zinsen']['nominalzins'],
                'effektiver Jahreszins' => $financing['ratenplan']['zinsen']['effektivzins'],
            ],
            'installmentText' => $financing['tilgungsplanText'],
            'installmentLink' => $process['allgemeineVorgangsdaten']['urlVorvertraglicheInformationen'],
        ];
    }
}
