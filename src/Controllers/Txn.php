<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class Txn extends Base
{

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
        $messages = [];

        $bought = (float) $params['bought'];
        $spent = (float) $params['spent'];

        if (!$args['cid']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        if (!$params['id']) {
            $this->_flash->addMessage('id','Id is missing');
        }

        if ($bought == null || !$this->isNumber($bought) || !$bought > 0) {
            $this->_flash->addMessage('bought', 'How much did you buy?');
        }

        if ($spent == null || !$this->isNumber($spent) || !$spent > 0) {
            $this->_flash->addMessage('spent', 'How much did you spend?');
        }

        if (!count($this->_flash->getMessages())) {
            $date = date('Y-m-d H:i:s');
            $data = [
                'bought'        => $params['bought'],
                'spent'         => $params['spent'],
                'updated_at'    => $date
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