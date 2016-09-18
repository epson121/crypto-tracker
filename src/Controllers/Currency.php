<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;


class Currency extends Base
{

    protected $_apis = [
        '1'   => 'https://api.cryptonator.com/api/ticker/btc-usd',
        '2'   => 'https://api.cryptonator.com/api/ticker/ltc-usd'
    ];

    public function view(Request $request, Response $response, $args)  {
        $currencyModel = $this->getModel('\Models\Currency');
        $data = $currencyModel->selectAll();
        $this->_view->setAttributes([
            'items' => $data
        ]);
        $this->render($response, 'currency/list.phtml');
    }

    public function viewId(Request $request, Response $response, $args) {
        $currencyModel = $this->getModel('\Models\Currency');
        $currencyData = $currencyModel->selectById($args['id']);

        $txnModel = $this->getModel('\Models\Txn');
        $txnData = $txnModel->selectAllByColumn('currency_id', $args['id']);

        $tickerData = $this->getApiModel($this->_apis[$args['id']])->call();

        $this->_view->setAttributes([
            'item'      => $currencyData,
            'txns'      => $txnData,
            'ticker'    => $tickerData
        ]);
        $this->render($response, 'currency/item.phtml');
    }

}