<!doctype html>
<html class="no-js" lang="">
<body>
<?php
$mens_json = file_get_contents('resources/mens-grand-slam-winners.json');
$womens_json = file_get_contents('resources/womens-grand-slam-winners.json');
$decoded_mens_json = json_decode($mens_json, true);
$decoded_womens_json = json_decode($womens_json, true);
$gender = "womens";
if ($gender == "mens") {
  $results = getSearchResult($decoded_mens_json, 2020, "equals", "Wimbledon", "Andy", "");
} elseif ($gender == "womens") {
  $results = getSearchResult($decoded_womens_json, 2014, "after", "U.S. Open", "", "");
} else {
  $decoded_mixed_json = array_merge($decoded_mens_json, $decoded_womens_json);
  $results = getSearchResult($decoded_mixed_json, 2020, null, "Wimbledon", "", "mi");
}
printResult($results);

function getSearchResult($data_json, $search_year, $year_condition, $search_tournament, $search_winner, $search_runnerup)
{
  print_r($search_year);
  echo ":";
  print_r($year_condition);
  echo ":";
  print_r($search_tournament);
  echo ":";
  print_r($search_winner);
  echo ":";
  print_r($search_runnerup);
  echo "<br>";

  $results = array();

  foreach ($data_json as $item) {
    $year = $item['year'];
    $tournament = $item['tournament'];
    $winner = $item['winner'];
    $runnerup = $item['runner-up'];
    if (checkYear($year, $search_year, $year_condition)) {
      if (checkTournament($tournament, $search_tournament)) {
        if (checkWinner($winner, $search_winner) && checkRunnerup($runnerup, $search_runnerup)) {
          $results[] = $item;
        }
      }
    }
  }
  return $results;
}

function checkYear($item_year, $search_year, $year_condition): bool
{
  switch ($year_condition) {
    case "equals":
      return $item_year == $search_year;
    case "after":
      return $item_year > $search_year;
    case "before":
      return $item_year < $search_year;
    case null:
      if ($search_year == null) {
        return  true;
      }
  }
  return false;
}

function checkTournament($tournament, $search_tournament): bool
{
  if ($search_tournament == "any" || $search_tournament == $tournament) {
    return true;
  }
  return false;
}

function checkWinner($winner, $search_winner): bool
{
  if ($search_winner == "") {
    return true;
  }
  if (stripos($winner, $search_winner) !== false) {
    return true;
  }
  return false;
}

function checkRunnerup($runnerup, $search_runnerup): bool
{
  if ($search_runnerup == "") {
    return true;
  }
  if (stripos($runnerup, $search_runnerup) !== false) {
    return true;
  }
  return false;
}

function printResult($results){
  echo "<b>RESULTS</b><br><br>";
  foreach ($results as $result_item) {
    $year = $result_item['year'];
    $tournament = $result_item['tournament'];
    $winner = $result_item['winner'];
    $runnerup = $result_item['runner-up'];
    print_r($year);
    echo " : ";
    echo " : ";
    print_r($tournament);
    echo " : ";
    print_r($winner);
    echo " : ";
    print_r($runnerup);
    echo "<br>";
  }
}

?>
</body>
</html>
