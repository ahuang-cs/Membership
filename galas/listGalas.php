<?php

$pagetitle = "Galas";
$title = "Galas";
$content = "<p class=\"lead\">Galas which are open for entries, or have closed. Galas in the past are not shown.</p>";
$content .= "<h2>Galas open for entries</h2>";
$content .= upcomingGalas($link);
$content .= "<p><a href=\"addgala\" class=\"btn btn-light\">Add a gala</a></p>";
$content .= "<h2>Galas closed for entries</h2>";
$content .= closedGalas($link);
?>
