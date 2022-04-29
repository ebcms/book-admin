<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Move extends Common
{
    public function post(
        Request $request,
        Db $db
    ) {
        if ($ids = $request->post('ids')) {
            $pid = $request->post('pid', 0, ['intval']);
            if ($ids = array_merge(array_diff($ids, [$pid]))) {
                $db->update('ebcms_book_post', [
                    'pid' => $request->post('pid'),
                ], [
                    'id' => $request->post('ids'),
                ]);
                if (!$this->hasTop($db, $pid)) {
                    $db->update('ebcms_book_post', [
                        'pid' => 0,
                    ], [
                        'id' => $pid,
                    ]);
                }

                $posts = $db->select('ebcms_book_post', '*', [
                    'book_id' => $db->get('ebcms_book_post', 'book_id', [
                        'id' => $ids
                    ]),
                    'pid' => $pid,
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
            }
        }
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }

    private function hasTop(Db $db, $id, $has = [])
    {
        $has[] = $id;
        $pid = $db->get('ebcms_book_post', 'pid', [
            'id' => $id,
        ]);
        if ($pid == 0) {
            return true;
        } elseif (in_array($pid, $has)) {
            return false;
        } else {
            return $this->hasTop($db, $pid, $has);
        }
    }
}
