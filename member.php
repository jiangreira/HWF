<?php
require_once('library.php');
include(_includes_ . '/navbar.php');
if (!isset($_SESSION['user'])) header('Location:login.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info</title>
    <style>
    </style>
</head>

<body>
    <div class="container-fluid ">
        <div class="container mt-3 position-relative">
            <h4 class="mt-3 mb-3">資料維護及設定(維修中)</h4>
        </div>
        <div class="container">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">初始訊息</a>
                    <a class="nav-item nav-link" id="find_friend-tab" data-toggle="tab" href="#find_friend" role="tab" aria-controls="find_friend" aria-selected="false">尋找好友</a>
                    <a class="nav-item nav-link" id="firend_list-tab" data-toggle="tab" href="#firend_list" role="tab" aria-controls="firend_list" aria-selected="false">好友列表</a>
                    <a class="nav-item nav-link" id="freind_request-tab" data-toggle="tab" href="#freind_request" role="tab" aria-controls="freind_request" aria-selected="false">申請&nbsp; &nbsp;<span class="badge badge-danger"></span></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- 初始訊息 -->
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
                <div class="tab-pane fade" id="find_friend" role="tabpanel" aria-labelledby="find_friend-tab">
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
                <!-- 好友列表 -->
                <div class="tab-pane fade" id="firend_list" role="tabpanel" aria-labelledby="firend_list-tab">...</div>
                <!-- 申請紀錄 -->
                <div class="tab-pane fade" id="freind_request" role="tabpanel" aria-labelledby="freind_request-tab">
                    <div class="form-group mt-3">
                        <label>好友申請:</label>
                        <table class='table friendsrequest-table '>
                            <thead class='thead-light '>
                                <tr class='d-flex'>
                                    <th class='col-3'>姓名</th>
                                    <th class='col-7'>功能</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr>
                    </div>
                    <div class="form-group mt-3">
                        <label>我提交的申請:</label>
                        <table class='table friendssend-table '>
                            <thead class='thead-light'>
                                <tr class='d-flex'>
                                    <th class='col-3'>姓名</th>
                                    <th class='col-7'>狀態</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="forsearch" tabindex="-1" role="dialog" aria-labelledby="forsearch" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">搜尋結果</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class='table searchfriend-table '>
                        <thead class='thead-light '>
                            <tr>
                                <th>姓名</th>
                                <th>發送邀請</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var clipboard = new ClipboardJS('.copycode', {
            text: function() {
                var txt = $('#hidden_code').val();
                return txt;
            }
        });
        clipboard.on('success', function(e) {
            alert('Copied!');
        });
        var arr22;
        var infoshow = function() {
            $.ajax({
                type: "POST",
                url: 'compute.php?do=basicshow',
                data: '',
                success: function(re) {
                    var arr = JSON.parse(re);
                    arr22 = arr;
                    // 初始訊息&代碼
                    if (arr.basicinfo.msg == 'nodata') {
                        $('#height').val('0');
                        $('#fat').val('0');
                        $('#kg').val('0');
                    } else if (arr.basicinfo.msg == 'OK') {
                        if ((arr.basicinfo.data[0].height) == 'null' || (arr.basicinfo.data[0].height) == 'undefined') {
                            $('#height').val('0');
                        } else {
                            $('#height').val(`${arr.basicinfo.data[0].height}`);
                        }
                        if ((arr.basicinfo.data[0].fat) == 'null' || (arr.basicinfo.data[0].fat) == 'undefined') {
                            $('#fat').val('0');
                        } else {
                            $('#fat').val(`${arr.basicinfo.data[0].fat}`);
                        }
                        if ((arr.basicinfo.data[0].kg) == 'null' || (arr.basicinfo.data[0].kg) == 'undefined') {
                            $('#kg').val('0');
                        } else {
                            $('#kg').val(`${arr.basicinfo.data[0].kg}`);
                        }
                        if (arr.basicinfo.data[0].friend_code != 'undefined') {
                            $('#firend_code').val(`${arr.basicinfo.data[0].friend_code}`)
                            $('#hidden_code').val(`${arr.basicinfo.data[0].friend_code}`)
                            $('#getFriendCode').attr('disabled', 'disabled');
                        }
                    }
                    // 我方發送的邀請
                    if (arr.friendsend.msg == 'OK') {
                        var print = '';
                        for (i = 0; i < arr.friendsend.data.length; i++) {
                            print += `<tr class="d-flex">
                                        <td class='col-3'>${arr.friendsend.data[i].requestname}
                                            <input type="hidden" name="friendsid" value='${arr.friendsend.data[i].requestToid}'></td>
                                        <td class='col-7'><span>等待同意中</span>
                                            <button class='btn btn-sm btn-danger'onclick='delfriendsend(${arr.friendsend.data[i].requestToid})'><span class="material-icons">delete</span></button></td>
                                    </tr>`;
                        }
                        $('.friendssend-table tbody').html(print);
                    } else {
                        var print = `<tr class="d-flex"><td colspan="2" class="text-center col-10">無資料</td></tr>`;
                        $('.friendssend-table tbody').html(print);
                    }
                    // 他人提交的邀請
                    if (arr.find_friendsreq.msg == 'OK') {
                        var print = '';
                        for (i = 0; i < arr.find_friendsreq.data.length; i++) {
                            print += `<tr class="d-flex">
                                        <td class='col-3'>${arr.find_friendsreq.data[i].requestname}
                                            <input type="hidden" name="friendsid" value='${arr.find_friendsreq.data[i].requestid}'></td>
                                        <td class='col-7'>
                                            <button class='btn btn-sm btn-danger'onclick='agreequest(${arr.find_friendsreq.data[i].requestid})'><span class="material-icons">how_to_reg</span></button>
                                            <button class='btn btn-sm btn-danger'onclick='denyquest(${arr.find_friendsreq.data[i].requestid})'><span class="material-icons">clear</span></button>
                                        </td>
                                        </tr>`;
                        }
                        $('.friendsrequest-table tbody').html(print);
                        var badge = `${arr.find_friendsreq.data.length}`;
                        $('.badge').text(badge);

                    } else {
                        var print = `<tr class='d-flex'><td colspan="2" class="text-center col-10">無資料</td></tr>`
                        $('.friendsrequest-table tbody').html(print);
                    }

                }
            });
        }
        infoshow();

        function basicedit() {
            var height = $('#height').val();
            var kg = $('#kg').val();
            var fat = $('#fat').val();
            if (height.length <= 0 || kg.length <= 0 || fat.length <= 0) {
                alert('不得為空');
            } else {
                $.ajax({
                    type: "POST",
                    url: 'compute.php?do=basicedit',
                    data: {
                        height,
                        kg,
                        fat
                    },
                    success: function(re) {
                        $arr = JSON.parse(re);
                        if ($arr['msg'] == 'OK') {
                            alert('修改成功');
                            infoshow();
                        } else if ($arr['msg'] == 'err') {
                            location.href('compute.php?do=logout');
                        }
                    }
                });
            }

        }
        $('#getFriendCode').click(function() {
            $.ajax({
                type: "POST",
                url: 'compute.php?do=getFriendCode',
                data: '',
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $('#firend_code').val(arr['FriendCode']);
                        $('#hidden_code').val(arr['FriendCode']);
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`);
                    }
                }
            });
        })
        $('#searchFriend').click(function() {
            var code = $('#search_firend').val();
            $.ajax({
                type: "POST",
                url: 'compute.php?do=searchFriend',
                data: {
                    code
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr.msg == 'OK') {
                        var i = 0;
                        var print = '';
                        for (i; i < arr.data.length; i++) {
                            print += `<tr><th>${arr.data[i].name}<input type="hidden" name="friendsid" value='${arr.data[i].id}'></th><th><button class='btn btn-sm btn-danger'onclick='sendrequest(this)'><span class="material-icons">send</span></button> \t <span></span></th></tr>`;
                        }
                        $('.searchfriend-table tbody').html(print);
                        $('#forsearch').modal('show')
                    } else if (arr.msg == 'err') {
                        alert(`${arr.txt}`);
                    } else {
                        alert('查無資料');
                    }

                }
            });
        })

        function sendrequest(who) {
            arr2 = who
            var requestid = $(who).parents('tr').find('input[name=friendsid]').val()
            $.ajax({
                type: "POST",
                url: 'compute.php?do=sendFriendRequest',
                data: {
                    requestid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $(who).attr('disabled', 'disabled');
                    } else if (arr['msg'] == 'requestfail') {
                        $(who).siblings('span').text(`Oops!`)
                    } else {
                        $(who).siblings('span').text(`${arr['txt']}`)
                        $(who).attr('disabled', 'disabled');
                    }
                }
            });
        }

        function delfriendsend(who) {
            var delid = who;
            $.ajax({
                type: "POST",
                url: 'compute.php?do=delfriendsend',
                data: {
                    delid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        infoshow();
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`)
                    }
                }
            });
        }

        function agreequest(who) {
            var agreeid = who;
            $.ajax({
                type: "POST",
                url: 'compute.php?do=agreequest',
                data: {
                    agreeid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $('.badge').text('');
                        infoshow();
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`)
                    }
                }
            });
        }

        function denyquest(who) {
            var denyid = who;
            $.ajax({
                type: "POST",
                url: 'compute.php?do=denyquest',
                data: {
                    denyid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr['msg'] == 'OK') {
                        $('.badge').text('');
                        infoshow();
                    } else if (arr['msg'] == 'err') {
                        alert(`${arr['txt']}`)
                    }
                }
            });

        }
    </script>

</body>

</html>