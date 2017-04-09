<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">COMS</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <form id="navbar-search" class="navbar-form navbar-left relative-div" role="search">
        <div class="input-group ">
	        <input type="text" id="query-input" class="form-control" placeholder="Search mailboxes...">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
          </span>

          
	      </div><!-- /input-group -->
        <div id="main-query" class="absolute-div">
          <div id="main-query-item" class="list-group">
          </div>
        </div>
      </form>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown relative-div">
          <a href="#" class="dropdown-toggle" id="view-my-notif" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell fa-lg" aria-hidden="true"></i>
            <span class="visible-xs">&nbsp;Notifications</span>
          </a>
          <ul id="notif-list" class="dropdown-menu">

          </ul>
          <div id="notif-icon-holder" class="absolute-div" style="z-index:9999">
            <i class="fa fa-circle-o-notch fa-spin fa-fw "></i>
            <span class="notif-notif hidden">9</span>
          </div>
        </li>

        <li class="dropdown">
          <a href="#" id="settings-on-nav" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-lg" aria-hidden="true"></i><span class="visible-xs">&nbsp;Settings</span></a>
          <ul  class="dropdown-menu">
            <li><a href="profile.php">Settings</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#" id="signout"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Sign out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->


  <div class="ajax-notifbar" style="display:none">
  </div>
</nav>