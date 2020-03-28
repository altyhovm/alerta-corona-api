<?php

$get = file_get_contents('https://www.bing.com/covid/data');
$get = json_decode($get, true);
$get = $get['areas'];

$len = count($get);

for ($i = 0; $i < $len; $i++) {
  if ($get[$i]['id'] == 'brazil') {
    $key = $i;
  }
}

$get = $get[$key];

if ($get['id'] != 'brazil') {
  return false;
}

$areas = $get['areas'];
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

  $states += [$indice => $areas[$i]];
}

$new = [
  "updated" => time(),
  "old" => [],
  "data" => [
    "states" => $states,
    "confirmed" => $get['totalConfirmed'],
    "recovered" => $get['totalRecovered'],
    "deaths" => $get['totalDeaths']
  ]
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

  $txt = fopen('data.txt', 'a');
  fwrite($txt, "data.json" . PHP_EOL);
  fclose($txt);

  copy('data.json', 'old/data.json');
} else {

  $check = file_get_contents('data.json');
  $check = json_decode($check, true);

  if ($check['data']['confirmed'] != $get['totalConfirmed']) {

    copy('data.json', 'old/data.json');

    $data = file_get_contents('data.json');
    $data = json_decode($data, true);
    $addData = $data['old'];
    $oldData = $data['updated'];
    $data = json_encode($data);

    $oldJson = fopen('old/' . $oldData . '.json', 'w');
    fwrite($oldJson, $data);
    fclose($oldJson);

    $txt = fopen('data.txt', 'a');
    fwrite($txt, $oldData . PHP_EOL);
    fclose($txt);

    if (is_int($addData)) {
      $addData = [
        $addData,
        $time
      ];
    } else {
      array_push($addData, $time);
    }

    $new['old'] += $addData;
    $new = json_encode($new);

    $json = fopen('data.json', 'w');
    fwrite($json, $new);
    fclose($json);
  }
}
