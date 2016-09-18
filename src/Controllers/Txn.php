<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class Txn extends Base
{

    public function add(Request $request, Response $response, $args) {
        $params = $request->getParams();
        if (!$params['id']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        $amount = (float) $params['amount'];
        $value = (float) $params['value'];

        if (!$params['txn_type']) {
            $this->_flash->addMessage('txn_type', 'Transaction type is missing');
        }

        if ($amount == null || !$this->isNumber($amount) || !$amount > 0) {
            $this->_flash->addMessage('bought', 'How much did you buy?');
        }

        if ($value == null || !$this->isNumber($value) || !$value > 0) {
            $this->_flash->addMessage('spent', 'How much did you spend?');
        }

        if (!$params['txn_date']) {
            $this->_flash->addMessage('txn_date', 'What was the date?');
        }

        if (!count($this->_flash->getMessages())) {
            $nowDate = date('Y-m-d H:i:s');
            $date = date('Y-m-d H:i:s', strtotime($params['txn_date']));
            $data = [
                'type'          => $params['txn_type'],
                'currency_id'   => $params['id'],
                'amount'        => $params['amount'],
                'value'         => $params['value'],
                'date'          => $params['txn_date'],
                'created_at'    => $nowDate,
                'updated_at'    => $nowDate
            ];

            $res = $this->getModel('\Models\Txn')->save($data);

            if ($res) {
                $this->_flash->addMessage('success', 'Transaction saved!');
            }
        }
        return $response->withRedirect('/currency/view/' . $params['id']);
    }

    public function edit(Request $request, Response $response, $args) {
        $id = $args['id'];
        if (!$id) {
            return $response->withRedirect('/currency');
        } else {
            $txn = $this->getModel('\Models\Txn')->selectById($id);
            if ($txn) {
                $this->_view->setAttributes([
                    'txn'   => $txn
                ]);
                $this->render($response, 'txn/edit.phtml');
            } else {
                // todo render error?
            }
        }
    }

    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];
        $cid = $args['cid'];
        if (!$id || !$cid) {
            return $response->withRedirect('/currency');
        } else {
            $this->getModel('\Models\Txn')->deleteById($id);
            return $response->withRedirect('/currency/view/' . $cid);
        }
    }

    public function editPost(Request $request, Response $response, $args) {
        $cid = $args['cid'];
        $params = $request->getParams();

        $amount = (float) $params['amount'];
        $value = (float) $params['value'];

        if (!$args['cid']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        if (!$params['id']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        if (!$params['txn_type']) {
            $this->_flash->addMessage('txn_type', 'Transaction type is missing');
        }

        if ($amount == null || !$this->isNumber($amount) || !$amount > 0) {
            $this->_flash->addMessage('bought', 'How much did you buy?');
        }

        if ($value == null || !$this->isNumber($value) || !$value > 0) {
            $this->_flash->addMessage('spent', 'How much did you spend?');
        }

        if (!$params['txn_date']) {
            $this->_flash->addMessage('txn_date', 'What was the date?');
        }


        if (!count($this->_flash->getMessages())) {
            $nowDate = date('Y-m-d H:i:s');
            $date = date('Y-m-d H:i:s', strtotime($params['txn_date']));
            $data = [
                'type'          => $params['txn_type'],
                'amount'        => $params['amount'],
                'value'         => $params['value'],
                'date'          => $date,
                'updated_at'    => $nowDate
            ];

            $cond = [
                'col'   => 'id',
                'op'    => '=',
                'val'   => $params['id']
            ];

            $this->getModel('\Models\Txn')->update($data, $cond);
        }

        return $response->withRedirect('/currency/view/' . $cid);
    }

    public function isNumber($value) {
        return is_int($value) || is_float($value);
    }

}