<?php
session_start();
$db = new PDO("mysql:host=localhost;dbname=s1080407;charset=utf8", "root", "");
function jlo($link)
{
    return "location.href='" . $link . "'";
    //外面要自己加上<script>
}
function getword($num)
{
    $length = $num;
    $password = '';
    $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $str = strlen($word);

    for ($i = 0; $i < $length; $i++) {
        $password .= $word[rand() % $str];
    }
    return $password;
}

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
                echo "<script>alert('登入成功');" . jlo("health_list.php") . "</script>";
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
        $sql = 'INSERT INTO hwf_dataset VALUES(null,"' . $userid . '","' . $kg . '","' . $fat . '","' . $date . '","' . $ampm . '",NOW(),NOW())';
        if ($db->query($sql)) {
            return header('Location:health_list.php');
        };
        break;
    case 'list7ds':
        $userid = $_SESSION['user'];
        $today = date('Y-m-d');
        $yesterday = date("Y-m-d", strtotime($today . "-6 day"));
        $sql = 'SELECT * FROM hwf_dataset WHERE date >= "' . $yesterday . '" AND date <= "' . $today . '" AND userid="' . $userid . '" ORDER BY date desc,ampm asc';
        $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) echo 'no';
        $data = array();
        $findheight = $db->query('SELECT height FROM hwf_userinfo WHERE id=' . $userid)->fetch(PDO::FETCH_ASSOC);
        $height = $findheight['height'];
        foreach ($rows as $key => $row) {
            $data[] = array(
                'id' => $row['id'],
                'kg' => $row['kg'],
                'fat' => $row['fat'],
                'date' => $row['date'],
                'ampm' => $row['ampm'],
                'bmi' => round(($row['kg'] / ($height * $height)) * 10000, 2)
            );
        }
        $newdata = json_encode($data);
        echo $newdata;
        break;
    case 'deldataset':
        $id = $_POST['id'];
        $sql = 'DELETE FROM hwf_dataset WHERE id=' . $id;
        $rows = $db->query($sql);
        if ($rows) {
            echo 'OK';
        } else {
            echo 'Fail';
        }
        break;


    case 'listsearch':
        $userid = $_SESSION['user'];
        $startdate = $_POST['startdate'];
        $enddate = $_POST['enddate'];
        $sql = 'SELECT * FROM hwf_dataset WHERE date >= "' . $startdate . '" AND date <= "' . $enddate . '" AND userid="' . $userid . '" ORDER BY date desc,ampm asc';
        $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) echo 'no';
        $findheight = $db->query('SELECT height FROM hwf_userinfo WHERE id=' . $userid)->fetch(PDO::FETCH_ASSOC);
        $height = $findheight['height'];
        $data = array();
        foreach ($rows as $key => $row) {
            $data[] = array(
                'id' => $row['id'],
                'user' => $row['userid'],
                'kg' => $row['kg'],
                'fat' => $row['fat'],
                'date' => $row['date'],
                'ampm' => $row['ampm'],
                'bmi' => round(($row['kg'] / ($height * $height)) * 10000, 2)
            );
        }
        $newdata = json_encode($data);
        echo $newdata;
        break;
    case 'basicedit':
        $id = $_SESSION['user'];
        if ($id) {
            $isexist = $db->query('SELECT * FROM hwf_userinfo WHERE id=' . $id)->fetchAll(PDO::FETCH_ASSOC);
            $height = isset($_POST['height']) ? $_POST['height'] : 1;
            $kg = isset($_POST['kg']) ? $_POST['kg'] : 1;
            $fat = isset($_POST['fat']) ? $_POST['fat'] : 1;
            if (!$isexist) {
                $db->query('INSERT INTO hwf_userinfo(id,updatetime) VALUES("' . $id . '",NOW())');
            }
            if (!empty($height)) {
                $db->query('UPDATE hwf_userinfo SET height=' . $height . ',updatetime=NOW() WHERE id=' . $id);
            }
            if (!empty($kg)) {
                $db->query('UPDATE hwf_userinfo SET kg=' . $kg . ',updatetime=NOW() WHERE id=' . $id);
            }
            if (!empty($fat)) {
                $db->query('UPDATE hwf_userinfo SET fat=' . $fat . ',updatetime=NOW() WHERE id=' . $id);
            }
            $arr['msg'] = 'OK';
        } else {
            $arr['msg'] = 'err';
        }
        echo json_encode($arr);
        break;
    case 'basicshow':
        $id = $_SESSION['user'];
        if ($id) {
            $result = $db->query('SELECT * FROM hwf_userinfo WHERE id=' . $id)->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $arr['msg'] = 'OK';
                $arr['data'] = $result;
            } else {
                $arr['msg'] = 'nodata';
            }
        } else {
            $arr['msg'] = 'err';
        }
        echo json_encode($arr);
        break;
    case 'getFriendCode':
        $chkuserinfo = $db->query('SELECT * FROM hwf_userinfo WHERE id=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        $chkisexist = $db->query('SELECT friend_code FROM hwf_userinfo WHERE id=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        if (count($chkisexist) != 0 && !empty($chkisexist)) {
            $arr['msg'] = 'err';
            $arr['txt'] = '代碼已存在';
        } else {
            if (!$chkuserinfo) {
                $db->query('INSERT INTO hwf_userinfo(id,updatetime) VALUES("' . $_SESSION['user'] . '",NOW())');
            }
            $code = '';
            for ($i = 0; $i < 10; $i++) {
                $code = getword(6);
                $rows = $db->query('SELECT *FROM hwf_userinfo WHERE friend_code="' . $code . '"')->fetchAll(PDO::FETCH_ASSOC);
                if (!$rows) {
                    $arr['FriendCode'] = $code;
                }
            }
            $rows1 = $db->query('UPDATE hwf_userinfo SET friend_code ="' . $code . '",updatetime=NOW() WHERE id=' . $_SESSION['user']);
            if ($rows1) {
                $arr['msg'] = 'OK';
            } else {
                $arr['msg'] = 'err';
                $arr['txt'] = '新增失敗';
            }
        }
        echo json_encode($arr);
        break;
    case 'searchFriend':
        $code = $_POST['code'];
        $chkcode = $db->query('SELECT *FROM hwf_userinfo WHERE friend_code="' . $code . '" AND id=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        if (count($chkcode) == 0) {
            $finduser = $db->query('SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_userinfo ON hwf_user.id=hwf_userinfo.id WHERE friend_code="' . $code . '"')->fetchall(PDO::FETCH_ASSOC);
            if (count($finduser) == 0) {
                $arr['msg'] = 'nodata';
            } else {
                $arr['msg'] = 'OK';
                $arr['data'] = $finduser;
            }
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '不得輸入自己代碼';
        }
        echo json_encode($arr);
        break;
    case '':
        break;
}
