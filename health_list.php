<?php
$thispage = 'health_list';
require_once('library.php');
include(_includes_ . '/navbar.php');
if (!isset($_SESSION['user'])) header('Location:login.php');
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>近一週紀錄</title>
    <style>
        .high {
            line-height: 1.5;
        }

        .form-control {
            font-size: 0.9rem !important;
        }

        .wid {
            padding-right: 8px !important;
            padding-left: 8px !important;
        }

        .addbutton {
            position: absolute;
            bottom: 10px;
            right: 10px;
            -webkit-transition: 0.5s;
            -o-transition: 0.5s;
            transition: 0.5s;
        }

        .addbutton a span {
            font-size: 4.5rem;
            color: salmon;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container mt-3">
            <h4 class="mt-3 mb-3">近一週紀錄</h4>
        </div>
        <div class="container mb-3">
            <div class="row mb-3">
                <div class="col-5 wid">
                    <input type="date" class="form-control" id='startdate' name='startdate' value=''>
                </div>
                <div class="col-5 wid">
                    <input type="date" class="form-control" id='enddate' name='enddate' value=''>
                </div>
                <div class="col-2 wid text-light">
                    <a onclick='listsearch()' class='btn btn-info text-center '><span class="material-icons">
                            search
                        </span></a>
                </div>
            </div>
        </div>
        <table class="table table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>功能</th>
                    <th>日期</th>
                    <th>體重</th>
                    <th>體脂</th>
                    <th>BMI</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class='addbutton'>
        <a class='text-light' data-toggle="modal" data-target="#create"><span class="material-icons">
                add_circle
            </span></a>
    </div>
    <!-- create modal -->
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">新增紀錄</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id='createdata' method='POST' action='compute.php?do=create'>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">日期</label>
                                    <input type="date" class="form-control" id='date' name='date' value=''>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group flex">
                                    <label for="exampleInputEmail1">時間</label>
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time"" id=" inlineRadio1" value="morning" checked>
                                            <label class="form-check-label" for="inlineRadio1">早上</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="time" id="inlineRadio2" value="night">
                                            <label class="form-check-label" for="inlineRadio2">下午</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">體重</label>
                                    <input type="text" class="form-control" id='kg' name='kg'>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">體脂</label>
                                    <input type="text" class="form-control" id='fat' name='fat'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class='btn btn-bg btn-info float-right'>提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        var today = new Date();
        //今天
        var currentDate = `${today.getFullYear()}-${((today.getMonth() + 1 ) <10)?0:''}${(today.getMonth() + 1)}-${((today.getDate()) <10)?0:''}${today.getDate()}`;
        $('#enddate').val(currentDate);
        $('#date').val(currentDate);

        var firstdate = `${today.getFullYear()}-${((today.getMonth() + 1 ) <10)?0:''}${(today.getMonth() + 1)}-01`;
        $('#startdate').val(firstdate);

        function list_ajax() {
            $.ajax({
                type: "GET",
                url: 'compute.php?do=list7ds',
                data: '',
                success: function(re) {
                    if (re) {
                        var newre = JSON.parse(re);
                        var print = ''; 
                        for (i = 0; i < newre.length; i++) {
                            print += `
                            <tr>
                                <td>
                                    <button class='btn btn-sm btn-warning text-light' onclick='update()'><span class="material-icons">create</span></button>
                                    <button class='btn btn-sm btn-danger text-light' onclick='del(this)'><span class="material-icons">delete</span></button>
                                    <input type="hidden" name="datasetid" value='${newre[i].id}'>
                                </td>
                                <td>${newre[i].date} ${(newre[i].ampm=='morning')?'早上':'下午'}</td>
                                <td>${newre[i].kg}</td>
                                <td>${newre[i].fat}</td>
                                <td>${newre[i].bmi}</td>
                            </tr>
                            `;
                        }
                        $('tbody').html(print);
                    }
                }
            });
        }
        list_ajax();

        function listsearch() {
            var startdate = $('#startdate').val();
            var enddate = $('#enddate').val();
            $.ajax({
                type: "POST",
                url: 'compute.php?do=listsearch',
                data: {
                    startdate,
                    enddate
                },
                success: function(re) {
                    if (re) {
                        var newre = JSON.parse(re);
                        var print = '';
                        for (i = 0; i < newre.length; i++) {
                            print += `
                            <tr>
                                <td>
                                    <button class='btn btn-sm btn-warning text-light' onclick='update()'><span class="material-icons">create</span></button>
                                    <button class='btn btn-sm btn-danger text-light' onclick='del(this)'><span class="material-icons">delete</span></button>
                                    <input type="hidden" name="datasetid" value='${newre[i].id}'>
                                </td>
                                <td>${newre[i].date} ${(newre[i].ampm=='morning')?'早上':'下午'}</td>
                                <td>${newre[i].kg}</td>
                                <td>${newre[i].fat}</td>
                                <td>${newre[i].bmi}</td>
                            </tr>
                            `;
                        }
                        $('tbody').html(print);
                    }
                }
            });

            function del(who) {
                var id = $(who).parent().find('input[name=datasetid]').val();
                var chkdel = confirm("確定刪除?");
                var this_tr = $(who).parents('tr');
                if (chkdel == true) {
                    $.ajax({
                        type: "POST",
                        url: 'compute.php?do=deldataset',
                        data: {
                            id
                        },
                        success: function(re) {
                            if (re == 'OK') {
                                $(this_tr).remove();
                            }
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>