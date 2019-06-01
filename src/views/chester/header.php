<?php

global $db;

require 'GlobalHead.php';

if (!isset($_SESSION['AlphaBeta'])) {
 if (rand() < 0.5) {
   $_SESSION['AlphaBeta'] = false;
 } else {
   $_SESSION['AlphaBeta'] = true;
 }
}

$bg = "bg-white";
?>

<?php if (false /*$_SESSION['AlphaBeta']*/) { ?>
<style>
h1 {
  background: #bd0000;
  padding: 1rem;
  color: #fff;
  width: max-content;
  margin: 3rem 0 0 0;
}
p.lead {
  background: #bd0000;
  padding: 1rem;
  color: #fff;
  width: max-content;
  margin: 0 0 3rem 0;
}
</style>
<?php } ?>

<body class="<?=$bg?> account--body">

  <div class="sr-only sr-only-focusable">
    <a href="#maincontent">Skip to main content</a>
  </div>

  <div class="d-print-none">

    <?php if (isset($_SESSION['UserSimulation'])) { ?>
      <div class="bg-secondary text-white box-shadow py-2 d-print-none">
        <div class="<?=$container_class?>">
          <p class="mb-0">
            <strong>
              You are in User Simulation Mode simulating <?=
              $_SESSION['UserSimulation']['SimUserName'] ?>
            </strong>
          </p>
          <p class="mb-0">
            <a href="<?=autoUrl("users/simulate/exit")?>" class="text-white">
              Exit User Simulation Mode
            </a>
          </p>
        </div>
      </div>
    <?php } ?>

    <noscript>
      <div class="bg-warning box-shadow py-3 d-print-none">
        <div class="<?=$container_class?>">
          <p class="h2">
            <strong>
              JavaScript is disabled or not supported
            </strong>
          </p>
          <p>
      	    It looks like you've got JavaScript disabled or your browser does
      	    not support it. JavaScript is essential for our website to function
      	    properly so we recommend you enable it or upgrade to a browser which
      	    supports it as soon as possible. <strong><a class="text-dark"
      	    href="http://browsehappy.com/" target="_blank">Upgrade your browser
      	    today <i class="fa fa-external-link"
      	    aria-hidden="true"></i></a></strong>.
          </p>
          <p class="mb-0">
            If JavaScript is not supported by your browser, <?=CLUB_NAME?>
            recommends you <strong><a class="text-dark"
            href="https://www.firefox.com">install Firefox by
            Mozilla</a></strong>.
          </p>
        </div>
      </div>
    </noscript>

    <!--[if IE]>
    <div class="bg-dark text-white box-shadow py-3 d-print-none">
      <div class="<?=$container_class?>">
        <p class="h2">
          <strong>
            Internet Explorer is not supported
          </strong>
        </p>
        <p>
          It looks like you're using Internet Explorer which we no longer
          support so we recommend you upgrade to a new browser which we do
          support as soon as possible. <strong><a href="http://browsehappy.com/"
          class="text-white" target="_blank">Upgrade your browser today <i
          class="fa fa-external-link" aria-hidden="true"></i></a></strong>.
        </p>
        <p class="mb-0">
          <?=CLUB_NAME?> recommends you <strong><a class="text-white"
          href="https://www.firefox.com">install Firefox by
          Mozilla</a></strong>.
        </p>
      </div>
    </div>
    <![endif]-->

    <?php
    $edit_link = null;
    if (!$people) {
      $edit_link = autoUrl("posts/" . $allow_edit_id . "/edit");
    } else if ($people && $page_is_mine) {
      $edit_link = autoUrl("people/me");
    }

    if ($allow_edit && (($_SESSION['AccessLevel'] != "Parent" &&
    $_SESSION['AccessLevel'] != "Coach" && $edit_link != null) || $page_is_mine)) { ?>
      <div class="bg-dark text-white box-shadow py-2 d-print-none">
        <div class="<?=$container_class?>">
          <p class="mb-0">
            <a href="<?=$edit_link?>" class="text-white">
              Edit this page
            </a>
          </p>
        </div>
      </div>
    <?php } ?>

    <div class="text-white py-2 top-bar bg-primary-dark hide-a-underline" style="font-size:0.875rem;">
      <div class="<?=$container_class?> d-flex">
        <div class="mr-auto">
          <span class="mr-2">
            <a href="https://www.twitter.com/CLSASC" target="_blank" class="text-white" title="Twitter">
              <i class="fa fa-twitter fa-fw" aria-hidden="true"></i>
              <span class="sr-only">Chester-le-Street ASC on Twitter</span>
            </a>
          </span>

          <span class="mr-2">
            <a href="https://www.facebook.com/CLSASC" target="_blank" class="text-white" title="Facebook">
              <i class="fa fa-facebook fa-fw" aria-hidden="true"></i>
              <span class="sr-only">Chester-le-Street ASC on Facebook</span>
            </a>
          </span>
        </div>

        <span class="d-flex" id="top-bar-visible">
        </span>

        <?php if ($_SESSION['LoggedIn']) { ?>
        <span class="d-none" id="top-bar-login-status">1</span>
        <?php } else { ?>
        <span class="d-none" id="top-bar-login-status">0</span>
        <?php } ?>

        <div class="ml-2 top-bar d-lg-none">
          <span>
            <a data-toggle="collapse" href="#mobSearch" role="button" aria-expanded="false" aria-controls="mobSearch" class="text-white" title="Search the site">
              Search
            </a>
          </span>
        </div>

        <div class="ml-2 top-bar">
          <span>
            <a id="top-bar-more-link" href="#top-bar-more" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="top-bar-more" class="text-white d-none" title="More Links">
              More <i class="fa fa-caret-down" aria-hidden="true"></i>
            </a>
          </span>
        </div>
      </div>
    </div>

    <div class="collapse " id="top-bar-more">
      <div class="bg-primary-dark py-2 border-top border-white hide-a-underline" style="font-size:0.875rem;">
        <div class="<?=$container_class?>">
          <div id="top-bar-more-content">
          </div>
        </div>
      </div>
    </div>

    <div class="collapse" id="mobSearch">
      <div class="text-white py-3 d-lg-none bg-primary-darker">
        <form class="container" action="https://www.chesterlestreetasc.co.uk" id="head-search" method="get">
          <label for="s" class="sr-only">Search</label>
          <div class="input-group">
            <input class="form-control bg-primary text-white border-primary" id="s" name="s" placeholder="Search the site" type="search">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
                <span class="sr-only">
                  Search
                </span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="text-white py-3 d-none d-lg-flex bg-primary-darker">
      <div class="<?=$container_class?>">
        <div class="row align-items-center">
          <div class="col">
            <a class="logowhite" href="<?=autoUrl("")?>" title="Membership Dashboard"></a>
          </div>
          <div class="col d-none d-lg-flex">
            <p class="lead mb-0 ml-auto text-right">Club Membership</p>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-primary">
      <div class="<?=$container_class?>">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary
    d-print-none justify-content-between px-0" <?php if ($use_website_menu) { ?>id="club-menu"<?php } ?> role="navigation">

        <a class="navbar-brand d-lg-none" href="<?php echo autoUrl("") ?>">
          <?php if ($_SESSION['AccessLevel'] == "Parent") { ?>
            <img src="<?php echo autoUrl("public/img/chesterIcon.svg"); ?>" width="20" height="20"> My Membership
          <?php } else { ?>
            <img src="<?php echo autoUrl("public/img/chesterIcon.svg"); ?>" width="20" height="20"> Club Membership
          <?php } ?>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#chesterNavbar" aria-controls="chesterNavbar"
        aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

      <?php if (!$use_website_menu) { ?>
  	  <div class="collapse navbar-collapse offcanvas-collapse" id="chesterNavbar">
      <?php if (!user_needs_registration($_SESSION['UserID'])) { ?>
  		<ul class="navbar-nav mr-auto">
  		<?php if (!empty($_SESSION['LoggedIn'])) { ?>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("") ?>">Home</a>
  		  </li>
        <?php if ($_SESSION['AccessLevel'] == "Parent") { ?>
          <?php
          $getSwimmers = $db->prepare("SELECT MForename Name, MSurname Surname, MemberID ID FROM `members` WHERE `UserID` = ? ORDER BY Name ASC, Surname ASC");
          $getSwimmers->execute([$_SESSION['UserID']]);
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="swimmersDropdown"
            role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
              My Swimmers
            </a>
            <div class="dropdown-menu" aria-labelledby="swimmersDropdown">
              <a class="dropdown-item" href="<?php echo autoUrl("swimmers") ?>">Swimmers Home</a>
              <?php if ($swimmer = $getSwimmers->fetch(PDO::FETCH_ASSOC)) { ?>
              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header">My Swimmers</h6>
              <?php do { ?>
                <a class="dropdown-item" href="<?=autoUrl("swimmers/" . $swimmer['ID'])?>">
                  <?=htmlspecialchars($swimmer['Name'] . " " . $swimmer['Surname'])?>
                </a>
              <?php } while ($swimmer = $getSwimmers->fetch(PDO::FETCH_ASSOC)); ?>
              <?php } else { ?>
                <a class="dropdown-item" href="<?=autoUrl("myaccount/addswimmer")?>">Add a swimmer</a>
              <?php } ?>
            </div>
          </li>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("emergencycontacts") ?>">Emergency Contacts</a>
  		  </li>
        <!--<li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("renewal") ?>">
            2019 Membership Renewal
          </a>
  		  </li>-->
        <?php }
        else { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="swimmerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Swimmers &amp; Squads
            </a>
            <div class="dropdown-menu" aria-labelledby="swimmerDropdown">
              <a class="dropdown-item" href="<?php echo autoUrl("swimmers")?>">Swimmer Directory</a>
              <?php if ($_SESSION['AccessLevel'] == "Admin") { ?>
              <a class="dropdown-item" href="<?php echo autoUrl("swimmers/addmember")?>">Add Member</a>
              <?php } ?>
              <?php if ($_SESSION['AccessLevel'] != "Galas") { ?>
              <a class="dropdown-item" href="<?php echo autoUrl("squads")?>">Squads</a>
          		<a class="dropdown-item" href="<?php echo autoUrl("squads/moves")?>">Squad Moves</a>
              <?php } ?>
              <a class="dropdown-item" href="<?php echo autoUrl("swimmers/accesskeys")?>">Access Keys</a>
              <?php if ($_SESSION['AccessLevel'] == "Admin") { ?>
              <a class="dropdown-item" href="<?php echo autoUrl("renewal")?>">Membership Renewal</a>
              <a class="dropdown-item" href="<?php echo autoUrl("swimmers/orphaned")?>">Orphan Swimmers</a>
              <?php } ?>
              <?php if ($_SESSION['AccessLevel'] == "Coach") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/squads/" . date("Y/m")) ?>">
                Squad Fee Payments, <?=date("F Y")?>
              </a>
              <?php
              $lm = date("Y/m", strtotime("first day of last month"));
              $lms = date("F Y", strtotime("first day of last month"));
              ?>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/squads/" . $lm) ?>">
                Squad Fee Payments, <?=$lms?>
              </a>
              <?php } ?>
            </div>
    		  </li>
          <?php if ($_SESSION['AccessLevel'] == "Admin" ||
          $_SESSION['AccessLevel'] == "Coach" || $_SESSION['AccessLevel'] ==
          "Committee") { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="swimmerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Registers
            </a>
            <div class="dropdown-menu" aria-labelledby="registerDropdown">
              <a class="dropdown-item" href="<?php echo autoUrl("attendance")?>">Attendance Home</a>
              <a class="dropdown-item" href="<?php echo autoUrl("attendance/register")?>">Take Register</a>
              <?php if ($_SESSION['AccessLevel'] == "Admin" || $_SESSION['AccessLevel'] == "Committee") {?>
              <a class="dropdown-item" href="<?php echo autoUrl("attendance/sessions")?>">Manage Sessions</a>
              <a class="dropdown-item" href="<?=autoUrl("attendance/venues")?>">Manage Venues</a>
              <?php } ?>
              <a class="dropdown-item" href="<?php echo autoUrl("attendance/history")?>">Attendance History</a>
              <a class="dropdown-item" href="https://www.chesterlestreetasc.co.uk/squads/" target="_blank">Timetables</a>
            </div>
    		  </li>
          <?php } ?>
          <?php if ($_SESSION['AccessLevel'] == "Admin" ||
          $_SESSION['AccessLevel'] == "Galas") { ?>
          <li class="nav-item">
    			  <a class="nav-link" href="<?php echo autoUrl("users") ?>">Users</a>
    		  </li>
          <?php } ?>
          <?php if ($_SESSION['AccessLevel'] == "Galas") { ?>
          <li class="nav-item">
    			  <a class="nav-link" href="<?php echo autoUrl("payments") ?>">Pay</a>
    		  </li>
          <?php } ?>
          <?php if ($_SESSION['AccessLevel'] == "Admin") { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="paymentsAdminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Pay
            </a>
            <div class="dropdown-menu" aria-labelledby="paymentsAdminDropdown">
              <a class="dropdown-item" href="<?php echo autoUrl("payments") ?>">Payments Home</a>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history") ?>">Payment Status</a>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/extrafees")?>">Extra Fees</a>
              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header"><?php echo date("F Y"); ?></h6>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/squads/" . date("Y/m")) ?>">
                Squad Fees
              </a>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/extras/" . date("Y/m")) ?>">
                Extra Fees
              </a>
              <?php
              $lm = date("Y/m", strtotime("first day of last month"));
              $lms = date("F Y", strtotime("first day of last month"));
              ?>
              <h6 class="dropdown-header"><?php echo $lms; ?></h6>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/squads/" . $lm) ?>">
                Squad Fees
              </a>
              <a class="dropdown-item" href="<?php echo autoUrl("payments/history/extras/" . $lm) ?>">
                Extra Fees
              </a>
              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header">GoCardless Accounts</h6>
              <a class="dropdown-item" href="https://manage.gocardless.com" target="_blank">
                Live
              </a>
              <a class="dropdown-item" href="https://manage-sandbox.gocardless.com" target="_blank">
                Sandbox
              </a>
            </div>
          </li>
          <?php } ?>
          <?php if ($_SESSION['AccessLevel'] == "Admin" || $_SESSION['AccessLevel'] == "Coach") { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notifyDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Notify
            </a>
            <div class="dropdown-menu" aria-labelledby="notifyDropdown">
              <a class="dropdown-item" href="<?php echo autoUrl("notify")?>">Notify Home</a>
          		<a class="dropdown-item" href="<?php echo autoUrl("notify/newemail")?>">New Message</a>
              <a class="dropdown-item" href="<?php echo autoUrl("notify/lists")?>">Targeted Lists</a>
              <?php if ($_SESSION['AccessLevel'] == "Admin") { ?>
              <a class="dropdown-item" href="<?php echo autoUrl("notify/sms")?>">SMS Lists</a>
          		<a class="dropdown-item" href="<?php echo autoUrl("notify/email")?>">Pending Messages</a>
              <?php } ?>
              <a class="dropdown-item" href="<?php echo autoUrl("notify/history")?>">Previous Messages</a>
            </div>
          </li>
          <?php } ?>
        <?php } ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="galaDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Galas
          </a>
          <div class="dropdown-menu" aria-labelledby="galaDropdown">
            <a class="dropdown-item" href="<?php echo autoUrl("galas")?>">Gala Home</a>
            <?php if ($_SESSION['AccessLevel'] == "Parent") {?>
            <a class="dropdown-item" href="<?php echo autoUrl("galas/entergala")?>">Enter a Gala</a>
            <a class="dropdown-item" href="<?php echo autoUrl("galas/entries")?>">My Entries</a>
            <?php } else {?>
            <a class="dropdown-item" href="<?php echo autoUrl("galas/addgala")?>">Add Gala</a>
            <a class="dropdown-item" href="<?php echo autoUrl("galas/entries")?>">View Entries</a>
            <?php } ?>
            <a class="dropdown-item" href="https://www.chesterlestreetasc.co.uk/competitions/" target="_blank">Gala Website <i class="fa fa-external-link"></i></a>
            <a class="dropdown-item" href="https://www.chesterlestreetasc.co.uk/competitions/category/galas/" target="_blank">Upcoming Galas <i class="fa fa-external-link"></i></a>
            <?php if ($_SESSION['AccessLevel'] == "Parent") {?>
            <a class="dropdown-item" href="https://www.chesterlestreetasc.co.uk/competitions/enteracompetition/guidance/" target="_blank">Help with Entries <i class="fa fa-external-link"></i></a>
            <?php } ?>
          </div>
  		  </li>
        <?php if ($_SESSION['AccessLevel'] == "Parent") {
          if (!userHasMandates($_SESSION['UserID'])) { ?>
          <li class="nav-item">
    			  <a class="nav-link"  href="<?=autoUrl("payments")?>">
              Payments
    			  </a>
    		  </li>
          <?php } else { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="paymentsParentDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Pay
            </a>
            <div class="dropdown-menu" aria-labelledby="paymentsParentDropdown">
              <a class="dropdown-item" href="<?=autoUrl("payments")?>">Payments Home</a>
              <a class="dropdown-item" href="<?=autoUrl("payments/transactions")?>">My Billing History</a>
              <a class="dropdown-item" href="<?=autoUrl("payments/mandates")?>">My Bank Account</a>
              <a class="dropdown-item" href="<?=autoUrl("payments/statement/latest")?>">My Latest Statement</a>
              <a class="dropdown-item" href="<?=autoUrl("payments/fees")?>">My Fees Since Last Bill</a>
            </div>
          </li>
        <?php } } ?>
        <?php if ($_SESSION['AccessLevel'] != "Parent" &&
    		$_SESSION['AccessLevel'] != "Coach") { ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="postDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Posts
          </a>
          <div class="dropdown-menu" aria-labelledby="postDropdown">
            <a class="dropdown-item" href="<?php echo autoUrl("posts")?>">Home</a>
        		<a class="dropdown-item" href="<?php echo autoUrl("posts/new")?>">New Page</a>
        		<?php if ($allow_edit && (($_SESSION['AccessLevel'] != "Parent" &&
            $_SESSION['AccessLevel'] != "Coach" && $edit_link != null) || $page_is_mine)) { ?>
        		<a class="dropdown-item" href="<?=$edit_link?>">Edit Current Page</a>
        		<?php } ?>
        		<?php if ($exit_edit && $_SESSION['AccessLevel'] != "Parent" &&
        		$_SESSION['AccessLevel'] != "Coach") { ?>
        		<a class="dropdown-item" href="<?=autoUrl("posts/" . $id)?>">View Page</a>
        		<?php } ?>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="trialDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Trials
          </a>
          <div class="dropdown-menu" aria-labelledby="trialDropdown">
            <a class="dropdown-item" href="<?=autoUrl("trials")?>">Requested Trials</a>
            <a class="dropdown-item" href="<?=autoUrl("trials/accepted")?>">Accepted Swimmers</a>
          </div>
        </li>
  		  <?php }
          } ?>
  		  <?php if (empty($_SESSION['LoggedIn'])) { ?>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("login") ?>">Login</a>
  		  </li>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("register") ?>">Create Account</a>
  		  </li>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("timeconverter") ?>">Time Converter</a>
  		  </li>
        <li class="nav-item">
  			  <a class="nav-link" href="<?php echo autoUrl("services/request-a-trial") ?>">Request a Trial</a>
  		  </li>
        <?php } ?>
  		</ul>
      <?php if (!empty($_SESSION['LoggedIn'])) {
        global $currentUser;
        $user_name = str_replace(' ', '&nbsp;', htmlspecialchars($currentUser->getName())); ?>
      <ul class="navbar-nav">
        <!--<a class="btn btn-sm btn-outline-light my-2 my-sm-0" href="<?=autoUrl("logout")?>">Logout</a>-->
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <?= $user_name ?> <i class="fa fa-user-circle-o" aria-hidden="true"></i>
        </a>
          <div class="dropdown-menu dropdown-menu-right">
            <span class="dropdown-item-text">Signed&nbsp;in&nbsp;as&nbsp;<strong><?= $user_name ?></strong></span>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount") ?>">Your Profile</a>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/email") ?>">Your Email Options</a>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/general") ?>">Your General Options</a>
            <?php if ($_SESSION['AccessLevel'] == "Parent") { ?>
              <a class="dropdown-item" href="<?php echo autoUrl("emergencycontacts") ?>">Your Emergency Contacts</a>
            <?php } ?>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/password") ?>">Your Password</a>
            <?php if ($_SESSION['AccessLevel'] == "Parent") { ?>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/notifyhistory") ?>">Your Message History</a>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/addswimmer") ?>">Add a Swimmer</a>
            <?php } ?>
            <?php if ($_SESSION['AccessLevel'] != "Parent") { ?>
            <a class="dropdown-item" href="<?php echo autoUrl("people/me") ?>">Your Personal Page</a>
            <?php } ?>
            <a class="dropdown-item" href="<?php echo autoUrl("myaccount/loginhistory") ?>">Your Login History</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" target="_blank" href="https://www.chesterlestreetasc.co.uk/support/onlinemembership/">Help</a>
            <a class="dropdown-item" href="<?= autoUrl("logout") ?>">Logout</a>
          </div>
        </li>
      </ul>
      <?php }
      }?>
    </div>
  </nav>
  <?php } ?>
  </div>

</div>

<div id="maincontent"></div>

<!-- END OF HEADERS -->
<div class="mb-3"></div>

<div class="have-full-height" style="min-height:70vh">
