<?php

/**
 * New registration and renewal system
 * 
 */

$this->get('/', function () {
  include 'home.php';
});

$this->group('/{id}:uuid', function ($id) {
  $this->get('/', function ($id) {
    include 'forms/home.php';
  });

  $this->group('/account-review', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/member-review', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/fee-review', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/address-review', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/emergency-contacts', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/medical-forms', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/conduct-forms', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/data-protection-and-privacy', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/terms-and-conditions', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/photography-permissions', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/administration-form', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/direct-debit', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });

  $this->group('/renewal-fees', function ($id) {
    $this->get('/', function ($id) {
      include 'forms/home.php';
    });
  });
});