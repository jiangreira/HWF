<nav class="navbar navbar-expand-lg navbar-dark bg-info">
<a class="navbar-brand" href="health_list.php">HWF <span class='text-dark'>&nbsp; &nbsp;<?='Hi,'.$_SESSION['username'].'!'?></span></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?=($thispage=='health_list')?'active':''?>">
        <a class="nav-link " href="health_list.php"> 近一週紀錄 <span class=" sr-only">(current)</span></a>
      </li>
      <li class="nav-item <?=($thispage=='member')?'active':''?>">
        <a class="nav-link" href="member.php">基本資訊</a>
      </li>
      <li class="nav-item <?=($thispage=='health_create')?'active':''?>">
        <a class="nav-link" href="health_create.php">新增紀錄</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="friends_group">好友群組</a>
      </li> -->
      <li class="nav-item dropdown  <?=($thispage=='create_friendsgroup')?'active':''?>">
        <a class="nav-link dropdown-toggle" id="friendsgroup" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          好友群組
        </a>
        <div class="dropdown-menu" aria-labelledby="friendsgroup">
          <a class="dropdown-item " href="create_friendsgroup.php">群組管理</a>
        </div>
      </li>
    </ul>
    <a class="btn btn-outline-light" href="compute.php?do=logout">登出</a>
  </div>
</nav>