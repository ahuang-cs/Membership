<style>
  @font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: 400;
    src: url('<?=autoUrl('public/fonts/OpenSans-Regular.ttf')?>') format('truetype');
  }
  @font-face {
    font-family: 'Open Sans Bold';
    font-style: normal;
    font-weight: 400;
    src: url('<?=autoUrl('public/fonts/OpenSans-Bold.ttf')?>') format('truetype');
  }
  @font-face {
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    src: url('<?=autoUrl('public/fonts/LATO-REGULAR.TTF')?>') format('truetype');
  }
  @font-face {
    font-family: 'Lato Bold';
    font-style: normal;
    font-weight: 400;
    src: url('<?=autoUrl('public/fonts/LATO-BOLD.TTF')?>') format('truetype');
  }
  @font-face {
    font-family: 'Roboto Mono';
    font-style: normal;
    font-weight: 400;
    src: url('<?=autoUrl('public/fonts/RobotoMono-Regular.ttf')?>') format('truetype');
  }
  html {
    font-family: 'Open Sans', sans-serif;
    color: black;
    margin: 1.27cm;
  }
  body {
    font-size: 10pt;
    line-height: 1;
    text-align: left;
  }
  * {
    box-sizing: border-box;
  }
  h1, h2, h3, h4, h5, h6 {
    font-family: 'Open Sans Bold', 'Open Sans', 'Helvetica', sans-serif;
    font-weight: 400;
    font-style: normal;
    margin: 0 0 10pt 0;
    padding: 0;
    line-height: 1;
  }
  h1 {
    font-size: 25pt;
  }
  h2 {
    font-size: 20pt;
  }
  h3 {
    font-size: 17.5pt;
  }
  h4 {
    font-size: 15pt;
  }
  h5 {
    font-size: 12.5pt;
  }
  h6 {
    font-size: 10pt;
  }
  p {
    margin: 0 0 10pt 0;
    padding: 0;
  }
  strong, thead, th {
    font-family: 'Open Sans Bold', 'Open Sans', 'Helvetica', sans-serif;
    font-weight: 400;
    font-style: normal;
  }
  .lead {
    font-size: 18pt;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  td, th {
    padding: 3pt;
    border-top: 1px solid #eee;
    vertical-align: top;
  }
  thead th {
    border-bottom: 2px solid #eee;
  }
  .primary {
    color: #bd0000;
  }
  .primary-box {
    padding: 4pt 10pt 10pt 10pt;
    background-color: #bd0000;
    color: #ffffff;
  }
  a, a:hover, a:active, a:visited {
    color: #bd0000;
    text-decoration: underline;
  }

  <?php if (!defined('IS_CLS') || !IS_CLS) { ?>
    .primary {
      color: #007bff;
    }
    .primary-box {
      background-color: #007bff;
    }
    a, a:hover, a:active, a:visited {
      color: #007bff;
      text-decoration: underline;
    }
  <?php } ?>

  .mb-0 {
    margin-bottom: 0;
  }
  .mb-3 {
    margin-bottom: 10pt;
  }
  .mt-0 {
    margin-top: 0;
  }
  .mt-3 {
    margin-top: 10pt;
  }
  .logo {
    height: 1.2cm;
    width: auto;
    max-width: 100%;
  }
  .text-right {
    text-align: right;
  }
  .row::after {
    content: "";
    clear: both;
    display: table;
  }
  .split-25 {
    width: 25%;
    float: left;
  }
  .split-30 {
    width: 30%;
    float: left;
  }
  .split-50 {
    width: 50%;
    float: left;
  }
  .split-70 {
    width: 70%;
    float: left;
  }
  .split-75 {
    width: 75%;
    float: left;
  }
  .list-unstyled {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  .mono {
    font-family: 'Roboto Mono', monospace;
  }
  .page-break {
    page-break-after: always;
  }

  <?php if (!defined('IS_CLS') || !IS_CLS) { ?>
  html {
    font-family: 'Lato', sans-serif;
  }
  h1, h2, h3, h4, h5, h6, strong, thead, th {
    font-family: 'Lato Bold';
  }
  <?php } ?>
</style>