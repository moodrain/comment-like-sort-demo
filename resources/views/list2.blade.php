<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="/lib/element-ui/index.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div style="width: 60%;margin: auto;">
            <el-table :data="comments">
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="like_count" label="Like"></el-table-column>
                <el-table-column prop="like_count_updated_at" label="LastLikeAt"></el-table-column>
            </el-table>
            <div style="text-align: center;">
                <br />
                <el-button @click="next">下一页</el-button>
            </div>
        </div>
    </div>
    <script src="/lib/vue/index.js"></script>
    <script src="/lib/element-ui/index.js"></script>
    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    comments: @json($list),
                    lastLikeAt: '{{ request('last_like_at') ?? date('Y-m-d H:i:s') }}',
                    likeCount: {{ $list->last()->like_count }},
                    lastId: {{ $list->last()->id }},
                }
            },
            methods: {
                next() {
                    location.href = ('/list2?like_count=' + this.likeCount + '&last_like_at=' + this.lastLikeAt + '&last_id=' + this.lastId)
                },
            }
        })
    </script>
</body>
</html>