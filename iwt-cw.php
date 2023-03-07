<?php
header('Content-Type: application/json; charset=utf-8');

$year = $_GET["year"];
$year_op = $_GET["year-op"];
$tournament = $_GET["tournament"];
$file = $_GET["file"];
$winner = $_GET["winner"];
$runner_up = $_GET["runnerup"];
$results = search($year, $year_op, $tournament, $winner, $runner_up, $file);
echo $results;

function search($year, $year_op, $tournament, $winner, $runner_up, $file)
{
  $mens_json = file_get_contents('resources/mens-grand-slam-winners.json');
  $womens_json = file_get_contents('resources/womens-grand-slam-winners.json');
  $decoded_mens_json = json_decode($mens_json, true);
  $decoded_womens_json = json_decode($womens_json, true);
  switch ($file) {
    case 'mens-grand-slam-winners.json':
      $results = getSearchResult($decoded_mens_json, $year, $year_op, $tournament, $winner, $runner_up);
      return json_encode($results);
    case 'womens-grand-slam-winners.json':
      $results = getSearchResult($decoded_womens_json, $year, $year_op, $tournament, $winner, $runner_up);
      return json_encode($results);
    case 'any':
      $decoded_mixed_json = array_merge($decoded_mens_json, $decoded_womens_json);
      $results = getSearchResult($decoded_mixed_json, $year, $year_op, $tournament, $winner, $runner_up);
      return json_encode($results);
    default:
      $error_message = new stdClass();
      $error_message->error = "Error! Invalid value for file.";
      return json_encode($error_message);
  }
}

function getSearchResult($data_json, $search_year, $year_condition, $search_tournament, $search_winner, $search_runnerup)
{
  $results = array();
  $error_message = new stdClass();
  foreach ($data_json as $item) {
    $year = $item['year'];
    $tournament = $item['tournament'];
    $winner = $item['winner'];
    $runnerup = $item['runner-up'];
    $check_year = checkYear($year, $search_year, $year_condition);
    if ($check_year === true) {
      $check_tournament = checkTournament($tournament, $search_tournament);
      if ($check_tournament === true) {
        $check_winner = checkWinner($winner, $search_winner);
        if ($check_winner === true) {
          $check_runner_up = checkRunnerup($runnerup, $search_runnerup);
          if ($check_runner_up === true) {
            $results[] = $item;
          } elseif ($check_runner_up !== false) {
            $error_message->error = $check_runner_up;
            return $error_message;
          }
        } elseif ($check_winner !== false) {
          $error_message->error = $check_winner;
          return $error_message;
        }
      } elseif ($check_tournament !== false) {
        $error_message->error = $check_tournament;
        return $error_message;
      }
    } elseif ($check_year !== false) {
      $error_message->error = $check_year;
      return $error_message;
    }
  }
  return $results;
}

function checkYear($item_year, $search_year, $year_condition): bool|string
{
  if (!is_numeric($search_year)) {
    return "Error! Year should be only numbers.";
  }
  if ($year_condition != null && $search_year == null) {
    return "Error! Search year cannot be empty with a condition.";
  }
  if ($search_year == null && $year_condition != null) {
    return "Error! Search condition cannot be empty with a year.";
  }
  switch ($year_condition) {
    case "=":
      return $item_year == $search_year;
    case ">":
      return $item_year > $search_year;
    case "<":
      return $item_year < $search_year;
    default:
      return "Error! Invalid year condition.";
  }
}

function checkTournament($tournament, $search_tournament): bool|string
{
  switch ($search_tournament) {
    case 'any':
      return true;
    case 'Australian Open':
    case 'U.S. Open':
    case 'French Open':
    case 'Wimbledon':
      return $search_tournament == $tournament;
    default:
      return 'Error! Invalid tournament name.';
  }
  return false;
}

function checkWinner($winner, $search_winner): bool|string
{
  if ($search_winner == "") {
    return true;
  }
  if (preg_match('~[0-9]+~', $search_winner)) {
    return "Error! Winner name cannot contain numbers.";
  }
  if (stripos($winner, $search_winner) !== false) {
    return true;
  }
  return false;
}

function checkRunnerup($runnerup, $search_runnerup): bool|string
{
  if ($search_runnerup == "") {
    return true;
  }
  if (preg_match('~[0-9]+~', $search_runnerup)) {
    return "Error! Runner-up name cannot contain numbers.";
  }
  if (stripos($runnerup, $search_runnerup) !== false) {
    return true;
  }
  return false;
}

?>
