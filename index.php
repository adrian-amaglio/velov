<?php
/*
 * Page displays in french because it will be mostly used by french. Feel free to add language system.
 * TODO Cache the humongous API data
*/

/* Fetching velov API in an associative array. This form is easier to use as keys are ids */
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

/* Check if a filter is enabled */

if(isset($_GET['filter'])){
  if(htmlentities($_GET['filter']))
    $filter = true;
  else
    $filter = false;
}else
  $filter = false;
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
<form action="" method="get">
  <?php if ($filter) { ?>
    <input type="hidden" name="filter" value="" />
    <input type="submit" value="Tout afficher" />
  <?php } else { ?>
    <input type="hidden" name="filter" value="true" />
    <input type="submit" value="Afficher uniquement la séléction" />
  <?php } ?>
  <table>
    <tr><th>Sélectionner</th><th>Nom</th><th>Vélos</th><th>Places Libres</th></tr>
    <?php
  foreach ($data as $key => $station){
    if ( ! $filter || isset($_GET[$key])){
      echo
      '<tr>
        <td><input type="checkbox" name="',$key,'"/></td>
        <td class="', ($station['open'] == 1 && $station['obsolete'] == 0 ? 'success' : 'danger' ) ,'" >'
          ,$station['name'],
        '</td>
        <td>',$station['AB'], '</td>
        <td>', $station['ABS'], '</td>
      </tr>';
    }
  }
    ?>
  </table>
  <?php if ($filter) { ?>
    <input type="submit" value="Tout afficher" />
  <?php } else { ?>
    <input type="submit" value="Afficher uniquement la séléction" />
  <?php } ?>
</form>
<p>
  <h4>À quoi ça sert ?</h4>
  À garder ses stations habituelles (et leurs voisines) dans un favoris de navigateur ! Plus besoin de se ballader sur une carte tous les jours ! Cet outil est également accessible sur tous les téléphones peu performants, contrairement au site officiel.
</p>
<p>
  <h4>Pourquoi cette page est elle lente ?</h4>
  Parce qu’il faut télécharger toutes les données relatives aux stations vélov à chaque chargement ! N’hésitez pas à améliorer cette page sur <a href="http://github.com/adrianamaglio/velov">Github</a>
</p>
</body>
</html>
