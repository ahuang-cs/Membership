<?php

$pagetitle = "Galas";
$title = "Galas";
$content = "<p class=\"lead\">The gala homepage gives you information about your gala entries for upcoming galas and other galas which are available to enter.</p>";
$content .= "<p>Please remember that this new system is in development, so you must hand in a <a href=\"https://www.chesterlestreetasc.co.uk/wp-content/uploads/2016/06/GalaEntryForm.pdf\" target=\"_blank\">paper Gala Entry Form</a> as well.</p>";
$content .= "<h2>Galas you can enter</h2>";
$content .= upcomingGalas($link);
$content .= "<p><a class=\"btn btn-success\" href=\"entergala\">Enter a gala</a></p>";
$content .= "<h2>Galas you've entered</h2>";
$content .= enteredGalas($link, $userID);

/* Stats Section */
$swimsCountArray = [];
$strokesCountArray = [0, 0, 0, 0, 0];
$strokesCountTextArray = ["Freestyle", "Breaststroke", "Butterfly", "Backstroke", "Individual Medley"];
$swimsArray = ['50Free','100Free','200Free','400Free','800Free','1500Free','50Breast','100Breast','200Breast','50Fly','100Fly','200Fly','50Back','100Back','200Back','100IM','150IM','200IM','400IM',];
$strokesArray = ['0','0','0','0','0','0','1','1','1','2','2','2','3','3','3','4','4','4','4',];
$swimsTextArray = ['50 Free','100 Free','200 Free','400 Free','800 Free','1500 Free','50 Breast','100 Breast','200 Breast','50 Fly','100 Fly','200 Fly','50 Back','100 Back','200 Back','100 IM','150 IM','200 IM','400 IM',];
$counter = 0;
for ($i=0; $i<sizeof($swimsArray); $i++) {
	$col = $swimsArray[$i];
	$sql = "SELECT `$col` FROM (galaEntries INNER JOIN members ON galaEntries.MemberID = members.MemberID) WHERE `UserID` = '$userID' AND `$col` = '1'";
	$result = mysqli_query($link, $sql);
	$count = mysqli_num_rows($result);
	$swimsCountArray[$i] = $count;
	$strokesCountArray[$strokesArray[$i]] += $count;
	$counter += $count;
}
	if ($counter>0) {
	$content .= "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
	    <script type=\"text/javascript\">
	      google.charts.load('current', {'packages':['corechart']});

	      google.charts.setOnLoadCallback(drawPieChart);
				google.charts.setOnLoadCallback(drawBarChart);

	      function drawPieChart() {

	        var data = google.visualization.arrayToDataTable([
	          ['Stroke', 'Total Number of Entries'],";
						for ($i=0; $i<sizeof($strokesCountArray); $i++) {
	          	$content .= "['" . $strokesCountTextArray[$i] . "', " . $strokesCountArray[$i] . "],";
						}
	        $content .= "]);

	        var options = {
	          title: 'My Gala Entries by Stroke',
						fontName: 'Open Sans',
						backgroundColor: {
							fill:'transparent'
						},
						chartArea: {
							left: '0',
							right: '0',
						}
	        };

	        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

	        chart.draw(data, options);
	      }
				function drawBarChart() {

	        var data = google.visualization.arrayToDataTable([
	          ['Stroke', 'Total Number of Entries'],";
						for ($i=0; $i<sizeof($swimsArray); $i++) {
							if ($swimsCountArray[$i] > 0) {
	          		$content .= "['" . $swimsTextArray[$i] . "', " . $swimsCountArray[$i] . "],";
							}
						}
	        $content .= "]);

	        var options = {
	          title: 'My Gala Entries by Event',
						fontName: 'Open Sans',
						backgroundColor: {
							fill:'transparent'
						},
						chartArea: {
							left: '0',
							right: '0',
						},
						backgroundColor: {
							fill:'transparent'
						},
						legend: {
							position: 'none',
						}
	        };

	        var chart = new google.visualization.ColumnChart(document.getElementById('barchart'));

	        chart.draw(data, options);
	      }
	    </script>
			<h2>Statistics <small>All My Swimmers</small></h2>
	    <div class=\"chart\" id=\"piechart\"></div>
			<div class=\"chart\" id=\"barchart\"></div>
	";
}
?>
