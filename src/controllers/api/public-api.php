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

$this->group('/auth', function() {
  $this->group('/login', function() {
    $this->post('/login', function() {
      include 'auth/login.php';
    });

    $this->post('/two-factor', function() {
      include 'auth/two-factor.php';
    });

    $this->post('/resend-two-factor', function() {
      include 'auth/resend-two-factor.php';
    });
  });

  $this->post('/request-password-reset', function() {
    include 'auth/request-password-reset.php';
  });

  $this->post('/can-password-reset', function() {
    include 'auth/can-password-reset.php';
  });

  $this->post('/complete-password-reset', function() {
    include 'auth/complete-password-reset.php';
  });
});

$this->group('/utilities', function() {
  $this->post('/pwned-password-check', function() {
    include 'utilities/pwned-password-check.php';
  });
});

$this->any(['/', '/*'], function() {
  include 'catch-all.php';
});