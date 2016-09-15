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

    /**
     * @param $txns
     */
    public function getTxnsTotal($txns) {

        $count = count($txns);
        $totalBought = 0;
        $totalSpent = 0;
        $totalPricePerBtc = 0;

        foreach ($txns as $txn) {
            $totalBought += $txn['bought'];
            $totalSpent += $txn['spent'];
            $totalPricePerBtc += $this->calculatePricePerBtc($txn['spent'], $txn['bought']);
        }

        return [
            'total_bought'  => $totalBought,
            'total_spent'   => $totalSpent,
            'total_price_per_btc'   => (float) ($totalPricePerBtc / $count)
        ];
    }

}