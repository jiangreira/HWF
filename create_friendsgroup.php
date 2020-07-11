<?php
$thispage = "create_friendsgroup";
require_once("library.php");
include(_includes_ . "/navbar.php");
if (!isset($_SESSION["user"])) header("Location:login.php");
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>群組管理</title>
    <style>
        td {
            line-height: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="container mt-3">
            <h4 class="mt-3 mb-3">群組管理</h4>
            <p></p>
            <button class="btn btn-primary" data-toggle="modal" data-target="#creategroup">新增群組</button>
            <button class="btn btn-primary" onclick="opensearchgroup()">搜尋群組</button>
            <hr>
        </div>
        <div class="container">
            <h5>我的群組</h5>
            <table class="table table-sm table-striped" id="mygroup">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>群組名</th>
                        <th>成員數</th>
                        <th>功能</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <h5>申請中的群組</h5>
            <table class="table table-sm table-striped" id="mygrouprequest"">
                <thead class=" thead-light">
                <tr>
                    <th>#</th>
                    <th>群組名(申請時間)</th>
                    <th>狀態</th>
                    <th>功能</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


    </div>
    <!-- Modal 新增群組 -->
    <div class="modal fade" id="creategroup" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">新增群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">群組名</label>
                        <input type="text" class="form-control" id="new_groupname" name="new_groupname">
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="newgroup()" class="btn btn-bg btn-info float-right">提交</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 搜尋群組-->
    <div class="modal fade" id="searchgroup" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModal">搜尋群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">群組代碼</label>
                        <input type="text" class="form-control" id="search_groupcode" name="search_groupcode">
                        <span class="searchspan text-danger"></span>
                    </div>
                    <hr>
                    <div class="form-group result_searcharea">
                        <label for="exampleInputEmail1">搜尋結果</label>
                        <table class="table table-sm table-striped" id="result_groupcode">
                            <thead class="thead-light">
                                <tr>
                                    <th>群組名</th>
                                    <th>申請</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="searchgroup()" class="btn btn-bg btn-info float-right">提交</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 管理群組 -->
    <div class="modal fade" id="managergroup" tabindex="-1" role="dialog" aria-labelledby="managerModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModal">管理群組</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-member-tab" data-toggle="tab" href="#nav-member" role="tab" aria-controls="nav-member" aria-selected="true">成員</a>
                            <a class="nav-item nav-link" id="nav-request-tab" data-toggle="tab" href="#nav-request" role="tab" aria-controls="nav-request" aria-selected="false">申請 &nbsp; <span id="managerrequest" class="badge badge-danger"></span> </a>
                        </div>
                    </nav>

                    <div class="tab-content mt-2" id="nav-tabContent">
                        <!-- 初始訊息 -->
                        <div class="tab-pane fade show active" id="nav-member" role="tabpanel" aria-labelledby="nav-member-tab">
                            <table class="table table-sm table-striped" id="man_member">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>成員</th>
                                        <th>功能</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- 好友代碼 -->
                        <div class="tab-pane fade" id="nav-request" role="tabpanel" aria-labelledby="nav-request-tab">
                            <table class="table table-sm table-striped" id="man_request">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>申請人</th>
                                        <th>功能</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="" class="btn btn-bg btn-info float-right">提交</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var arr22

        function groupshow() {
            $.ajax({
                type: "POST",
                url: "compute.php?do=groupshow",
                data: {},
                success: function(re) {
                    var arr = JSON.parse(re);
                    arr22 = arr
                    // 我的
                    if (arr["mygroup"]["msg"] == "OK") {
                        var print = "";
                        for (i = 0; i < arr["mygroup"]["data"].length; i++) {
                            var num = i + 1;
                            var MemberCount = (arr["mygroup"]["data"][i].MemberCount > 0) ? arr["mygroup"]["data"][i].MemberCount : "0";
                            console.log(arr["mygroup"]["data"][i].MemberCount)
                            if (arr["mygroup"]["data"][i].isowner == 1) { //是owner
                                var PendingCount = (arr["mygroup"]["data"][i].PendingCount > 0) ? arr["mygroup"]["data"][i].PendingCount : "";
                                print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                    <span class="badge badge-danger">Owner</span>
                                        ${arr["mygroup"]["data"][i].name}
                                    </td>
                                    <td>${MemberCount}</td>
                                    <td>
                                    <button class="groupcode btn btn-sm btn-warning text-light" data-clipboard-text="${arr["mygroup"]["data"][i].code}">複製代碼</button>
                                    <button onclick="manager(${arr["mygroup"]["data"][i].groupid})"class="btn-sm btn btn-primary">管理群組&nbsp;<span class="badge badge-danger">${PendingCount}</span></button>
                                    </td>
                                    </tr>`;
                            } else { //不是owner
                                print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                        ${arr["mygroup"]["data"][i].name}
                                    </td>
                                    <td>${MemberCount}</td>
                                    <td>
                                    <button class="groupcode btn btn-sm btn-warning text-light" data-clipboard-text="${arr["mygroup"]["data"][i].code}">複製代碼</button>
                                    <button class="btn-sm btn btn-danger" onclick="outgroup('user',${arr["mygroup"]["data"][i].groupid})">退出</button>
                                    </td>
                                    </tr>`;
                            }

                        }
                        $("#mygroup tbody").html(print);
                    } else if (arr["mygroup"]["msg"] == "nodata") {
                        var print = `<tr><td colspan="4" class="text-center">無資料</td></tr>`;
                        $("#mygroup tbody").html(print);
                    }

                    // 申請中的
                    if (arr["myrequestgroup"]["msg"] == "OK") {
                        var print = "";
                        for (i = 0; i < arr["myrequestgroup"]["data"].length; i++) {
                            var num = i + 1;
                            var status = '';
                            if (arr["myrequestgroup"]["data"][i]['status'] == 'Pending') {
                                status = '等待送審中';
                            } else if (arr["myrequestgroup"]["data"][i]['status'] == 'Denied') {
                                status = '申請已被拒絕';
                            } else if (arr["myrequestgroup"]["data"][i]['status'] == 'Out') {
                                status = '已被群主踢出';
                            }
                            print += `<tr>
                                    <td>${num}</td>
                                    <td>
                                        ${arr["myrequestgroup"]["data"][i].name} \t
                                        (${arr["myrequestgroup"]["data"][i].time})
                                    </td>
                                    <td>${status}</td>
                                    <td>
                                    <button class="btn btn-sm btn-danger text-light" onclick="delgrouprequest(${arr["myrequestgroup"]["data"][i].groupid})"><span class="material-icons">delete</span></button>
                                    </td>
                                    </tr>`;
                        }
                        $("#mygrouprequest tbody").html(print);
                    } else if (arr["myrequestgroup"]["msg"] == "nodata") {
                        var print = `<tr><td colspan="4" class="text-center">無資料</td></tr>`;
                        $("#mygrouprequest tbody").html(print);
                    }
                }
            });
        }
        groupshow()

        // copycode
        var clipboard = new ClipboardJS(".groupcode");
        clipboard.on("success", function(e) {
            e.clearSelection();
            alert(`"${e.text}" 複製成功`);
        });

        // 新增群組
        function newgroup() {
            var new_groupname = $("#new_groupname").val();
            $.ajax({
                type: "POST",
                url: "compute.php?do=newgroup",
                data: {
                    new_groupname
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr["msg"] == "OK") {
                        $("#creategroup").modal("hide")
                        $("#new_groupname").val("");
                        alert(`新增成功`)
                        groupshow();
                    } else if (arr["msg"] == "err") {
                        alert(`${arr["txt"]}`)
                    }
                }
            });
        }

        // open search modal
        function opensearchgroup() {
            $("#result_groupcode tbody").html();
            $(".searchspan").text("")
            $("#search_groupcode").val("")
            $(".result_searcharea").hide();
            $("#searchgroup").modal("show");
        }
        // 搜尋群組
        function searchgroup() {
            var code = $("#search_groupcode").val();
            $.ajax({
                type: "POST",
                url: "compute.php?do=searchgroup",
                data: {
                    code
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    console.log(arr);
                    if (arr["msg"] == "OK") {
                        var print = `<tr>
                                    <td>
                                        ${arr["data"][0].name} (群組擁有者:${arr["data"][0].owner})
                                        <input type="hidden" name="result_search_groupcode" value="${arr["data"][0].id}">
                                    </td>
                                    <td>
                                    <button class="btn btn-sm btn-danger"onclick="sendrequest(this,${arr["data"][0].id})"><span class="material-icons">send</span></button>
                                    &nbsp; &nbsp;
                                    <span class="sendresult"></span>
                                    </td>
                                    </tr>`;

                        $("#result_groupcode tbody").html(print);
                        $(".result_searcharea").show();
                    } else if (arr["msg"] == "isexist") {
                        if (arr["data"][0].isauth == 'Pending') {
                            $(".searchspan").text("申請中")
                        } else if (arr["data"][0].isauth == 'Owner') {
                            $(".searchspan").text("已擁有該群組")
                        } else if (arr["data"][0].isauth == 'Out') {
                            $(".searchspan").text("已被踢出")
                        } else if (arr["data"][0].isauth == 'Member') {
                            $(".searchspan").text("已是成員")
                        }
                    } else if (arr["msg"] == "owner") {
                        $(".searchspan").text("已擁有該群組")
                    } else if (arr["msg"] == "err") {
                        alert(`${arr["txt"]}`)
                    }
                }
            });
        }

        function sendrequest(where, who) {
            var id = who;
            $.ajax({
                type: "POST",
                url: "compute.php?do=sendGroupRequest",
                data: {
                    id
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr["msg"] == "OK") {
                        $(".sendresult").text("已發送申請")
                        $(where).attr("disabled", "disabled");
                    } else {
                        //     $(who).siblings("span").text(`${arr["txt"]}`)
                        //     $(who).parent().find(".txt").text("邀請已存在")
                        //     $(who).attr("disabled", "disabled");

                    }
                }
            });
        }
        // 管理群組
        var man;

        function manager(who) {
            var groupid = who;
            $.ajax({
                type: "POST",
                url: "compute.php?do=findgroup",
                data: {
                    groupid
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    man = arr;
                    if (arr["msg"] == "OK") {
                        var member, request = "";
                        if (arr.member.length > 0) {
                            for (i = 0; i < arr.member.length; i++) {
                                var num = i + 1;
                                member += `
                                        <tr>
                                            <td>${num}</td>
                                            <td>${arr.member[i].name}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="outgroup('managerout',${groupid},${arr.member[i].userid})"><span class="material-icons">highlight_off</span></button>
                                            </td>
                                        </tr>`;
                            }

                            $("#man_member tbody").html(member);
                        } else {
                            member += `<tr><td class="text-center" colspan="3">無資料</td></tr>`;
                            $("#man_member tbody").html(member);
                        }

                        if (arr.request.length > 0) {
                            for (i = 0; i < arr.request.length; i++) {
                                var num = i + 1;
                                request += `
                                        <tr>
                                            <td>${num}</td>
                                            <td>${arr.request[i].name}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="grouprequest('succ',${arr.request[i].userid},${groupid})"><span class="material-icons">check_circle_outline</span></button>
                                                <button class="btn btn-sm btn-danger" onclick="grouprequest('denied',${arr.request[i].userid},${groupid})"><span class="material-icons">highlight_off</span></button>
                                            </td>
                                        </tr>`;
                            }
                            $('#managerrequest').text(arr.request.length)
                            $("#man_request tbody").html(request);
                        } else {
                            request += `<tr><td class="text-center" colspan="3">無資料</td></tr>`;
                            $("#man_request tbody").html(request);
                        }
                        $("#managergroup").modal("show")
                    } else {
                        groupshow()
                    }
                }
            });
        }

        // 刪除我方申請
        function delgrouprequest(who) {
            var ans = confirm("確定要刪除")
            if (ans) {
                var delid = who
                $.ajax({
                    type: "POST",
                    url: "compute.php?do=delgrouprequest",
                    data: {
                        delid
                    },
                    success: function(re) {
                        var arr = JSON.parse(re);
                        if (arr["msg"] == "OK") {
                            alert("刪除成功")
                            groupshow();
                        } else {
                            alert("Something wrong,請重新再試一遍!!");
                        }
                    }
                });
            }

        }
        //request
        function grouprequest(what, who, group) {
            var userid = who;
            var groupid = group;
            var method_name = what;
            $.ajax({
                type: "POST",
                url: "compute.php?do=GroupRequest",
                data: {
                    userid,
                    groupid,
                    method_name
                },
                success: function(re) {
                    var arr = JSON.parse(re);
                    if (arr["msg"] == "OK") {
                        $('#managerrequest').text("");
                        manager(groupid);
                        groupshow();
                    } else if (arr["msg"] == "err") {
                        alert(`${arr["txt"]}`);
                        manager(groupid);
                        groupshow();
                    }
                }
            });
        }
        // 退出
        function outgroup(useris, group, who = "") {
            var method_name = useris
            var groupid = group
            var userid = who
            if (method_name == 'managerout') {
                var ans = confirm('確定要踢出?')
            } else {
                var ans = confirm('確定要退出?')
            }
            console.log(useris, group, who)
            if (ans) {
                $.ajax({
                    type: "POST",
                    url: "compute.php?do=outgroup",
                    data: {
                        method_name,
                        groupid,
                        userid
                    },
                    success: function(re) {
                        var arr = JSON.parse(re);
                        if (arr["msg"] == "OK") {
                            if (method_name == 'managerout') {
                                manager(groupid)
                            }
                            groupshow();
                        } else {
                            alert(`${arr['txt']}`)
                        }
                    }
                });
            }

        }
    </script>
</body>

</html>