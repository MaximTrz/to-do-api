<?php


namespace System\Controllers;


use System\Contracts\API;
use System\Contracts\HasQueryResult;

class ControllerAPI extends Controller implements API
{

    public function actionGet($id = null)
    {
        $result = !isset($id) ? $this->model::findAll() : $this->model::findById($id);
        http_response_code(200);
        echo $this->view->JSON($result);

    }

    public function actionPut($id)
    {
        $obj = $this->model->findById($id);

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        $obj = $this->fillObj($obj, $data);
        $res = $obj->save();

        $this->sentResult($res);

    }

    public function actionDelete($id)
    {
        $obj = $this->model::findById($id);
        if (empty($obk)){
            $result = $this->model->delete();
            $this->sentResult($result);
        }
    }

    public function actionPost()
    {
        $this->model = $this->fillObj($this->model, $_POST);
        $result = $this->model->save();
        $this->sentResult($result);
    }

    public function fillObj(object $obj, array $arr): object
    {

        foreach ($arr as $key => $value){
            if (property_exists($obj, $key)==true){
                $obj->$key = htmlspecialchars($arr[$key]);
            }
        }

        return $obj;
    }

    private function sentResult(HasQueryResult $result)
    {
        if  ($result->getQueryResult()){
            http_response_code(200);
            echo $this->view->JSON($result->getId());
        } else {
            http_response_code(500);
        }
    }

    public function getModel()
    {
        return $this->model;
    }

}