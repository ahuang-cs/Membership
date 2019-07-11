<?php

$fluidContainer = true;

global $db;

$getExtraEmails = null;
try {
  $getExtraEmails = $db->prepare("SELECT ID, Name, EmailAddress FROM notifyAdditionalEmails WHERE UserID = ?");
  $getExtraEmails->execute([$_SESSION['UserID']]);
} catch (Exception $e) {}

$sql = "SELECT `EmailAddress`, `EmailComms` FROM `users` WHERE `UserID` = ?";
try {
	$query = $db->prepare($sql);
	$query->execute([$_SESSION['UserID']]);
} catch (Exception $e) {
	halt(500);
}

$row = $query->fetch(PDO::FETCH_ASSOC);
//$current_email = $query->fetchColumn();

$emailChecked;
if ($row['EmailComms']) {
	$emailChecked = " checked ";
}

$emailChecked_security;
if (isSubscribed($_SESSION['UserID'], 'Security')) {
	$emailChecked_security = " checked ";
}

$emailChecked_payments;
if (isSubscribed($_SESSION['UserID'], 'Payments')) {
	$emailChecked_payments = " checked ";
}

$emailChecked_new_member;
if ($_SESSION['AccessLevel'] == "Admin" && isSubscribed($_SESSION['UserID'], 'NewMember')) {
	$emailChecked_new_member = " checked ";
}


$email = $row['EmailAddress'];

$pagetitle = "Email Options";
include BASE_PATH . "views/header.php";
  $userID = $_SESSION['UserID'];
?>
<div class="container-fluid">
  <div class="row justify-content-between">
    <div class="col-md-3 d-none d-md-block">
      <?php
        $list = new \CLSASC\BootstrapComponents\ListGroup(file_get_contents(BASE_PATH . 'controllers/myaccount/ProfileEditorLinks.json'));
        echo $list->render('email');
      ?>
    </div>
    <div class="col-md-9">
      <h1>Manage Email Options</h1>
      <p class="lead">Manage your email address and email options.</p>

    	<?php if (isset($_SESSION['OptionsUpdate']) && $_SESSION['OptionsUpdate']) { ?>
    		<div class="alert alert-success">
    			<p class="mb-0">
    				<strong>We've successfully updated your email options</strong>
    			</p>
    		</div>
    	<?php unset($_SESSION['OptionsUpdate']);
    	} ?>

    	<?php if (isset($_SESSION['EmailUpdate']) && $_SESSION['EmailUpdate']) { ?>
    		<div class="alert alert-success">
    			<p class="mb-0">
    				<strong>Just one more step to update your email address</strong>
    			</p>
    			<p class="mb-0">
    				We've sent an email to your new email address with a link in it. Please
    				follow that link to confirm your new email address.
    			</p>
    		</div>
    	<?php unset($_SESSION['EmailUpdate']);
    	} else if (isset($_SESSION['EmailUpdate'])) { ?>
    		<div class="alert alert-danger">
    			<p class="mb-0">
    				<strong>The email address provided is not valid</strong>
    			</p>
    			<p class="mb-0">
    				Please try again
    			</p>
    		</div>
    		<?php unset($_SESSION['EmailUpdate']);
    	} ?>

    	<?php if (isset($_SESSION['EmailUpdateNew'])) { ?>
    		<div class="alert alert-info">
    			<p class="mb-0">
    				<strong>Once verified, your account email
    				address will change to
    				<?=htmlentities($_SESSION['EmailUpdateNew'])?></strong>
    			</p>
    			<p class="mb-0">
    				If you need help, contact <a
    				href="mailto:support@chesterlestreetasc.co.uk"
    				class="alert-link">support@chesterlestreetasc.co.uk</a>
    			</p>
    		</div>
    	<?php } ?>

      <?php if (isset($_SESSION['DeleteCCSuccess'])) {
        unset($_SESSION['DeleteCCSuccess']); ?>
    		<div class="alert alert-success">
    			<p class="mb-0">
    				<strong>We've deleted that CC</strong>
    			</p>
    			<p class="mb-0">
    				If you need help, contact <a
    				href="mailto:support@chesterlestreetasc.co.uk"
    				class="alert-link">support@chesterlestreetasc.co.uk</a>
    			</p>
    		</div>
    	<?php } ?>

      <?php if (isset($_SESSION['AddNotifySuccess'])) {
        unset($_SESSION['AddNotifySuccess']); ?>
    		<div class="alert alert-success">
    			<p class="mb-0">
    				<strong>We've added a new Carbon Copy Email</strong>
    			</p>
    			<p class="mb-0">
    				If you need help, contact <a
    				href="mailto:support@chesterlestreetasc.co.uk"
    				class="alert-link">support@chesterlestreetasc.co.uk</a>
    			</p>
    		</div>
    	<?php } ?>

      <?php if (isset($_SESSION['AddNotifyError'])) {
        unset($_SESSION['AddNotifyError']); ?>
    		<div class="alert alert-warning">
    			<p class="mb-0">
    				<strong>An error occurred and we were unable to add your new CC Email.</strong>
    			</p>
    			<p class="mb-0">
    				Your verification code might have been wrong. If you need help, contact your support team.
    			</p>
    		</div>
    	<?php } ?>

    	<div class="cell">
    		<form method="post">
    			<div class="form-group">
    		    <label for="EmailAddress">Your email address</label>
    		    <input type="email" class="form-control" id="EmailAddress" name="EmailAddress" placeholder="name@example.com" value="<?=htmlentities($email)?>">
    				<?php if (isset($_SESSION['EmailUpdateNew'])) { ?>
    				<small class="form-text">Once verified, your account email address will change to <?=htmlentities($_SESSION['EmailUpdateNew'])?></small>
    				<?php } ?>
    		  </div>

    			<div class="form-group">
    				<div class="custom-control custom-switch">
    					<input type="checkbox" class="custom-control-input" value="1" id="EmailComms" aria-describedby="EmailCommsHelp" name="EmailComms" <?=$emailChecked?> >
              <label class="custom-control-label" for="EmailComms">Receive squad updates by email</label>
    					<small id="EmailCommsHelp" class="form-text text-muted">Squad updates include emails from your coaches. You'll still receive emails relating to your account if you don't receive updates.</small>
    				</div>
    			</div>

    			<div class="form-group">
    				<div class="custom-control custom-switch">
    					<input type="checkbox" class="custom-control-input" value="1" id="SecurityComms" aria-describedby="SecurityCommsHelp" name="SecurityComms" <?=$emailChecked_security?> >
              <label class="custom-control-label" for="SecurityComms">Receive account security emails</label>
    					<small id="SecurityCommsHelp" class="form-text text-muted">Receive emails whenever somebody logs in to your account from an unrecognised <abbr title="Internet Protocol">IP</abbr> address.</small>
    				</div>
    			</div>

    			<div class="form-group">
    				<div class="custom-control custom-switch">
    					<input type="checkbox" class="custom-control-input" value="1" id="PaymentComms" aria-describedby="PaymentCommsHelp" name="PaymentComms" <?php echo $emailChecked_payments; ?> >
              <label class="custom-control-label" for="PaymentComms">Receive payment emails</label>
    					<small id="PaymentCommsHelp" class="form-text text-muted">If you opt out, you'll still receive emails required for regulatory purposes.</small>
    				</div>
    			</div>

    			<?php if ($_SESSION['AccessLevel'] == "Admin") { ?>
    			<div class="form-group">
    				<div class="custom-control custom-switch">
    					<input type="checkbox" class="custom-control-input" value="1" id="NewMemberComms" aria-describedby="NewMemberCommsHelp" name="NewMemberComms" <?php echo $emailChecked_new_member; ?> >
              <label class="custom-control-label" for="NewMemberComms">Receive new member emails</label>
    					<small id="NewMemberCommsHelp" class="form-text text-muted">Get notified when new members are added.</small>
    				</div>
    			</div>
    			<?php } ?>

    			<p class="mb-0">
    				<button type="submit" class="btn btn-success">Save Changes</button>
    			</p>
    		</form>
    	</div>

      <div class="cell">
        <h2>Additional Recipients</h2>
        <p class="lead">
          You can have squad update emails sent to additional email addresses.
        </p>
        <p>
          This allows your partner or others involved with your swimmers to also be able to stay up to date.
        </p>
        <p>
          Additional recipients will be required to confirm they would like to receive emails. They will be able to unsubscribe at any time.
        </p>

        <ul class="list-unstyled">
        <?php while ($extraEmails = $getExtraEmails->fetch(PDO::FETCH_ASSOC)) { ?>
          <li>
            <p class="text-truncate mb-0">
              <?=$extraEmails['EmailAddress']?>
            </p>
            <p>
              <a href="<?=autoUrl("my-account/email/cc/" . $extraEmails['ID'] . "/delete")?>">
                Delete this email
              </a>
            </p>
          </li>
        <?php } ?>
        </ul>

        <form method="post" action="<?=autoUrl("my-account/email/cc/new")?>" class="needs-validation" novalidate>
          <div class="form-row">
            <div class="col-md">
              <div class="form-group">
                <label for="new-cc-name">Name</label>
                <input type="text" class="form-control" id="new-cc-name" name="new-cc-name" placeholder="Joe Bloggs" required>
              </div>
            </div>
            <div class="col-md">
              <div class="form-group">
                <label for="new-cc">CC Email Address</label>
                <input type="email" class="form-control" id="new-cc" name="new-cc" placeholder="joe.bloggs@example.com" required>
              </div>
            </div>
          </div>

          <button class="btn btn-success" type="submit">
            Add new CC Email
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

<script defer src="<?=autoUrl("public/js/NeedsValidation.js")?>"></script>

<?php include BASE_PATH . "views/footer.php"; ?>
