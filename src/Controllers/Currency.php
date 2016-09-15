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

    public function add(Request $request, Response $response, $args) {
        $params = $request->getParams();
        $errors = [];
        if (!$params['id']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        if (!$params['bought']) {
            $this->_flash->addMessage('bought', 'How much did you buy?');
        }

        if (!$params['spent']) {
            $this->_flash->addMessage('spent', 'How much did you spend?');
        }

        if (!count($this->_flash->getMessages())) {
            $date = date('Y-m-d H:i:s');
            $data = [
                'currency_id'   => $params['id'],
                'bought'        => $params['bought'],
                'spent'         => $params['spent'],
                'created_at'    => $date,
                'updated_at'    => $date
            ];

            $res = $this->getModel('\Models\Txn')->save($data);

            if ($res) {
                $this->_flash->addMessage('success', 'Transaction saved!');
            }
        }
        return $response->withRedirect('/currency/view/' . $params['id']);
    }

}