<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title><?php echo $this->pageTitle; ?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="<?php echo $this->config['templateUri']; ?>/boilerplate/css/normalize.css">
  <link rel="stylesheet" href="<?php echo $this->config['templateUri']; ?>/boilerplate/css/main.css">
<script>
window.onload = function () {

<?php echo $this->pageScript; ?>

}
</script>

  <meta name="theme-color" content="#fafafa">
</head>

<body>
  <!--[if IE]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

<?php echo $this->pageContent; ?>
  <script src="<?php echo $this->config['templateUri']; ?>/boilerplate/js/vendor/modernizr-3.7.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.4.1.min.js"><\/script>')</script>
  <script src="<?php echo $this->config['templateUri']; ?>/boilerplate/js/plugins.js"></script>
  <script src="<?php echo $this->config['templateUri']; ?>/boilerplate/js/main.js"></script>

</body>

</html>
