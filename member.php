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
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
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
                    <a onclick='basicedit()' class="btn btn-danger mt-3 float-right"><span class="material-icons">save</span></a>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
            </div>
        </div>
    </div>
    <script>
        var infoshow = function() {
            $.ajax({
                type: "POST",
                url: 'compute.php?do=basicshow',
                data: '',
                success: function(re) {
                    var arr = JSON.parse(re);
                    $('#height').val(`${arr[0].height}`);
                    $('#fat').val(`${arr[0].fat}`);
                    $('#kg').val(`${arr[0].kg}`);
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
    </script>
</body>

</html>