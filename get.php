<?php
// API

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
    "confirmed" => $brazil['confirmed'],
    "recovered" => $brazil['recovered'],
    "deaths" => $brazil['deaths']
  ]
];

$time = time();

if (!is_dir('backup')) {
  mkdir('backup', 0777, true);
}

if (!file_exists('data.json')) {
  $json = fopen('data.json', 'w');
  $new = json_encode($new);
  fwrite($json, $new);
  fclose($json);
  echo "data.json criado com sucesso! <br>";

  copy('data.json', 'backup/data.json');
  echo "backup realizado com sucesso! <br>";
} else {

  $check = file_get_contents('data.json');
  $check = json_decode($check, true);


  for ($i = 0; $i < $len; $i++) {

    foreach ($check['brazil']['states'] as $checkUp) {
      if ($checkUp['datetime'] != $states[$i]['datetime']) {
        $data = file_get_contents('data.json');
        $data = json_decode($data, true);
        $data['brazil']['updated'] = $time;
        $data['brazil']['states'] = $statesUp;
        $data['brazil']['confirmed'] = $brazil['confirmed'];
        $data['brazil']['recovered'] = $brazil['recovered'];
        $data['brazil']['deaths'] = $brazil['deaths'];

        $data = json_encode($data);
        $json = fopen('data.json', 'w');
        fwrite($json, $data);
        fclose($json);
        echo "informações nos estados atualizada! <br>";

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
      $data['brazil']['confirmed'] = $brazil['confirmed'];
      $data['brazil']['recovered'] = $brazil['recovered'];
      $data['brazil']['deaths'] = $brazil['deaths'];

      $data = json_encode($data);
      $json = fopen('data.json', 'w');
      fwrite($json, $data);
      fclose($json);
      echo "informações forçadas atualizada! <br>";
    }
  }


  if ($check['brazil']['confirmed'] != $brazil['confirmed'] || $check['brazil']['recovered'] != $brazil['recovered'] || $check['brazil']['deaths'] != $brazil['deaths']) {
    $data = file_get_contents('data.json');
    $data = json_decode($data, true);
    $data['brazil']['updated'] = $time;
    $data['brazil']['states'] = $statesUp;
    $data['brazil']['confirmed'] = $brazil['confirmed'];
    $data['brazil']['recovered'] = $brazil['recovered'];
    $data['brazil']['deaths'] = $brazil['deaths'];

    $data = json_encode($data);
    $json = fopen('data.json', 'w');
    fwrite($json, $data);
    fclose($json);
    echo "informações no Brasil atualizada! <br>";
  }
}

// Brasil.io
// Compara informações mais atualizada e atualiza
$get = [
  'AC' => 'Acre',
  'AL' => 'Alagoas',
  'AP' => 'Amapá',
  'AM' => 'Amazonas',
  'BA' => 'Bahia',
  'CE' => 'Ceará',
  'DF' => 'Distrito Federal',
  'ES' => 'Espírito Santo',
  'GO' => 'Goiás',
  'MA' => 'Maranhão',
  'MT' => 'Mato Grosso',
  'MS' => 'Mato Grosso do Sul',
  'MG' => 'Minas Gerais',
  'PA' => 'Pará',
  'PB' => 'Paraíba',
  'PR' => 'Paraná',
  'PE' => 'Pernambuco',
  'PI' => 'Piauí',
  'RJ' => 'Rio de Janeiro',
  'RN' => 'Rio Grande do Norte',
  'RS' => 'Rio Grande do Sul',
  'RO' => 'Rondônia',
  'RR' => 'Roraima',
  'SC' => 'Santa Catarina',
  'SP' => 'São Paulo',
  'SE' => 'Sergipe',
  'TO' => 'Tocantins'
];

$states = [];

foreach ($get as $key => $state) {
  $item = file_get_contents("https://brasil.io/api/dataset/covid19/caso/data?state={$key}&is_last=True&place_type=state&format=json");

  $item = json_decode($item, true);
  $item = $item['results'][0];

  $states += [strtolower($item['state']) => [
    'confirmed' => $item['confirmed'],
    'deaths' => $item['deaths'],
  ]];
}

$brasilio = $states;
$data = file_get_contents("data.json");
$data = json_decode($data, true);
$data = $data['brazil']['states'];


$modified = false;

foreach ($data as $key => $value) {
  if ($data[$key]['confirmed'] != $brasilio[$key]['confirmed']) {
    if ($brasilio[$key]['confirmed'] > $data[$key]['confirmed']) {
      $data[$key]['confirmed'] = $brasilio[$key]['confirmed'];
      echo 'confirmed <br>';
      $modified = true;
    }
    if ($brasilio[$key]['deaths'] > $data[$key]['deaths']) {
      $data[$key]['deaths'] = $brasilio[$key]['deaths'];
      echo 'deaths <br>';
      $modified = true;
    }
  }
}

if ($modified) {
  $novo = file_get_contents("data.json");
  $novo = json_decode($novo, true);
  $states = $novo['brazil']['states'] = $data;
  $novo = [
    "brazil" => [
      "updated" => time(),
      "states" => $states,
      "confirmed" => $novo['brazil']['confirmed'],
      "recovered" => $novo['brazil']['recovered'],
      "deaths" => $novo['brazil']['deaths']
    ]
  ];

  $json = fopen('data.json', 'w');
  $novo = json_encode($novo);
  fwrite($json, $novo);
  fclose($json);

  echo 'Divergência entre API e Brasi.io - Atualizado!';
}

echo "sem novas informações! <br>";
