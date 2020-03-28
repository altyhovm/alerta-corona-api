<?php

$get = file_get_contents('https://www.bing.com/covid/data');
$get = json_decode($get, true);

$len = count($get['areas']);

for ($i = 0; $i < $len; $i++) {
  if ($get['areas'][$i]['id'] == 'brazil') {
    $key = $i;
  }
}

$brazil = $get['areas'][$key];

if ($brazil['id'] != 'brazil') {
  return false;
}

$areas = $brazil['areas'];
$len = count($areas);
$states = [];

for ($i = 0; $i < $len; $i++) {

  $indice = $areas[$i]['id'];

  switch ($indice) {
    case 'acre_brazil':
      $indice = 'ac';
      break;
    case 'amazonas_brazil':
      $indice = 'am';
      break;
    case 'amap_brazil':
      $indice = 'ap';
      break;
    case 'par_brazil':
      $indice = 'pa';
      break;
    case 'rondnia_brazil':
      $indice = 'ro';
      break;
    case 'roraima_brazil':
      $indice = 'rr';
      break;
    case 'tocantins_brazil':
      $indice = 'to';
      break;
    case 'alagoas_brazil':
      $indice = 'al';
      break;
    case 'bahia_brazil':
      $indice = 'ba';
      break;
    case 'cear_brazil':
      $indice = 'ce';
      break;
    case 'maranho_brazil':
      $indice = 'ma';
      break;
    case 'paraba_brazil':
      $indice = 'pb';
      break;
    case 'pernambuco_brazil':
      $indice = 'pe';
      break;
    case 'piau_brazil':
      $indice = 'pi';
      break;
    case 'riograndedonorte_brazil':
      $indice = 'rn';
      break;
    case 'sergipe_brazil':
      $indice = 'se';
      break;
    case 'espritosanto_brazil':
      $indice = 'es';
      break;
    case 'minasgerais_brazil':
      $indice = 'mg';
      break;
    case 'riodejaneiro_brazil':
      $indice = 'rj';
      break;
    case 'sopaulo_brazil':
      $indice = 'sp';
      break;
    case 'distritofederal_brazil':
      $indice = 'df';
      break;
    case 'gois_brazil':
      $indice = 'go';
      break;
    case 'matogrossodosul_brazil':
      $indice = 'ms';
      break;
    case 'matogrosso_brazil':
      $indice = 'mt';
      break;
    case 'paran_brazil':
      $indice = 'pr';
      break;
    case 'santacatarina_brazil':
      $indice = 'sc';
      break;
    case 'riograndedosul_brazil':
      $indice = 'rs';
      break;
  }

  $states += [$indice => [
    'name' => $areas[$i]['displayName'],
    'confirmed' => $areas[$i]['totalConfirmed'],
    'recovered' => $areas[$i]['totalRecovered'],
    'deaths' => $areas[$i]['totalDeaths']
  ]];
}




$new = [
  "brazil" => [
    "updated" => time(),
    "states" => $states,
    "confirmed" => $brazil['totalConfirmed'],
    "recovered" => $brazil['totalRecovered'],
    "deaths" => $brazil['totalDeaths']
  ],
  "world" => [
    "updated" => time(),
    "confirmed" => $get['totalConfirmed'],
    "recovered" => $get['totalRecovered'],
    "deaths" => $get['totalDeaths']
  ],
  "old" => []
];

$time = time();

if (!is_dir('old')) {
  mkdir('old', 0777, true);
}

if (!file_exists('data.json')) {
  $json = fopen('data.json', 'w');
  $new['old'] = $time;
  $new = json_encode($new);
  fwrite($json, $new);
  fclose($json);
  echo "data.json criado com sucesso! <br>";

  $txt = fopen('data.txt', 'a');
  fwrite($txt, "data.json" . PHP_EOL);
  fclose($txt);
  echo "data.txt criado com sucesso! <br>";

  copy('data.json', 'old/data.json');
  echo "backup realizado com sucesso! <br>";
} else {

  function old($time)
  {
    copy('data.json', 'old/data.json');
    echo "backup realizado com sucesso! <br>";

    $old = file_get_contents('data.json');
    $old = json_decode($old, true);

    if (is_int($old['old'])) {
      $old['old'] = [
        $old['old'],
        $time
      ];
    } else {
      array_push($old['old'], $time);
    }
    $old = json_encode($old);

    $jsonOld = fopen('data.json', 'w');
    fwrite($jsonOld, $old);
    fclose($jsonOld);

    $oldJson = fopen('old/' . $time . '.json', 'w');
    fwrite($oldJson, $old);
    fclose($oldJson);
    echo "backup old/{$time}.json criado com sucesso! <br>";

    $txt = fopen('data.txt', 'a');
    fwrite($txt, $time . PHP_EOL);
    fclose($txt);
    echo "LOG: {$time} foi escrito em data.txt <br>";
  }

  $check = file_get_contents('data.json');
  $check = json_decode($check, true);

  if ($check['world']['confirmed'] != $get['totalConfirmed'] || $check['world']['recovered'] != $get['totalRecovered'] || $check['world']['deaths'] != $get['totalDeaths']) {
    $data = file_get_contents('data.json');
    $data = json_decode($data, true);
    $data['world']['updated'] = $time;
    $data['world']['confirmed'] = $get['totalConfirmed'];
    $data['world']['recovered'] = $get['totalRecovered'];
    $data['world']['deaths'] = $get['totalDeaths'];

    $data = json_encode($data);
    $json = fopen('data.json', 'w');
    fwrite($json, $data);
    fclose($json);
    echo "informações no mundo atualizada! <br>";
  }

  if ($check['world']['confirmed'] != $get['totalConfirmed'] || $check['world']['recovered'] != $get['totalRecovered'] || $check['world']['deaths'] != $get['totalDeaths'] && $check['brazil']['confirmed'] != $brazil['totalConfirmed'] || $check['brazil']['recovered'] != $brazil['totalRecovered'] || $check['brazil']['deaths'] != $brazil['totalDeaths']) {
    $data = file_get_contents('data.json');
    $data = json_decode($data, true);
    $data['brazil']['updated'] = $time;
    $data['brazil']['states'] = $states;
    $data['brazil']['confirmed'] = $brazil['totalConfirmed'];
    $data['brazil']['recovered'] = $brazil['totalRecovered'];
    $data['brazil']['deaths'] = $brazil['totalDeaths'];

    $data = json_encode($data);
    $json = fopen('data.json', 'w');
    fwrite($json, $data);
    fclose($json);
    echo "informações no Brasil atualizada! <br>";
  }

  if ($check['brazil']['confirmed'] != $brazil['totalConfirmed'] || $check['brazil']['recovered'] != $brazil['totalRecovered'] || $check['brazil']['deaths'] != $brazil['totalDeaths']) {
    old($time);
  } elseif ($check['world']['confirmed'] != $get['totalConfirmed']) {
    old($time);
  } elseif ($check['brazil']['confirmed'] != $brazil['totalConfirmed']) {
    old($time);
  }
}
echo "sem novas informações!";
