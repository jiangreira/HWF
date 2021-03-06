<?php
require_once('library.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        html,
        body {
            height: 100%;
            font-family: "Noto Sans TC", sans-serif;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: -2px;
            border-radius: 0;
        }

        .form-signin input[type="text"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="POST" class="form-signin" action="compute.php?do=register">
                    <h1 class="h3 mb-3 font-weight-normal">註冊</h1>
                    <label for="re_name" class="sr-only">姓名</label>
                    <input type="text" name='re_name' id='re_name' class="form-control" placeholder="暱稱" required autofocus>
                    <label for="re_id" class="sr-only">帳號</label>
                    <input type="text" name='re_id' id='re_id' class="form-control" placeholder="ID" required autofocus>
                    <label for="re_password" class="sr-only">密碼</label>
                    <input type="password" name='re_password' id="re_password" class="form-control" placeholder="Password" required>
                    <p></p>
                    <button class="btn btn-lg btn-primary btn-block">登入</button>
                    <hr>
                </form>
            </div>
        </div>
    </div>
</body>

</html>