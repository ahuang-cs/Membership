<?php

header('Content-Type: application/manifest+json');

$icons = [];

$clubName = 'My Club';
if (mb_strlen(nezamy_app()->tenant->getKey('CLUB_SHORT_NAME')) > 0 && mb_strlen(nezamy_app()->tenant->getKey('CLUB_SHORT_NAME')) < 14) {
  $clubName = nezamy_app()->tenant->getKey('CLUB_SHORT_NAME');
}

$themeColour = "#bd0000";
if (nezamy_app()->tenant->getKey('SYSTEM_COLOUR')) {
  $themeColour = nezamy_app()->tenant->getKey('SYSTEM_COLOUR');
}

$logos = nezamy_app()->tenant->getKey('LOGO_DIR');

if ($logos) {
  $icons[] = [
    'src' => autoUrl($logos . 'icon-196x196.png'),
    'sizes' => '196x196',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-192x192.png'),
    'sizes' => '192x192',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-180x180.png'),
    'sizes' => '180x180',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-167x167.png'),
    'sizes' => '167x167',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-152x152.png'),
    'sizes' => '152x152',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-128x128.png'),
    'sizes' => '128x128',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-114x114.png'),
    'sizes' => '114x114',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-32x32.png'),
    'sizes' => '32x32',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => autoUrl($logos . 'icon-32x32.png'),
    'sizes' => '32x32',
    'type' => 'image/png'
  ];
} else if (nezamy_app()->tenant->isCLS()) { 
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-precomposed.png'),
    'sizes' => '57x57',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-72x72-precomposed.png'),
    'sizes' => '72x72',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-76x76-precomposed.png'),
    'sizes' => '76x76',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-114x114-precomposed.png'),
    'sizes' => '114x114',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-120x120-precomposed.png'),
    'sizes' => '120x120',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-144x144-precomposed.png'),
    'sizes' => '144x144',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-152x152-precomposed.png'),
    'sizes' => '152x152',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/apple-touch-icon-180x180-precomposed.png'),
    'sizes' => '180x180',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/touch-icon-192x192-precomposed.png'),
    'sizes' => '192x192',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/touchicons/touch-icon-196x196.png'),
    'sizes' => '196x196',
    'type' => 'image/png'
  ];
} else {
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon.png'),
    'sizes' => '57x57',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-72x72.png'),
    'sizes' => '72x72',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-76x76.png'),
    'sizes' => '76x76',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-114x114.png'),
    'sizes' => '114x114',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-120x120.png'),
    'sizes' => '120x120',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-144x144.png'),
    'sizes' => '144x144',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-152x152.png'),
    'sizes' => '152x152',
    'type' => 'image/png'
  ];
  $icons[] = [
    'src' => url('/img/corporate/icons/apple-touch-icon-180x180.png'),
    'sizes' => '180x180',
    'type' => 'image/png'
  ];
}

$data = [
  'name' => nezamy_app()->tenant->getKey('CLUB_NAME') . ' Membership',
  'short_name' => $clubName,
  'start_url' => autoUrl(""),
  'display' => 'minimal-ui',
  'background_color' => '#fff',
  'description' => 'My ' . nezamy_app()->tenant->getKey('CLUB_NAME') . ' Membership',
  'icons' => $icons,
  'theme_color' => $themeColour,
  'lang' => 'en-GB',
  'scope' => autoUrl("")
];

echo json_encode($data);