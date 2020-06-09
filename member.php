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
            <h4 class="mt-3 mb-3">資料維護及設定</h4>
        </div>
        <div class="container">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">初始訊息</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">好友++</a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
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
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
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
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->

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
        var arr1;
        var infoshow = function() {
            $.ajax({
                type: "POST",
                url: 'compute.php?do=basicshow',
                data: '',
                success: function(re) {
                    var arr = JSON.parse(re);
                    arr1 = arr
                    if (arr.msg == 'nodata') {
                        $('#height').val('0');
                        $('#fat').val('0');
                        $('#kg').val('0');
                    } else if (arr.msg == 'OK') {
                        if ((arr.data[0].height) == 'null' || (arr.data[0].height) == 'undefined') {
                            $('#height').val('0');
                        } else {
                            $('#height').val(`${arr.data[0].height}`);
                        }
                        if ((arr.data[0].fat) == 'null' || (arr.data[0].fat) == 'undefined') {
                            $('#fat').val('0');
                        } else {
                            $('#fat').val(`${arr.data[0].fat}`);
                        }
                        if ((arr.data[0].kg) == 'null' || (arr.data[0].kg) == 'undefined') {
                            $('#kg').val('0');
                        } else {
                            $('#kg').val(`${arr.data[0].kg}`);
                        }
                        if (arr.data[0].friend_code != 'undefined') {
                            $('#firend_code').val(`${arr.data[0].friend_code}`)
                            $('#hidden_code').val(`${arr.data[0].friend_code}`)
                            $('#getFriendCode').attr('disabled', 'disabled');
                        }
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
                    console.log(arr.data.length);
                    var i = 0;
                    var print = '<tr><th>姓名</th><th>++</th></tr>';
                    for (i; i < arr.data.length; i++) {
                        print += `<tr><th>${arr.date[i].name}</th><th></th></tr>`;
                    }
                    $('table').html(print);

                }
            });
        })
    </script>
</body>

</html>