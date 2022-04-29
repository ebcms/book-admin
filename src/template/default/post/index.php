{include /common/header@ebcms/admin}
<script>
    function create(type) {
        if (type == 1) {
            var tips = "请输入文件夹名称";
        } else {
            var tips = "请输入文档名称";
        }
        if (title = prompt(tips)) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/ebcms/book-admin/post/create')}",
                data: {
                    pid: "{:$request->get('pid', 0)}",
                    book_id: "{:$request->get('book_id', 0)}",
                    type: type,
                    title: title,
                },
                dataType: "JSON",
                success: function(response) {
                    if (!response.code) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function rename(id, title) {
        if (title = prompt("请输入新的名称", title)) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/ebcms/book-admin/post/update')}",
                data: {
                    id: id,
                    title: title,
                },
                dataType: "JSON",
                success: function(response) {
                    if (!response.code) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function restate(id, state) {
        $.ajax({
            type: "POST",
            url: "{echo $router->build('/ebcms/book-admin/post/update')}",
            data: {
                id: id,
                state: state,
            },
            dataType: "JSON",
            success: function(response) {
                if (!response.code) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function deleteItem(id, title) {
        if (confirm("删除“" + title + "”\r\n此操作无法恢复，确定删除？")) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/ebcms/book-admin/post/delete')}",
                data: {
                    id: id,
                },
                dataType: "JSON",
                success: function(response) {
                    if (!response.code) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function rerank(id, type) {
        $.ajax({
            type: "POST",
            url: "{echo $router->build('/ebcms/book-admin/post/rank')}",
            data: {
                id: id,
                type: type,
            },
            dataType: "JSON",
            success: function(response) {
                if (!response.code) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $("#fanxuan").on("click", function() {
            $("#tablexx td :checkbox").each(function() {
                $(this).prop("checked", !$(this).prop("checked"));
            });
        });
        $("#inlineFormCustomSelect").bind('change', function() {
            var pid = $(this).val();
            if (pid >= 0) {
                var ids = [];
                $.each($('#tablexx input:checkbox:checked'), function() {
                    ids.push($(this).val());
                });
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/book-admin/post/move')}",
                    data: {
                        ids: ids,
                        pid: pid,
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (!response.code) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });
    });
</script>
<div class="container-xxl">
    <div class="my-4 h1">书籍管理</div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/book-admin/book/index')}">书籍管理</a></li>
            <li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/book-admin/post/index', ['book_id'=>$book['id']])}">{$book['title']}</a></li>
            {foreach $pdata as $vo}
            <li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/book-admin/post/index', ['book_id'=>$book['id'],'pid'=>$vo['id']])}">{$vo.title}</a></li>
            {/foreach}
        </ol>
    </nav>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" onclick="create(1);">新建文件夹</button>
        <button type="button" class="btn btn-primary" onclick="create(2);">新建文档</button>
    </div>
    <div class="table-responsive">
        <table class="table table-light table-bordered" id="tablexx">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">文档</th>
                    <th scope="col">状态</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>
            <tbody>
                {foreach $datas as $vo}
                <tr>
                    <td scope="row" style="width: 20px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="ids[]" value="{$vo.id}" class="custom-control-input" id="checkbox_{$vo.id}">
                        </div>
                    </td>
                    <td class="text-nowrap">
                        {if $vo['type']==1}
                        <svg t="1595425658894" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4089" width="20" height="20">
                            <path d="M912 208H427.872l-50.368-94.176A63.936 63.936 0 0 0 321.056 80H112c-35.296 0-64 28.704-64 64v736c0 35.296 28.704 64 64 64h800c35.296 0 64-28.704 64-64v-608c0-35.296-28.704-64-64-64z m-800-64h209.056l68.448 128H912v97.984c-0.416 0-0.8-0.128-1.216-0.128H113.248c-0.416 0-0.8 0.128-1.248 0.128V144z m0 736v-96l1.248-350.144 798.752 1.216V784h0.064v96H112z" p-id="4090"></path>
                        </svg>
                        <a href="{echo $router->build('/ebcms/book-admin/post/index', ['pid'=>$vo['id'],'book_id'=>$vo['book_id']])}" class="link-primary text-decoration-none">{$vo.title}</a>
                        {elseif $vo['type']==2}
                        <svg t="1595425699202" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4212" width="20" height="20">
                            <path d="M288 320h448a32 32 0 0 0 0-64H288a32 32 0 0 0 0 64zM288 544h448a32 32 0 0 0 0-64H288a32 32 0 0 0 0 64zM544 704H288a32 32 0 0 0 0 64h256a32 32 0 0 0 0-64z" p-id="4213"></path>
                            <path d="M896 132.928C896 77.28 851.552 32 796.928 32H227.04C172.448 32 128 77.28 128 132.928v758.144C128 946.72 172.448 992 227.04 992h435.008c1.568 0 2.912-0.672 4.416-0.896 8.96 1.6 18.464-0.256 25.984-6.528l192-160a31.424 31.424 0 0 0 10.816-27.2c0.16-1.184 0.736-2.208 0.736-3.424V132.928zM192 891.072V132.928C192 112.576 207.712 96 227.04 96h569.888C816.288 96 832 112.576 832 132.928V736h-96a96 96 0 0 0-96 96v96H227.04C207.712 928 192 911.424 192 891.072zM814.016 800L704 891.68V832a32 32 0 0 1 32-32h78.016z" p-id="4214"></path>
                        </svg>
                        <a href="{echo $router->build('/ebcms/book-admin/post/update', ['id'=>$vo['id']])}" class="link-primary text-decoration-none">{$vo.title}</a>
                        {/if}
                    </td>
                    <td>
                        {if $vo['state']==1}
                        <a href="javascript:restate('{$vo.id}','2');" class="link-primary text-decoration-none" title="点击切换">已发布</a>
                        {else}
                        <a href="javascript:restate('{$vo.id}','1');" class="link-secondary text-decoration-none" title="点击切换">未发布</a>
                        {/if}
                    </td>
                    <td class="text-nowrap">
                        <a href="javascript:rename('{$vo.id}','{$vo.title}');" class="link-primary text-decoration-none">重命名</a>
                        <a href="javascript:deleteItem('{$vo.id}','{$vo.title}');" class="link-primary text-decoration-none">删除</a>
                        <a href="javascript:rerank('{$vo.id}','up');" class="link-primary text-decoration-none">上移</a>
                        <a href="javascript:rerank('{$vo.id}','down');" class="link-primary text-decoration-none">下移</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <form class="row gy-2 gx-3 align-items-center">
                            <div class="col-auto">
                                <button class="btn btn-secondary btn-sm" type="button" id="fanxuan">全选/反选</button>
                            </div>
                            <div class="col-auto">
                                {function test($db, $book_id, $pid=0, $level=0)}
                                <?php
                                $datas = $db->select('ebcms_book_post', '*', [
                                    'book_id' => $book_id,
                                    'pid' => $pid,
                                    'type' => 1,
                                    'ORDER' => [
                                        'rank' => 'DESC',
                                        'id' => 'ASC',
                                    ],
                                ]);
                                ?>
                                {foreach $datas as $vo}
                                <option value="{$vo.id}">{:str_repeat('&nbsp;&nbsp;&nbsp;', $level)}┣{$vo.title}</option>
                                {:test($db, $book_id, $vo['id'], $level+1)}
                                {/foreach}
                                {/function}
                                <select class="form-select form-select-sm" id="inlineFormCustomSelect">
                                    <option value="-1" selected>移动到...</option>
                                    <option value="0">根目录</option>
                                    {:test($db, $book['id'])}
                                </select>
                            </div>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
{include /common/footer@ebcms/admin}