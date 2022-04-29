<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Rank extends Common
{
    public function post(
        Request $request,
        Db $db
    ) {
        if (!$post = $db->get('ebcms_book_post', '*', [
            'id' => $request->post('id', 0),
        ])) {
            return $this->error('不存在!');
        }
        if ($request->post('type', 'down') == 'down') {
            if ($smallid = $db->get('ebcms_book_post', 'id', [
                'book_id' => $post['book_id'],
                'pid' => $post['pid'],
                'rank[<]' => $post['rank'],
                'ORDER' => [
                    'rank' => 'DESC',
                ],
            ])) {
                $db->update('ebcms_book_post', [
                    'rank[+]' => 1,
                ], [
                    'id' => $smallid,
                ]);
                $db->update('ebcms_book_post', [
                    'rank[-]' => 1,
                ], [
                    'id' => $post['id'],
                ]);
            }
        } else {
            if ($bigid = $db->get('ebcms_book_post', 'id', [
                'book_id' => $post['book_id'],
                'pid' => $post['pid'],
                'rank[>]' => $post['rank'],
                'ORDER' => [
                    'rank' => 'ASC',
                ],
            ])) {
                $db->update('ebcms_book_post', [
                    'rank[-]' => 1,
                ], [
                    'id' => $bigid,
                ]);
                $db->update('ebcms_book_post', [
                    'rank[+]' => 1,
                ], [
                    'id' => $post['id'],
                ]);
            }
        }
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
