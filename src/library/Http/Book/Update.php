<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Book;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Router\Router;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Cover;
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
        Router $router,
        Db $db,
        Request $request
    ) {
        $data = $db->get('ebcms_book_book', '*', [
            'id' => $request->get('id'),
        ]);
        $form = new Builder('更新书籍');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Input('名称', 'name', $data['name']))->set('required', 1),
                    new Cover('封面', 'cover', $data['cover'], $router->build('/ebcms/admin/upload')),
                    new Input('关键词', 'keywords', $data['keywords']),
                    new Textarea('简介', 'description', $data['description']),
                    new Input('栏目默认模板', 'tpl_book', $data['tpl_book']),
                    new Input('内容默认模板', 'tpl_post', $data['tpl_post'])
                ),
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $data['id'])),
                    (new Input('书籍标题', 'title', $data['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new SimpleMDE('书籍介绍', 'body', $data['body'], $router->build('/ebcms/admin/upload'))),
                    (new Radio('是否发布', 'state', $data['state'], [
                        '1' => '是',
                        '2' => '否',
                    ]))->set('inline', true)
                )
            )
        );
        return $form;
    }
    public function post(
        Request $request,
        Db $db
    ) {
        $update = array_intersect_key($request->post(), [
            'title' => '',
            'cover' => '',
            'state' => '',
            'keywords' => '',
            'description' => '',
            'tpl_book' => '',
            'tpl_post' => '',
            'name' => '',
        ]);

        if (isset($update['name'])) {
            if ($db->get('ebcms_book_book', '*', [
                'id[!]' => $request->post('id'),
                'name' => $request->post('name'),
            ])) {
                return $this->error('名称已经存在！');
            }
        }

        if ($request->has('post.body')) {
            $update['body'] = $request->post('body', '', []);
        }
        $update['update_time'] = time();

        $db->update('ebcms_book_book', $update, [
            'id' => $request->post('id'),
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
