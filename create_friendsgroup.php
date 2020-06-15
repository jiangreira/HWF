<?php
$thispage = 'create_friendsgroup';
require_once('library.php');
include(_includes_ . '/navbar.php');
if (!isset($_SESSION['user'])) header('Location:login.php');
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>群組管理</title>
    <style>
        td {
            line-height: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container mt-3">
            <h4 class="mt-3 mb-3">群組管理</h4>
            <p></p>
            <button class='btn btn-primary' data-toggle="modal" data-target="#creategroup">新增群組</button>
            <button class='btn btn-primary' onclick='opensearchgroup()'>搜尋群組</button>
            <hr>
        </div>
        <div class="container">
            <h5>我的群組</h5>
            <table class="table table-sm table-striped" id='mygroup'>
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>群組名</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <!-- <h5>受邀請的群組</h5>
            <table class="table table-sm table-striped" id='invited'>
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>群組名</th>
                        <th>邀請人</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table> -->
            <h5>申請中的群組</h5>
            <table class="table table-sm table-striped" id='mygrouprequest''>
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>群組名(申請時間)</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


    </div>
    <!-- Modal 新增群組 -->
    <div class="modal fade" id="creategroup" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">新增群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">群組名</label>
                        <input type="text" class="form-control" id=' new_groupname' name='new_groupname'>
        </div>
    </div>
    <div class="modal-footer">
        <button onclick='newgroup()' class='btn btn-bg btn-info float-right'>提交</button>
    </div>
    </div>
    </div>
    </div>

    <!-- Modal 搜尋群組-->
    <div class="modal fade" id="searchgroup" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModal">搜尋群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">群組代碼</label>
                        <input type="text" class="form-control" id='search_groupcode' name='search_groupcode'>
                        <span class='searchspan text-danger'></span>
                    </div>
                    <hr>
                    <div class="form-group result_searcharea">
                        <label for="exampleInputEmail1">搜尋結果</label>
                        <table class="table table-sm table-striped" id='result_groupcode'>
                            <thead class="thead-light">
                                <tr>
                                    <th>群組名</th>
                                    <th>申請</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick='searchgroup()' class='btn btn-bg btn-info float-right'>提交</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 管理群組 -->
    <div class="modal fade" id="managergroup" tabindex="-1" role="dialog" aria-labelledby="managerModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModal">管理群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">成員</a>
                            <a class="nav-item nav-link" id="nav-second-tab" data-toggle=" tab" href="#group_request" role="tab" aria-controls="group_request" aria-selected="false">申請</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <!-- 成員 -->
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="form-group mt-3">
                                <label for="height">身高</label>
                                <input type="text" class="form-control" id="height" val=''>
                            </div>
                            <div class="form-group">
                                <label for="kg">挑戰日體重</label>
                                <input type="text" class="form-control" id="kg" val=''>
                            </div>
                            <div class="form-group">
                                <label for="fat"">挑戰日體脂</label>
                        <input type=" text" class="form-control" id="fat" val=''>
                            </div>
                            <button onclick='basicedit()' class="btn btn-danger mt-3 float-right"><span class="material-icons">save</span></button>
                        </div>
                        <!-- 好友代碼 -->
                        <div class="tab-pane fade" id="group_request" role="tabpanel" aria-labelledby="nav-second-tab">
                            <div class="form-group mt-3">
                                <label for="firend_code">我的好友++代碼</label>
                                <input type="text" class="form-control" id="firend_code" disabled>
                                <input type="hidden" class="form-control" id="hidden_code">
                                <span>此為他人透過代碼增加好友</span>
                            </div>
                            <div class="form-group mt-3">
                                <button id='getFriendCode' class='btn btn-danger'>產生代碼</button>
                                <button id='copycode' class='btn btn-primary copycode'>複製代碼</button>
                            </div>
                            <hr>
                            <div class="form-group mt-3">
                                <label for="firend_code">搜尋他人</label><br>
                                <input type="text" class="form-control" id="search_firend" val=''>
                            </div>
                            <div class="form-group">
                                <button id='searchFriend' type="button" class="btn btn-primary">尋找</button>
                            </div>
                            <table class='table'>
                            </table>
                            <hr>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick='' class='btn btn-bg btn-info float-right'>提交</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var arr22
        $('#managergroup').modal('show')

        function groupshow() {
            $.ajax({
                type: "POST",
                url: 'compute.php?do=groupshow',
                data: {},
                success: function(re) {
                    var arr = JSON.parse(re);
                    arr22 = arr
                    // 我的
                    if (arr['mygroup']['msg'] == 'OK') {
                        var print = '';
                        for (i = 0; i < arr['mygroup']['data'].length; i++) {
                            var num = i + 1;
                            if (arr['mygroup']['data'][i].isowner == 1) { //是owner
                                var requestotal = (arr['mygroup']['data'][i].PendingCount > 0) ? arr['mygroup']['data'][i].PendingCount : '';
                                print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                    <span class="badge badge-danger">Owner</span>
                                        ${arr['mygroup']['data'][i].name}
                                    </td>
                                    <td>
                                    <button onclick='manager(${arr['mygroup']['data'][i].groupid})'class='btn-sm btn btn-primary'>管理群組&nbsp;<span class="badge badge-danger">${requestotal}</span></button>
                                    <button class='groupcode btn btn-sm btn-warning text-light' data-clipboard-text="${arr['mygroup']['data'][i].code}">複製代碼</button>
                                    </td>
                                    </tr>`;
                            } else { //不是owner
                                print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                        ${arr['mygroup']['data'][i].name}
                                    </td>
                                    <td>
                                    <button class='groupcode btn btn-sm btn-warning text-light' data-clipboard-text="${arr['mygroup']['data'][i].code}">複製代碼</button>
                                    <button class='btn-sm btn btn-danger'>退出</button>
                                    </td>
                                    </tr>`;
                            }

                        }
                        $('#mygroup tbody').html(print);
                    } else if (arr['mygroup']['msg'] == 'nodata') {
                        var print = `<tr><td colspan="4" class='text-center'>無資料</td></tr>`;
                        $('#mygroup tbody').html(print);
                    }

                    // 申請中的
                    if (arr['myrequestgroup']['msg'] == 'OK') {
                        var print = '';
                        for (i = 0; i < arr['myrequestgroup']['data'].length; i++) {
                            var num = i + 1;
                            print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                        ${arr['myrequestgroup']['data'][i].name}(${arr['myrequestgroup']['data'][i].time})
                                    </td>
                                    <td>
                                    <button class='btn btn-sm btn-danger text-light' onclick='delgrouprequest(${arr['myrequestgroup']['data'][i].groupid})'><span class="material-icons">delete</span></button>
                                    </td>
                                    </tr>`;
                        }
                        $('#mygrouprequest tbody').html(print);

                    } else if (arr['myrequestgroup']['msg'] == 'nodata') {
                        var print = `<tr><td colspan="4" class='text-center'>無資料</td></tr>`;
                        $('#mygrouprequest tbody').html(print);
                    }
                }
            });
        }
        groupshow()

        // copycode
        var clipboard = new ClipboardJS('.groupcode');
        clipboard.on('success', function(e) {
            e.clearSelection();
            alert(`"${e.text}" 複製成功`);
        });

        // 新增
        function newgroup() {
            var new_groupname = $('#new_groupname').val();
            $.ajax({
                type: "POST",
                url: 'compute.php?do=newgroup',
                data: {
                    new_groupname
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $('#creategroup').modal('hide')
                        $('#new_groupname').val('');
                        alert(`新增成功`)
                        groupshow();
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`)
                    }
                }
            });
        }

        // open search modal
        function opensearchgroup() {
            $('#result_groupcode tbody').html();
            $('.searchspan').text('')
            $('#search_groupcode').val('')
            $('.result_searcharea').hide();
            $('#searchgroup').modal('show');
        }

        function searchgroup() {
            var code = $('#search_groupcode').val();
            $.ajax({
                type: "POST",
                url: 'compute.php?do=searchgroup',
                data: {
                    code
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    console.log(arr);
                    if (arr['msg'] == 'OK') {
                        var print = `<tr>
                                    <td>
                                        ${arr['data'][0].name} (群組擁有者:${arr['data'][0].owner})
                                        <input type="hidden" name="result_search_groupcode" value='${arr['data'][0].id}'>
                                    </td>
                                    <td>
                                    <button class='btn btn-sm btn-danger'onclick='sendrequest(this,${arr['data'][0].id})'><span class="material-icons">send</span></button>
                                    &nbsp; &nbsp;
                                    <span class='sendresult'></span>
                                    </td>
                                    </tr>`;
                        $('#result_groupcode tbody').html(print);
                        // $('#searchgroup').find('.modal-body').find('.form-group').eq(1).removeClass('fade')
                        $('.result_searcharea').show();
                    } else if (arr['msg'] == 'owner') {
                        $('.searchspan').text('已擁有該群組')
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`)
                    }
                }
            });
        }

        function sendrequest(where, who) {
            var id = who;
            $.ajax({
                type: "POST",
                url: 'compute.php?do=sendGroupRequest',
                data: {
                    id
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $('.sendresult').text('已發送申請')
                        $(where).attr('disabled', 'disabled');
                    } else {
                        //     $(who).siblings('span').text(`${arr['txt']}`)
                        //     $(who).parent().find('.txt').text('邀請已存在')
                        //     $(who).attr('disabled', 'disabled');

                    }
                }
            });
        }
        // 管理群組
        var man;

        function manager(who) {
            var groupid = who;
            $.ajax({
                type: "POST",
                url: 'compute.php?do=findgroup',
                data: {
                    groupid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    man = arr;
                    if (arr['msg'] == 'OK') {

                    } else {
                        groupshow()
                    }
                }
            });
        }

        // 刪除我方申請
        function delgrouprequest(who) {
            var ans = confirm('確定要刪除')
            if (ans) {
                var delid = who
                $.ajax({
                    type: "POST",
                    url: 'compute.php?do=delgrouprequest',
                    data: {
                        delid
                    },
                    success: function(re) {
                        var arr = JSON.parse(re);
                        if (arr['msg'] == 'OK') {
                            alert('刪除成功')
                            groupshow();
                        } else {
                            alert('Something,請重新再試一遍!!');
                        }
                    }
                });
            }

        }
    </script>
</body>

</html>