<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Db $db,
        Request $request
    ) {
        return $this->html($template->renderFromFile('post/index@ebcms/book-admin', [
            'book' => $db->get('ebcms_book_book', '*', [
                'id' => $request->get('book_id', 0, ['intval']),
            ]),
            'pdata' => $this->getParentData($db, $request->get('pid', 0, ['intval'])),
            'datas' => $db->select('ebcms_book_post', '*', [
                'book_id' => $request->get('book_id', 0, ['intval']),
                'pid' => $request->get('pid', 0, ['intval']),
                'ORDER' => [
                    // 'type' => 'ASC',
                    'rank' => 'DESC',
                    'id' => 'ASC',
                ],
            ]),
        ]));
    }

    private function getParentData(Db $db, $id = 0): array
    {
        $res = [];
        if ($data = $db->get('ebcms_book_post', '*', [
            'id' => $id,
        ])) {
            $sub = $this->getParentData($db, $data['pid']);
            foreach ($sub as $value) {
                $res[] = $value;
            }
            $res[] = $data;
        }
        return $res;
    }
}
