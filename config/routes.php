<?php

$app->get('/', 'Controllers\Currency:view');
$app->get('/currency', 'Controllers\Currency:view');
$app->get('/currency/view/{id}', 'Controllers\Currency:viewId');
$app->get('/currency/add', 'Controllers\Currency:add');
$app->post('/currency/add/transaction', 'Controllers\Txn:add');
$app->get('/currency/txn/edit/{id}', 'Controllers\Txn:edit');
$app->get('/currency/txn/delete/{id}/{cid}', 'Controllers\Txn:delete');
$app->post('/currency/txn/update/{id}/{cid}', 'Controllers\Txn:editPost');