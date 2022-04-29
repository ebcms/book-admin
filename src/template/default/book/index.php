{include /common/header@ebcms/admin}
<script>
    function restate(id, state) {
        $.ajax({
            type: "POST",
            url: "{echo $router->build('/ebcms/book-admin/book/update')}",
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
                url: "{echo $router->build('/ebcms/book-admin/book/delete')}",
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
</script>
<div class="container-xxl">
    <div class="my-4 h1">书籍管理</div>
    <div class="mb-4">
        <a href="{echo $router->build('/ebcms/book-admin/book/create')}" class="btn btn-primary">创建书籍</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/holderjs@2.9.6/holder.min.js" integrity="sha256-yF/YjmNnXHBdym5nuQyBNU62sCUN9Hx5awMkApzhZR0=" crossorigin="anonymous"></script>
    <div class="row row-cols-auto gy-4">
        {foreach $books as $vo}
        <div class="col">
            <div style="width:400px;">
                <div class="d-flex position-relative">
                    {if isset($vo['cover']) && $vo['cover']}
                    <img class="flex-shrink-0 me-3 img-thumbnail" width=100 height=140 src="{$vo.cover}">
                    {else}
                    <img class="flex-shrink-0 me-3 img-thumbnail" width=100 height=140 data-src="holder.js/100x140?auto=yes&text=nopic&size=16">
                    {/if}
                    <div class="position-relative flex-fill">
                        <div class="mt-0"><strong>{$vo.title}</strong></div>
                        <div class="text-muted">名称：{$vo['name']?:''}</div>
                        <div class="text-muted">已发布：{$db->count('ebcms_book_post',['book_id'=>$vo['id'],'state'=>1])} 篇</div>
                        <div class="text-muted">草稿：{$db->count('ebcms_book_post',['book_id'=>$vo['id'],'state'=>2])} 篇</div>
                        <div class="position-absolute bottom-0 start-0">
                            <a class="btn btn-outline-primary btn-sm" href="{echo $router->build('/ebcms/book-admin/book/update', ['id'=>$vo['id']])}">设置</a>
                            <a class="btn btn-outline-primary btn-sm" href="{echo $router->build('/ebcms/book-admin/post/index', ['book_id'=>$vo['id']])}">管理</a>
                            <a class="btn btn-outline-danger btn-sm" href="javascript:deleteItem('{$vo.id}','{$vo.title}');">删除</a>
                            {if $vo['state']==1}
                            <a class="btn btn-outline-primary btn-sm" href="javascript:restate('{$vo.id}','2');" class="text-primary" title="点击切换">已发布</a>
                            {else}
                            <a class="btn btn-outline-info btn-sm" href="javascript:restate('{$vo.id}','1');" class="text-warning" title="点击切换">未发布</a>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>
{include /common/footer@ebcms/admin}