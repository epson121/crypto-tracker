<?php

namespace Helpers;

class Data
{
    protected $_currencyFormatter;

    public function calculatePricePerBtc($spent, $received) {
        $price = (float) $spent / $received;
        return $price;
    }

    public function formatCurrency($value) {
        $numberFormatter = \NumberFormatter::create('en_US', \NumberFormatter::CURRENCY);
        return $numberFormatter->format($value);
    }

    public function formatDate($date) {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * @param $txns
     */
    public function getTxnsTotal($txns) {

        $countBought = 0;
        $countSold = 0;
        $totalAmount = 0;
        $totalValue = 0;
        $totalPricePerBtcBought = 0;
        $totalPricePerBtcSold = 0;

        foreach ($txns as $txn) {
            if ($txn['type'] == 1) {
                $totalAmount += $txn['amount'];
                $totalValue -= $txn['value'];
                $totalPricePerBtcBought += $this->calculatePricePerBtc($txn['amount'], $txn['value']);
                $countBought += 1;
            } else {
                $totalAmount -= $txn['amount'];
                $totalValue += $txn['value'];
                $totalPricePerBtcSold += $this->calculatePricePerBtc($txn['amount'], $txn['value']);
                $countSold += 1;
            }
        }

        return [
            'total_amount'  => $totalAmount,
            'total_value'   => $totalValue,
            'total_price_per_btc_bought'   => (float) ($totalPricePerBtcBought / $countBought),
            'total_price_per_btc_sold'   => (float) ($totalPricePerBtcSold / $countSold)
        ];
    }

}