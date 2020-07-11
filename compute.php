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
            // 找別人向我申請紀錄
            // SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_friends_request ON hwf_friends_request.userid=hwf_user.id WHERE hwf_friends_request.friendsid=2
            $find_friendsreq_res = $db->query('SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_friends_request ON hwf_friends_request.userid=hwf_user.id WHERE isauth="Pending" AND hwf_friends_request.friendsid=' . $id)->fetchAll(PDO::FETCH_ASSOC);
            if (count($find_friendsreq_res) > 0) {
                $arr['find_friendsreq']['msg'] = 'OK';
                foreach ($find_friendsreq_res as $value) {
                    $find_friendsreq[] = array('requestid' => $value['id'], 'requestname' => $value['name']);
                }
                $arr['find_friendsreq']['data'] = $find_friendsreq;
            } else {
                $arr['find_friendsreq']['msg'] = 'nodata';
            }
            // 找我方申請的紀錄
            // SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_friends_request ON hwf_friends_request.friendsid=hwf_user.id WHERE userid=2
            $friendsend_res = $db->query('SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_friends_request ON hwf_friends_request.friendsid=hwf_user.id WHERE isauth="Pending" AND userid=' . $id)->fetchAll(PDO::FETCH_ASSOC);
            if (count($friendsend_res) > 0) {
                $arr['friendsend']['msg'] = 'OK';
                foreach ($friendsend_res as $value) {
                    $friendsend[] = array('requestToid' => $value['id'], 'requestname' => $value['name']);
                }
                $arr['friendsend']['data'] = $friendsend;
            } else {
                $arr['friendsend']['msg'] = 'nodata';
            }
            // 找已成為朋友
            $myfirend_res = $db->query('SELECT hwf_user.id,hwf_user.name FROM hwf_user JOIN hwf_friends_request ON hwf_friends_request.friendsid=hwf_user.id WHERE isauth="Friends" AND userid=' . $id);
            if (count($myfirend_res) > 0) {
                $arr['myfirends']['msg'] = 'OK';
                foreach ($myfirend_res as $value) {
                    $myfirends[] = array('friendsid' => $value['id'], 'friendsname' => $value['name']);
                }
                $arr['myfirends']['data'] = $myfirends;
            } else {
                $arr['myfirends']['msg'] = 'nodata';
            }
            // 基本資訊
            $basicinfo = $db->query('SELECT * FROM hwf_userinfo WHERE id=' . $id)->fetchAll(PDO::FETCH_ASSOC);
            if (count($basicinfo) > 0) {
                $arr['basicinfo']['msg'] = 'OK';
                $arr['basicinfo']['data'] = $basicinfo;
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
    case 'sendFriendRequest':
        $requestToid = $_POST['requestid'];
        $chkisexist = $db->query('SELECT * FROM hwf_friends_request WHERE userid=' . $_SESSION['user'] . ' AND friendsid=' . $requestToid)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($chkisexist)) { //沒有申請過紀錄
            $authstatus = 'Pending';
            $sql = 'INSERT INTO hwf_friends_request values(null,' . $_SESSION['user'] . ',' . $requestToid . ',"' . $authstatus . '",NOW())';
            if ($db->query($sql)) {
                $arr['msg'] = 'OK';
            } else {
                $arr['msg'] = 'requestfail';
            }
        } else { //已存在申請紀錄
            $arr['msg'] = 'isexist';
            $arr['txt'] = $chkisexist[0]['isauth'];
        }
        echo json_encode($arr);
        break;
    case 'delfriendsend':
        $delid = $_POST['delid'];
        $sql = 'DELETE FROM hwf_friends_request WHERE friendsid=' . $delid . ' AND isauth="Pending" AND userid=' . $_SESSION['user'];
        $ssql = 'SELECT * FROM hwf_friends_request WHERE friendsid=' . $delid . ' AND isauth="Pending" AND userid=' . $_SESSION['user'];
        $chkisexist = $db->query($ssql)->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($chkisexist)) {
            if ($db->query($sql)) {
                $arr['msg'] = 'OK';
            } else {
                $arr['msg'] = 'err';
                $arr['txt'] = '刪除失敗';
            }
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '不存在的申請或是已通過對方申請';
        }
        echo json_encode($arr);
        break;
    case 'agreequest':
        $agreeid = $_POST['agreeid'];
        $authstatus = 'Friends';
        // 確認我有沒有加對方 有的話也一起修改 並一起增加好友
        $sql = 'SELECT * FROM hwf_friends_request WHERE userid=' . $_SESSION['user'] . ' AND friendsid=' . $agreeid;
        $chkuser_request = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($chkuser_request)) {
            $bothupdate = 1; //我也有加對方，修改我方request
            $sql2 = 'UPDATE hwf_friends_request SET updatetime=NOW(),isauth="' . $authstatus . '" WHERE userid=' . $_SESSION['user'] . ' AND friendsid=' . $agreeid;
            $user_result = ($db->query($sql2)) ? 1 : 0;
        } else {
            $bothupdate = 0; // 我沒有加對方，新增一個request isauth=Friends
            $sql3 = 'INSERT INTO hwf_friends_request values(null,' . $_SESSION['user'] . ',' . $agreeid . ',"' . $authstatus . '",NOW())';
            $other_result = ($db->query($sql3)) ? 1 : 0;
        }
        // 只有對方的request
        $sql4 = 'UPDATE hwf_friends_request SET updatetime=NOW(),isauth="' . $authstatus . '" WHERE userid=' . $agreeid . ' AND friendsid=' . $_SESSION['user'];
        $fsqlstatus = ($db->query($sql4)) ? 1 : 0;
        if ($bothupdate == 1) {
            if (($user_result == 1) && ($fsqlstatus == 1)) {
                $arr['msg'] = 'OK';
            } else {
                $db->query('UPDATE hwf_friends_request SET updatetime=NOW(),isauth="Pending" AND userid=' . $_SESSION['user'] . ' AND friendsid=' . $agreeid);
                $db->query('UPDATE hwf_friends_request SET updatetime=NOW(),isauth="Pending" AND userid=' . $agreeid . ' AND friendsid=' . $_SESSION['user']);
                $arr['msg'] = 'err';
                $arr['txt'] = '未知錯誤';
            }
        } else {
            if (($other_result == 1) && ($fsqlstatus == 1)) {
                $arr['msg'] = 'OK';
            } else {
                $db->query('DELETE FROM hwf_friends_request WHERE userid=' . $_SESSION['user'] . ' AND friendsid=' . $agreeid);
                $db->query('UPDATE hwf_friends_request SET updatetime=NOW(),isauth="Pending" AND userid=' . $agreeid . ' AND friendsid=' . $_SESSION['user']);
                $arr['msg'] = 'err';
                $arr['txt'] = '未知錯誤';
            }
        }
        echo json_encode($arr);
        break;
    case 'denyquest':
        $denyid = $_POST['denyid'];
        $authstatus = 'Pending';
        $updatesql = 'DELETE FROM hwf_friends_request WHERE isauth="' . $authstatus . '" AND userid=' . $denyid . ' AND friendsid=' . $_SESSION['user'];
        if ($db->query($updatesql)) {
            $arr['msg'] = 'OK';
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '修改失敗';
        }
        echo json_encode($arr);
        break;
    case 'delmyfriends':
        $delid = $_POST['delid'];
        $delmyside = $db->query('DELETE FROM hwf_friends_request WHERE userid=' . $_SESSION['user'] . ' AND friendsid=' . $delid);
        $delotherside = $db->query('DELETE FROM hwf_friends_request WHERE userid=' . $delid . ' AND friendsid=' . $_SESSION['user']);
        if ($delmyside && $delotherside) {
            $arr['msg'] = 'OK';
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '刪除失敗';
        }
        echo json_encode($arr);
        break;
    case 'newgroup':
        $groupname = $_POST['new_groupname'];
        $code = '';
        for ($i = 0; $i < 10; $i++) {
            $code = getword(7);
            $rows = $db->query('SELECT *FROM hwf_userinfo WHERE friend_code="' . $code . '"')->fetchAll(PDO::FETCH_ASSOC);
            if (!$rows) {
                $arr['groupcode'] = $code;
            }
        }
        $sql = 'INSERT INTO hwf_groupinfo VALUES(null,"' . $groupname . '",' . $_SESSION['user'] . ',"' . $code . '",NOW(),NOW())';
        if ($db->query($sql)) {
            $groupinfo = $db->query('SELECT id FROM hwf_groupinfo WHERE code="' . $code . '" AND ownerid=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
            $groupid = $groupinfo[0]['id'];
            $sql2 = 'INSERT INTO hwf_group VALUES(null,' . $groupid . ',' . $_SESSION['user'] . ',"Owner",NOW(),NOW())';
            if ($db->query($sql2)) {
                $arr['msg'] = 'OK';
            } else {
                $delnewgroup = $db->query('DELETE FROM hwf_groupinfo WHERE code="' . $code . '" AND ownerid=' . $_SESSION['user']);
                $arr['msg'] = 'err';
                $arr['txt'] = '新增失敗1';
            }
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '新增失敗2';
        }
        echo json_encode($arr);
        break;
    case 'groupshow':
        $mygroup = $db->query('SELECT hwf_group.groupid,hwf_groupinfo.name,hwf_groupinfo.ownerid,hwf_groupinfo.code,hwf_group.isauth FROM hwf_group JOIN hwf_groupinfo ON hwf_groupinfo.id=hwf_group.groupid WHERE isauth IN("Owner","Member") AND hwf_group.userid=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        $group = array();
        if (count($mygroup) > 0) {
            foreach ($mygroup as $key => $value) {
                $group[$key]['groupid'] = $value['groupid'];
                $group[$key]['name'] = $value['name'];
                $group[$key]['code'] = $value['code'];
                $group[$key]['isowner'] = ($value['ownerid'] == $_SESSION['user']) ? 1 : 0;
                if ($group[$key]['isowner'] == 1) {
                    $countPending = $db->query('SELECT count(*) cnt FROM hwf_group JOIN hwf_groupinfo ON hwf_groupinfo.id=hwf_group.groupid WHERE isauth="Pending" AND ownerid=' . $_SESSION['user'] . ' AND groupid=' . $value['groupid'])->fetchAll(PDO::FETCH_ASSOC);
                    $group[$key]['PendingCount'] = $countPending[0]['cnt'];
                }
                $countMember = $db->query('SELECT count(*) cnt FROM hwf_group JOIN hwf_groupinfo ON hwf_groupinfo.id=hwf_group.groupid WHERE isauth="Member" AND groupid=' . $value['groupid'])->fetchAll(PDO::FETCH_ASSOC);
                $group[$key]['MemberCount'] = $countMember[0]['cnt'];
            }
            $arr['mygroup']['msg'] = 'OK';
            $arr['mygroup']['data'] = $group;
        } else {
            $arr['mygroup']['msg'] = 'nodata';
        }
        $myrequestgroup = $db->query('SELECT hwf_group.groupid,hwf_groupinfo.name,hwf_groupinfo.ownerid,hwf_group.isauth,hwf_group.createtime,hwf_group.isauth FROM hwf_group JOIN hwf_groupinfo ON hwf_groupinfo.id=hwf_group.groupid WHERE isauth IN("Pending","Denied","Out") AND hwf_group.userid=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        $requestgroup = array();
        if (count($myrequestgroup) > 0) {
            foreach ($myrequestgroup as $key => $value) {
                $requestgroup[$key]['groupid'] = $value['groupid'];
                $requestgroup[$key]['name'] = $value['name'];
                $requestgroup[$key]['status'] = $value['isauth'];
                $requestgroup[$key]['time'] = $value['createtime'];
            }
            $arr['myrequestgroup']['msg'] = 'OK';
            $arr['myrequestgroup']['data'] = $requestgroup;
        } else {
            $arr['myrequestgroup']['msg'] = 'nodata';
        }
        echo json_encode($arr);
        break;
    case 'searchgroup':
        $code = $_POST['code'];
        $chkisexist = $db->query('SELECT* FROM hwf_groupinfo JOIN hwf_group ON hwf_groupinfo.id=hwf_group.groupid WHERE code="' . $code . '" AND userid=' . $_SESSION['user'])->fetchAll(PDO::FETCH_ASSOC);
        if (empty($chkisexist)) {
            // groupinfo
            $groupinfo = $db->query('SELECT hwf_user.name owner,hwf_groupinfo.ownerid,hwf_groupinfo.id,hwf_groupinfo.name FROM hwf_groupinfo JOIN hwf_user ON hwf_groupinfo.ownerid=hwf_user.id WHERE code="' . $code . '"')->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($groupinfo)) {
                if ($groupinfo[0]['ownerid'] == $_SESSION['user']) {
                    $arr['msg'] = 'owner';
                } else {
                    $arr['msg'] = 'OK';
                    $arr['data'] = $groupinfo;
                }
            } else {
                $arr['msg'] = 'err';
                $arr['txt'] = '找不到該群組';
            }
        } else {
            $arr['msg'] = 'isexist';
            $arr['data'] = $chkisexist;
        }
        echo json_encode($arr);
        break;
    case 'sendGroupRequest':
        //發送群組加入申請
        $id = $_POST['id'];
        // 確認是否有邀請OR已經是member
        $chksql = 'SELECT * FROM hwf_group WHERE groupid=' . $id . ' AND userid=' . $_SESSION['user'];
        $status = 'Pending';
        $sql = 'INSERT INTO hwf_group VALUES(null,' . $id . ',' . $_SESSION['user'] . ',"' . $status . '",NOW(),NOW())';
        $request = $db->query($sql);
        if ($request) {
            $arr['msg'] = 'OK';
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '申請失敗';
        }
        echo json_encode($arr);
        break;
    case 'delgrouprequest':
        $delid = $_POST['delid'];
        $sql = 'DELETE FROM hwf_group WHERE groupid=' . $delid . ' AND userid=' . $_SESSION['user'];
        if ($db->query($sql)) {
            $arr['msg'] = 'OK';
        } else {
            $arr['msg'] = 'err';
        }
        echo json_encode($arr);
        break;
    case 'findgroup':
        $groupid = $_POST['groupid'];
        $sql = 'SELECT hwf_group.userid,hwf_user.name,hwf_group.isauth FROM hwf_group JOIN hwf_user ON hwf_user.id=hwf_group.userid WHERE groupid=' . $groupid;
        $groupinfo = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $request = array();
        $member = array();
        if (!empty($groupinfo)) {
            foreach ($groupinfo as $value) {
                if ($value['isauth'] == 'Pending') {
                    $request[] = $value;
                } elseif ($value['isauth'] == 'Member') {
                    $member[] = $value;
                }
            }
            $arr['msg'] = 'OK';
            $arr['request'] = $request;
            $arr['member'] = $member;
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '參數錯誤';
        }
        echo json_encode($arr);
        break;
    case 'GroupRequest':
        // 管理群組的申請接受拒絕
        $userid = $_POST['userid'];
        $groupid = $_POST['groupid'];
        $method_name = $_POST['method_name'];
        // chk這個申請有沒有存在
        $chkrequest = $db->query('SELECT * FROM hwf_group WHERE groupid=' . $groupid . ' AND userid=' . $userid)->fetchAll(PDO::FETCH_ASSOC);
        if ($chkrequest) {
            if ($chkrequest[0]['isauth'] == 'Pending') {
                if ($method_name == 'succ') {
                    $isauth = 'Member';
                } else if ($method_name == 'denied') {
                    $isauth = 'Denied';
                } else {
                    $arr['msg'] = 'err';
                    $arr['txt'] = '參數錯誤';
                }
                $sql = 'UPDATE hwf_group SET updatetime=NOW(),isauth="' . $isauth . '" WHERE groupid=' . $groupid . ' AND userid=' . $userid;
                if ($db->query($sql)) {
                    $arr['msg'] = 'OK';
                } else {
                    $arr['msg'] = 'err';
                    $arr['txt'] = '暫時性失敗';
                }
            } else {
                $arr['msg'] = 'err';
                $arr['txt'] = '該筆申請可能已通過或是申請者取消';
            }
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '查無該資料';
        }
        echo json_encode($arr);
        break;
    case 'outgroup':
        $method_name = $_POST['method_name'];
        $groupid = $_POST['groupid'];
        $userid = ($method_name == 'managerout') ? $_POST['userid'] : $_SESSION['user'];
        $chkisexist = $db->query('SELECT * FROM hwf_group WHERE isauth="Member" AND userid=' . $userid . ' AND groupid=' . $groupid)->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($chkisexist)) {
            $sql = '';
            if ($method_name == 'managerout') {
                $sql = 'UPDATE hwf_group SET updatetime=NOW(),isauth="Out" WHERE userid=' . $userid . ' AND groupid=' . $groupid;
            } elseif ($method_name == 'user') {
                $sql = 'DELETE FROM hwf_group WHERE userid=' . $userid . ' AND groupid=' . $groupid;
            }
            if ($db->query($sql)) {
                $arr['msg'] = 'OK';
            } else {
                $arr['msg'] = 'err';
                $arr['txt'] = '錯誤';
            }
        } else {
            $arr['msg'] = 'err';
            $arr['txt'] = '查無該筆資料';
        }
        echo json_encode($arr);
        break;
    case '':
        break;
    case '':
        break;
    case '':
        break;
    case '':
        break;
    case '':
        break;
    case '':
        break;
    case '':
        break;
}
