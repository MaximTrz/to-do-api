<?php


namespace App\Controllers\Api;


use System\Contracts\API;

class Task extends \System\Controllers\Controller implements API
{

    public function actionGet($id = null)
    {
        if(isset($id))
        {

            $obj = $this->model::findById($id);
            echo $this->view->JSON($obj);
        }
    }

    public function actionPut($id)
    {
        // TODO: Implement actionPut() method.
    }

    public function actionDelete($id)
    {
        // TODO: Implement actionDelete() method.
    }

    public function actionPost()
    {
        // TODO: Implement actionPost() method.
    }
}