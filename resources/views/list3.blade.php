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
    <script src="/lib/rium/index.js"></script>
    <script src="/lib/vue/index.js"></script>
    <script src="/lib/element-ui/index.js"></script>
    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    comments: [],
                    lastIds: [],
                }
            },
            methods: {
                getIds() {
                    $fet('/getCommentIds').then(rs => {
                        this.lastIds = rs
                        let ids = this.lastIds.splice(0, 10)
                        $fet('/getCommentByIds', {ids}).then(rs => {
                            this.comments = rs
                        })
                    })
                },
                next() {
                    let ids = this.lastIds.splice(0, 10)
                    if (! ids) {
                        return
                    }
                    $fet('/getCommentByIds', {ids}).then(rs => {
                        this.comments = rs
                    })
                    if (this.lastIds.length === 0) {
                        $fet ('/getCommentIds', {last_ids: ids}).then(rs => {
                            this.lastIds = rs
                        })
                    }
                },
            },
            mounted() {
                this.getIds()
            }
        })
    </script>
</body>
</html>