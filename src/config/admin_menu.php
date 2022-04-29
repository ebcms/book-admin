<?php

use DiggPHP\Framework\Framework;
use DiggPHP\Router\Router;

return Framework::execute(function (
    Router $router
): array {
    $res = [];
    $res[] = [
        'title' => '书籍管理',
        'url' => $router->build('/ebcms/book-admin/book/index'),
        'tags' => ['primary']
    ];
    return $res;
});
