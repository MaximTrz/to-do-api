<?php

$container = System\Container::getInstace();

$router = new \Bramus\Router\Router();

// Создаем фабрику контроллеров
$controllerFactory = function () use ($container) {
    return $container->get("controller.task");
};

// Обработчики маршрутов
$router->get("/api/tasks/", function () use ($controllerFactory) {
    $controller = $controllerFactory()->action("get");
});

$router->get("api/task/{id}", function ($id) use ($container, $controllerFactory) {
    if (!is_numeric($id)) {
        http_response_code(400);
        throw new Exception("Неправильный идентификатор задачи");
    }
    $controller = $controllerFactory()->action("get", intval($id));
});

$router->post("api/task/", function () use ($controllerFactory) {
    $controller = $controllerFactory()->action("post");
});

$router->delete("api/task/{id}", function ($id) use ($container, $controllerFactory) {
    if (!is_numeric($id)) {
        http_response_code(400);
        throw new Exception("Неправильный идентификатор задачи");
    }
    $controller = $controllerFactory();
    $controller->getModel()->id = intval($id);
    $controller->action("delete", $id);
});

$router->put("api/task/{id}", function ($id) use ($container, $controllerFactory) {
    if (!is_numeric($id)) {
        http_response_code(400);
        throw new Exception("Неправильный идентификатор задачи");
    }
    $controller = $controllerFactory()->action("put", intval($id));
});

// Обработка 404
$router->set404(function() {
    throw new \System\Exceptions\RouteException("Страница не найдена");
});

$router->run();
