<?php


namespace App\Controllers;
use App\Controllers\Interfaces\HasActions;
use App\Exceptions\PageNotFound;
use App\Views\View;


abstract class Controller implements HasActions
{

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * @param $action string имя Action
     * @param array $params массив с параметрами необходимыми для работы Action
     * @throws PageNotFound если подходящего Action не нащлось бросаем ошибку "страница не найдена"
     * Запускает переданный Action
     */
    public function action($action, $params = [])
    {
        $actionName = 'action' . $action;

        if (method_exists($this, $actionName)) {

            $this->$actionName($params);

        } else {
            throw new PageNotFound();
        }
    }

}