<?php

namespace Models;

class Base
{

    protected $_db;
    protected $_tableName;

    public function __construct()
    {
        // todo set in config
        $dsn = 'sqlite:/var/www/app/db/db.sqlite';
        $this->_db = new \Slim\PDO\Database($dsn);
    }

    public function selectAll() {

        $stmt = $this->_db->select()->from($this->_tableName);
        $data = $stmt->execute()->fetchAll();

        return $data;
    }

    public function selectById($id) {

        $data = $this->selectByColumn('id', $id);

        return $data;
    }

    public function selectByColumn($column, $value) {
        $stmt = $this->getSelectByAllStmt($column, $value);
        $data = $stmt->execute()->fetch();

        return $data;
    }

    protected function getSelectByAllStmt($column, $value) {
        $stmt = $this->_db->select()->from($this->_tableName)->where($column, '=', $value);
        return $stmt;
    }

    public function selectAllByColumn($column, $value) {
        $stmt = $this->getSelectByAllStmt($column, $value);
        $data = $stmt->execute()->fetchAll();

        return $data;
    }

    public function save($data) {
        $stmt = $this->_db->insert($data)->into($this->_tableName);
        $data = $stmt->execute();

        return $data;
    }

    public function update($data, $cond) {
        $stmt = $this->_db->update($data)->table($this->_tableName)->where($cond['col'], $cond['op'], $cond['val']);
        $data = $stmt->execute();

        return $data;
    }

    public function getDeleteByColumnStmt($column, $id) {
        $stmt = $this->_db->delete()->from($this->_tableName)->where($column, '=', $id);
        return $stmt;
    }

    public function deleteById($id) {
        $stmt = $this->getDeleteByColumnStmt('id', $id);
        $data = $stmt->execute();

        return $data;
    }

}