<?php

use DiggPHP\Database\Db;
use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return Framework::execute(function (
    Router $router,
    Db $db
): array {
    $res = [];

    if (!$db->count('ebcms_book_post', [
        'update_time[>]' => strtotime(date('Y-m-d 00:00:01'))
    ])) {
        $res[] = [
            'title' => '工作提醒',
            'body' => '今日尚未<a href="' . $router->build('/ebcms/book-admin/book/index') . '" class="mx-1 fw-bold">发布</a>内容哦~',
            'tags' => ['remind']
        ];
    }
    if ($count = $db->count('ebcms_book_post', [
        'state' => 2
    ])) {
        $res[] = [
            'title' => '内容审核',
            'body' => '有<a href="' . $router->build('/ebcms/book-admin/book/index', ['state' => 2]) . '" class="mx-1 fw-bold">' . $count . '</a>篇内容待审核',
            'tags' => ['remind']
        ];
    }
    $res[] = [
        'title' => '书籍信息',
        'url' => $router->build('/ebcms/book-admin/book/index'),
        'tags' => ['info'],
        'body' => '共有<a href="' . $router->build('/ebcms/book-admin/book/index') . '" class="mx-1 fw-bold">' . $db->count('ebcms_book_book') . '</a>本，文章<a href="' . $router->build('/ebcms/book-admin/book/index', ['state' => 2]) . '" class="mx-1 fw-bold">' . $db->count('ebcms_book_post') . '</a>篇',
    ];
    return $res;
});
