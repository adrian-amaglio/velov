<?php
$data =
json_decode(
file_get_contents(
  'https://velov.grandlyon.com/fr/les-stations.html?type=777&tx_glstationsvelov_pi1%5Baction%5D=listOfVelovWidthInfoStation&tx_glstationsvelov_pi1%5Bcontroller%5D=StationVelov',
  false,
  stream_context_create([
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query([])
      ]
    ])
  ),
  true
);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Because velov map is too heavy</title>
  <style>
    html{
      background-color:black;
      color:white;
    }
    .success{
      color:green;
    }
    .danger{
      color: red;
    }
  </style>
</head>
<body>
<table>
<tr><th>Nom</th><th>Vélos</th><th>Places Libres</th></tr>
<?php
foreach ($data as $station){
  echo '<tr><td class="', ($station['open'] == 1 || $station['obsolete'] == 0 ? 'success' : 'danger' ) ,'" >',$station['name'], '</td><td>',$station['AB'], '</td><td>', $station['ABS'], '</td></tr>';
}
?>
</table>
</body>
</html>
