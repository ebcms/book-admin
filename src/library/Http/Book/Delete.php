<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Book;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function post(
        Request $request,
        Db $db
    ) {
        $db->delete('ebcms_book_post', [
            'book_id' => $request->post('id'),
        ]);
        $db->delete('ebcms_book_book', [
            'id' => $request->post('id'),
        ]);
        return $this->success('操作成功！');
    }
}
