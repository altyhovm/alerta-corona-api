<?php

$brazil = file_get_contents('https://covid19-brazil-api.now.sh/api/report/v1/brazil');

$states = file_get_contents('https://covid19-brazil-api.now.sh/api/report/v1');

$brazil = json_decode($brazil, true);
$brazil = $brazil['data'];

$states = json_decode($states, true);
$states = $states['data'];

$len = count($states);

$statesUp = [];

for ($i = 0; $i < $len; $i++) {

  $indice = $states[$i]['uf'];

  switch ($indice) {
    case 'AC':
      $indice = 'ac';
      break;
    case 'AM':
      $indice = 'am';
      break;
    case 'AP':
      $indice = 'ap';
      break;
    case 'PA':
      $indice = 'pa';
      break;
    case 'RO':
      $indice = 'ro';
      break;
    case 'RR':
      $indice = 'rr';
      break;
    case 'TO':
      $indice = 'to';
      break;
    case 'AL':
      $indice = 'al';
      break;
    case 'BA':
      $indice = 'ba';
      break;
    case 'CE':
      $indice = 'ce';
      break;
    case 'MA':
      $indice = 'ma';
      break;
    case 'PB':
      $indice = 'pb';
      break;
    case 'PE':
      $indice = 'pe';
      break;
    case 'PI':
      $indice = 'pi';
      break;
    case 'RN':
      $indice = 'rn';
      break;
    case 'SE':
      $indice = 'se';
      break;
    case 'ES':
      $indice = 'es';
      break;
    case 'MG':
      $indice = 'mg';
      break;
    case 'RJ':
      $indice = 'rj';
      break;
    case 'SP':
      $indice = 'sp';
      break;
    case 'DF':
      $indice = 'df';
      break;
    case 'GO':
      $indice = 'go';
      break;
    case 'MS':
      $indice = 'ms';
      break;
    case 'MT':
      $indice = 'mt';
      break;
    case 'PR':
      $indice = 'pr';
      break;
    case 'SC':
      $indice = 'sc';
      break;
    case 'RS':
      $indice = 'rs';
      break;
  }

  $statesUp += [$indice => [
    'name' => $states[$i]['state'],
    'confirmed' => $states[$i]['cases'],
    'recovered' => null,
    'deaths' => $states[$i]['deaths'],
    'datetime' => $states[$i]['datetime']
  ]];
}


$new = [
  "brazil" => [
    "updated" => time(),
    "states" => $statesUp,
    "confirmed" => $brazil['cases'],
    "recovered" => $brazil['recovered'],
    "deaths" => $brazil['deaths']
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


  for ($i = 0; $i < $len; $i++) {

    foreach ($check['brazil']['states'] as $checkUp) {
      if ($checkUp['datetime'] != $states[$i]['datetime']) {
        $data = file_get_contents('data.json');
        $data = json_decode($data, true);
        $data['brazil']['updated'] = $time;
        $data['brazil']['states'] = $statesUp;
        $data['brazil']['confirmed'] = $brazil['cases'];
        $data['brazil']['recovered'] = $brazil['recovered'];
        $data['brazil']['deaths'] = $brazil['deaths'];

        $data = json_encode($data);
        $json = fopen('data.json', 'w');
        fwrite($json, $data);
        fclose($json);
        echo "informações nos estados atualizada! <br>";

        old($time);

        return false;
      }
    }
  }


  if (isset($_GET['force'])) {
    $url = filter_var($_GET['force'], FILTER_SANITIZE_STRING);
    if ($url) {
      $data = file_get_contents('data.json');
      $data = json_decode($data, true);
      $data['brazil']['updated'] = $time;
      $data['brazil']['states'] = $statesUp;
      $data['brazil']['confirmed'] = $brazil['cases'];
      $data['brazil']['recovered'] = $brazil['recovered'];
      $data['brazil']['deaths'] = $brazil['deaths'];

      $data = json_encode($data);
      $json = fopen('data.json', 'w');
      fwrite($json, $data);
      fclose($json);
      echo "informações forçadas atualizada! <br>";
    }
  }


  if ($check['brazil']['confirmed'] != $brazil['cases'] || $check['brazil']['recovered'] != $brazil['recovered'] || $check['brazil']['deaths'] != $brazil['deaths']) {
    $data = file_get_contents('data.json');
    $data = json_decode($data, true);
    $data['brazil']['updated'] = $time;
    $data['brazil']['states'] = $statesUp;
    $data['brazil']['confirmed'] = $brazil['cases'];
    $data['brazil']['recovered'] = $brazil['recovered'];
    $data['brazil']['deaths'] = $brazil['deaths'];

    $data = json_encode($data);
    $json = fopen('data.json', 'w');
    fwrite($json, $data);
    fclose($json);
    echo "informações no Brasil atualizada! <br>";
  }

  if ($check['brazil']['confirmed'] != $brazil['cases'] || $check['brazil']['deaths'] != $brazil['deaths']) {
    old($time);
  }
}
echo "sem novas informações!";
