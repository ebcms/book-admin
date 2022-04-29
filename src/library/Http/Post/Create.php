<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Create extends Common
{
    public function post(
        Request $request,
        Db $db
    ) {
        $data = [
            'book_id' => $request->post('book_id'),
            'pid' => $request->post('pid'),
            'type' => $request->post('type', 1, ['intval']),
            'title' => $request->post('title'),
            'state' => $request->post('state', 2, ['intval']),
            'create_time' => time(),
            'update_time' => time(),
        ];
        $db->insert('ebcms_book_post', $data);
        $db->update('ebcms_book_book', [
            'update_time' => time(),
        ], [
            'id' => $request->post('book_id'),
        ]);

        $posts = $db->select('ebcms_book_post', '*', [
            'book_id' => $data['book_id'],
            'pid' => $data['pid'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        $count = count($posts);
        foreach ($posts as $key => $vo) {
            if ($vo['rank'] != ($count - $key - 1)) {
                $db->update('ebcms_book_post', [
                    'rank' => ($count - $key - 1),
                ], [
                    'id' => $vo['id'],
                ]);
            }
        }

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
