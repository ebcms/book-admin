<?php

declare(strict_types=1);

namespace App\Ebcms\BookAdmin\Http\Book;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Router\Router;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Cover;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Form\Field\SimpleMDE;
use DiggPHP\Form\Field\Textarea;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;

class Create extends Common
{
    public function get(
        Router $router
    ) {
        $form = new Builder('创建书籍');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    new Input('名称', 'name', '', [
                        'required' => 1,
                    ]),
                    new Cover('封面', 'cover', '', $router->build('/ebcms/admin/upload')),
                    new Input('关键词', 'keywords'),
                    new Textarea('简介', 'description'),
                    new Input('封面模板', 'tpl_book'),
                    new Input('内容模板', 'tpl_post')
                ),
                (new Col('col-md-9'))->addItem(
                    (new Input('书籍标题', 'title'))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new SimpleMDE('书籍介绍', 'body', '', $router->build('/ebcms/admin/upload'))),
                    (new Radio('是否发布', 'state', 1, [
                        1 => '是',
                        2 => '否',
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

        if ($db->get('ebcms_book_book', '*', [
            'name' => $request->post('name'),
        ])) {
            return $this->error('别名已经存在！');
        }

        $db->insert('ebcms_book_book', [
            'name' => $request->post('name'),
            'title' => $request->post('title'),
            'body' => $request->post('body', '', []),
            'cover' => $request->post('cover'),
            'state' => $request->post('state'),
            'keywords' => $request->post('keywords'),
            'description' => $request->post('description'),
            'tpl_book' => $request->post('tpl_book'),
            'tpl_post' => $request->post('tpl_post'),
            'create_time' => time(),
            'update_time' => time(),
        ]);
        return $this->success('操作成功！');
    }
}
