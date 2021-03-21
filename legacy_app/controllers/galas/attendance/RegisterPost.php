<?php

canView('TeamManager', $_SESSION['TENANT-' . nezamy_app()->tenant->getId()]['UserID'], $id);