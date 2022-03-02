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

$this->group('/react', function() {
  $this->get('/header-footer', function() {
    include 'react/header-footer.php';
  });
});

$this->any(['/', '/*'], function() {
  include 'catch-all.php';
});