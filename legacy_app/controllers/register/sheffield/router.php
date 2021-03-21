<?php

$this->get('/', function () {
  if (isset(nezamy_app()->tenant) && nezamy_app()->tenant->getKey('ASA_CLUB_CODE') == 'UOSZ') {
    include 'register-page.php';
  } else {
    halt(404);
  }
});

$this->post('/', function () {
  if (isset(nezamy_app()->tenant) && nezamy_app()->tenant->getKey('ASA_CLUB_CODE') == 'UOSZ') {
    include 'register-post.php';
  } else {
    halt(404);
  }
});