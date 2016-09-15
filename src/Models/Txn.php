<?php

namespace Models;


class Txn extends Base
{
    protected $_tableName = 'txn';

    public function saveTxn($data) {
        $this->_db->insert($data);
    }

}