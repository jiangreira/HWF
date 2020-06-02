<?php
require_once('library.php');
switch ($_GET['do']) {
    case 'login':
        $id = $_POST['id'];
        $inputpasswod = md5($_POST['password']);
        $chkid = 'SELECT * FROM hwf_user WHERE acc="' . $id . '"';
        $rows = $db->query($chkid)->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            // db裡的password
            $hashedPassword = $rows[0]['pwd'];
            // verity
            if ($inputpasswod == $hashedPassword) {
                $_SESSION['username'] = $rows[0]['name'];
                $_SESSION['user'] = $rows[0]['id'];
                echo "<script>alert('登入成功');" . jlo("health_create.php") . "</script>";
            } else {
                echo "<script>alert('密碼錯誤');" . jlo("login.php") . "</script>";
            }
        } else {
            $_SESSION['loginmsg'] = 'noid';
            echo "<script>alert('非邀請對象');" . jlo("login.php") . "</script>";
        }

        break;
    case 'register':
        $name = $_POST['re_name'];
        $acc = $_POST['re_id'];
        $password = md5($_POST['re_password']);
        $sql = 'INSERT INTO hwf_user VALUES(null,"' . $name . '","' . $acc . '","' . $password . '",NOW(),NOW())';
        if ($db->query($sql)) {
            return header('Location:login.php');
        };
        break;
    case 'logout':
        session_destroy();
        header('Location:login.php');
        break;
    case 'create':
        print_r($_POST);
        $userid = $_SESSION['user'];
        $kg = (isset($_POST['kg'])) ? $_POST['kg'] : 0;
        $fat = (isset($_POST['fat'])) ? $_POST['fat'] : 0;
        $date = (isset($_POST['date'])) ? $_POST['date'] : date('Y-m-d');
        $ampm = (isset($_POST['time'])) ? $_POST['time'] : 'morning';
        $sql='INSERT INTO hwf_dataset VALUES(null,"' . $userid . '","' . $kg . '","' . $fat . '","'.$date.'","'.$ampm.'",NOW(),NOW())';
        print_r($sql);
        break;
}
