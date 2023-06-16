<?php

$container = \System\Container::getInstace();
\App\Models\Task::setDb($container->get("db"));
\App\Models\Task::setQueryBuilder($container->get("queryBuilder"));