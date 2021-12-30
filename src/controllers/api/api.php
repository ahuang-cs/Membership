<?php

/**
 * API
 */

$this->before('/api', function (){
  header("content-type: application/json");
});

$this->group('/settings', function() {
  $this->get('/tenant', function() {
    include 'settings/tenant.php';
  });
});

$this->group('/galas', function() {
  $this->get('/', function() {
    include 'galas/galas.php';
  });

  $this->get('/{id}:int', function($id) {
    include 'galas/gala.php';
  });
});

$this->get('/test', function() {
  echo json_encode('Yo');
});

$this->any(['/', '/*'], function() {
  include 'catch-all.php';
});