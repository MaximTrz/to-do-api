<?php


namespace System\Contracts;

interface API extends HasActions
{
    public function actionGet($id = null);
    public function actionPut($id);
    public function actionDelete($id);
    public function actionPost();
}