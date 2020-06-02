<?php
require_once('library.php');
include(_includes_ . '/navbar.php');
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>近一週紀錄</title>
</head>

<body>
    <div class="container">
        <div class="container mt-3">
            <h4 class="mt-3 mb-3">近一週紀錄</h4>
        </div>
        <div class="container">
            <table class="table table-sm table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>功能</th>
                        <th>日期</th>
                        <th>體重</th>
                        <th>體指</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button class='btn btn-sm btn-warning text-light'><span class="material-icons">create</span></button>
                            <button class='btn btn-sm btn-warning text-light'><span class="material-icons">delete</span></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>


    </script>
</body>

</html>