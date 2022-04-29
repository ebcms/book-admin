<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Book;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Db $db,
        Template $template
    ) {
        return $this->html($template->renderFromFile('book/index@ebcms/book-admin', [
            'books' => $db->select('ebcms_book_book', '*', [
                'ORDER' => [
                    'id' => 'ASC',
                ],
            ]),
        ]));
    }
}
