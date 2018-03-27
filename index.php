<?php
/*
 * Page displays in french because it will be mostly used by french. Feel free to add language system.
 * TODO Cache the humongous API data
*/

/* Form submit if a filter is active or not */
$SEND_BUTTON = [
  true  => [ 'value' => '',     'text' => 'Tout afficher'],
  false => [ 'value' => 'true', 'text' => 'Afficher la sélection'],
];



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
    ;$filter = false;
}else
  $filter = false;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="style.css">
  <title>Because velov map is too heavy</title>
</head>
<body>
<form action="" method="get">
    <input type="hidden" name="filter" value="<?php echo $SEND_BUTTON[$filter]['value']; ?>" />
    <input type="submit" value="<?php echo $SEND_BUTTON[$filter]['text']; ?>" />
  <table>
  <tr><th>Sélectionner</th><th>Nom</th><th>Vélos</th><th>Places Libres</th></tr>
    <?php
  foreach ($data as $key => $station){
    if ( ! $filter || isset($_GET[$key])){
      echo '<tr>
        <td>
          <input type="checkbox" name="',$key,'" ',(isset($_GET[$key]) ? 'checked="checked"' : ''),' />
        </td>
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
    <input type="submit" value="<?php echo $SEND_BUTTON[$filter]['text']; ?>" />
</form>
<p>
  <h4>À quoi ça sert ?</h4>
  À garder ses stations habituelles (et leurs voisines) dans un favoris de navigateur ! Plus besoin de se ballader sur une carte tous les jours ! Cet outil est également accessible sur tous les téléphones peu performants, contrairement au site officiel.
</p>
<p>
  <h4>Pourquoi cette page est elle lente ?</h4>
  Parce qu’il faut télécharger toutes les données relatives aux stations vélov à chaque chargement ! N’hésitez pas à améliorer cette page sur <a href="http://github.com/adrianamaglio/velov">Github</a>
</p>
<p>
  <h4>Pourquoi ces couleurs ?</h4>
  Le <span class="success">vert</span> est réservé aux stations actives, le <span class="danger">rouge</span> est pour celles inutilisables.
</p>
</body>
</html>
