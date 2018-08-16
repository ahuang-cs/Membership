<?

global $db;

$null = $page;

$start = 0;

if ($page != null) {
  $start = ($page-1)*10;
} else {
  $page = 1;
}

if ($page == 1 && $null != null) {
  header("Location: " . autoUrl("myaccount/loginhistory"));
  die();
}

$sql = "SELECT `ID` FROM `userLogins` WHERE `UserID` = ?";
try {
	$query = $db->prepare($sql);
	$query->execute([$_SESSION['UserID']]);
} catch (PDOException $e) {
	halt(500);
}
$numLogins = sizeof($query->fetchAll(PDO::FETCH_ASSOC));
$numPages = ((int)($numLogins/10)) + 1;

if ($start > $numLogins) {
  //halt(404);
}

$sql = "SELECT `Time`, `IPAddress`, `Browser`, `Platform`, `Mobile` FROM `userLogins` WHERE `UserID` = :user ORDER BY `Time` DESC LIMIT :start, 10";
try {
	$query = $db->prepare($sql);
  $query->bindParam('user', $_SESSION['UserID'], PDO::PARAM_INT);
  $query->bindParam('start', $start, PDO::PARAM_INT);
	$query->execute();
} catch (PDOException $e) {
	 halt(500);
}

$row = $query->fetchAll(PDO::FETCH_ASSOC);

$pagetitle = "Login History";

include BASE_PATH . "views/header.php";
//include BASE_PATH . "views/notifyMenu.php";?>

<div class="container">
  <div class="mb-3 p-3 bg-white rounded box-shadow">
    <h1>Your Login History</h1>
		<div class="alert alert-danger">
			<p class="mb-0"><strong>Spotted anything suspicious?</strong></p>
			<p class="mb-0"><a href="<?=autoUrl("myaccount/password")?>"
			class="alert-link">Change your password</a> straight away.</p>
		</div>
    <? if ($numLogins == 0) {
      halt(404);
    } else { ?>
    <p class="lead pb-3 mb-0 border-bottom border-gray">
      Page <? echo $page; ?> of <? echo $numPages; ?>
    </p>
    <? for ($i = 0; $i < sizeof($row); $i++) { ?>
    <div class="media pt-3">
      <div class="media-body pb-3 mb-0 lh-125 border-bottom border-gray force-wrap">
        <div class="d-block text-gray-dark">
          <p class="mb-0">
            <strong>
              Login at <?= date("H:i \o\\n l j F Y", strtotime($row[$i]['Time'])) ?>
              using <?= htmlentities($row[$i]['Browser']) ?>
            </strong>
          </p>
          <p class="mb-0">
            <? if ($row[$i]['Mobile']) { ?>
            Login from a mobile device running <?= htmlentities($row[$i]['Platform']) ?>.
            <? } else { ?>
            Login from a desktop computer running <?= htmlentities($row[$i]['Platform']) ?>.
            <? } ?>
            IP Address: <?= htmlentities($row[$i]['IPAddress']) ?>
          </p>
        </div>
      </div>
    </div>
    <? } ?>

    <nav aria-label="Page navigation">
      <ul class="pagination mb-0">
        <? if ($numLogins <= 10) { ?>
        <li class="page-item active"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page ?>"><? echo $page ?></a></li>
        <? } else if ($numLogins <= 20) { ?>
          <? if ($page == 1) { ?>
          <li class="page-item active"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page ?>"><? echo $page ?></a></li>
    			<li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>"><? echo $page+1 ?></a></li>
    			<li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>">Next</a></li>
          <? } else { ?>
          <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page-1 ?>">Previous</a></li>
    	    <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page-1 ?>"><? echo $page-1 ?></a></li>
    	    <li class="page-item active"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page ?>"><? echo $page ?></a></li>
          <? } ?>
        <? } else { ?>
    			<? if ($page == 1) { ?>
    			<li class="page-item active"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page ?>"><? echo $page ?></a></li>
    	    <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>"><? echo $page+1 ?></a></li>
    			<li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+2 ?>"><? echo $page+2 ?></a></li>
    			<li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>">Next</a></li>
          <? } else { ?>
    			<li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page-1 ?>">Previous</a></li>
    	    <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page-1 ?>"><? echo $page-1 ?></a></li>
    	    <li class="page-item active"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page ?>"><? echo $page ?></a></li>
    			<? if ($numLogins > $page*10) { ?>
    	    <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>"><? echo $page+1 ?></a></li>
    	    <li class="page-item"><a class="page-link" href="<? echo autoUrl("myaccount/loginhistory/page/"); ?><? echo $page+1 ?>">Next</a></li>
          <? } ?>
        <? } ?>
      <? } ?>
      </ul>
    </nav>
  <? } ?>
  </div>
</div>

<?
include BASE_PATH . "views/footer.php";
