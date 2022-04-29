<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Post;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Router\Router;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Form\Field\SimpleMDE;
use DiggPHP\Form\Field\Textarea;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;

class Update extends Common
{
    public function get(
        Db $db,
        Router $router,
        Request $request
    ) {
        $data = $db->get('ebcms_book_post', '*', [
            'id' => $request->get('id', 0),
        ]);
        $form = new Builder('编辑文档');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    new Input('关键词', 'keywords', $data['keywords']),
                    new Textarea('简介', 'description', $data['description'])
                ),
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $data['id'])),
                    (new Input('文档名称', 'title', $data['title']))->set('help', '一般不超过80个字符')->set('required', 1),
                    (new SimpleMDE('文档详情', 'body', $data['body'], $router->build('/ebcms/admin/upload'))),
                    (new Radio('是否发布', 'state', $data['state'], [
                        1 => '是',
                        2 => '否',
                    ]))->set('inline', true)
                )
            )
        );
        return $this->html($form->__toString());
    }
    public function post(
        Request $request,
        Db $db
    ) {
        $update = array_intersect_key($request->post(), [
            'title' => '',
            'keywords' => '',
            'description' => '',
            'state' => '',
        ]);
        if ($request->has('post.body')) {
            $update['body'] = $request->post('body', '');
        }
        $update['update_time'] = time();

        $db->update('ebcms_book_post', $update, [
            'id' => $request->post('id', 0),
        ]);

        if ($book_id = $db->get('ebcms_book_post', 'book_id', [
            'id' => $request->post('id'),
        ])) {
            $db->update('ebcms_book_book', [
                'update_time' => time(),
            ], [
                'id' => $book_id,
            ]);
        }

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
