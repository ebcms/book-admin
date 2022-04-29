<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use Ebcms\Database\Model;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function post(
        Request $request,
        Db $db
    ) {
        if ($post = $db->get('ebcms_book_post', '*', [
            'id' => $request->post('id', 0, ['intval']),
        ])) {
            $this->delete($db, [$post['id']]);

            $posts = $db->select('ebcms_book_post', '*', [
                'book_id' => $post['book_id'],
                'pid' => $post['pid'],
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

            $db->update('ebcms_book_book', [
                'update_time' => time(),
            ], [
                'id' => $request->post('id'),
            ]);
        }
        return $this->success('操作成功！');
    }

    private function delete(Db $db, array $ids)
    {
        foreach ($ids as $pid) {
            if ($subid = $db->select('ebcms_book_post', 'id', [
                'pid' => $pid
            ])) {
                $this->delete($db, $subid);
            }
        }
        $db->delete('ebcms_book_post', [
            'id' => $ids,
        ]);
    }
}
