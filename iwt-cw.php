<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>Grand Slam Winners</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
  <meta name="theme-color" content="#fafafa">
</head>

<body>
<?php
$mens_json = file_get_contents('resources/mens-grand-slam-winners.json');
$decoded_json = json_decode($mens_json, false);
echo $decoded_json->year;
// Monty
echo $decoded_json->tournament;
// monty@something.com
echo $decoded_json->tournament;
// 77
?>
</body>
</html>
