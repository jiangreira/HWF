<?php
require_once('library.php');
include(_includes_ . '/navbar.php');
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增紀錄</title>
</head>

<body>
    <div class="container">
        <div class="container mt-3">
            <h4 class="mt-3 mb-3">新增紀錄</h4>
        </div>
        <div class="container">
            <form method="POST" action='compute.php?do=create'>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">日期</label>
                            <input type="date" class="form-control" id='date' name='date' value='<?= date('Y-m-d') ?>'>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group flex">
                            <label for="exampleInputEmail1">時間</label>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="time"" id="inlineRadio1" value="morning" checked>
                                    <label class="form-check-label" for="inlineRadio1"  >早上</label>
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
                            <label for="exampleInputEmail1">體指</label>
                            <input type="text" class="form-control" id='fat' name='fat'>
                        </div>
                    </div>
                </div>
                <button class='btn btn-bg btn-info float-right'>提交</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>


    </script>
</body>

</html>