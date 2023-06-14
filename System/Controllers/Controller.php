<?php


namespace System\Controllers;




use System\Contracts\HasActions;
use System\Models\Model;
use System\Views\View;


abstract class Controller implements HasActions
{

    protected $view;
    protected $model;

    public function __construct(Model $model, View $view)
    {
        $this->view = $view;
        $this->model = $model;
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