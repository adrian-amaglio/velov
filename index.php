<?php
/*
 * Page displays in french because it will be mostly used by french. Feel free to add language system.
 * TODO Cache the humongous API data
 * TODO Sort the stations
*/

/* Form submit values if a filter is active or not */
$SEND_BUTTON = [
  true  => [ 'value' => '',     'text' => 'Tout afficher'],
  false => [ 'value' => 'true', 'text' => 'Afficher la sélection'],
];

/* Fetching velov API in an associative array. This form is easier to use as keys are ids */
$data =
  json_decode(
    file_get_contents('https://api.jcdecaux.com/vls/v1/stations?apiKey=frifk0jbxfefqqniqez09tw4jvk37wyf823b5j1i&contract=lyon',
      false
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
    if ( ! $filter || isset($_GET[$station['number']])){
      echo '<tr>
        <td>
          <input type="checkbox" name="',$station['number'],'" ',(isset($_GET[$station['number']]) ? 'checked="checked"' : ''),' />
        </td>
      <td class="', ($station['status'] == 'OPEN' ? 'success' : 'danger' ) ,'" >'
          ,$station['name'],
        '</td>
        <td>',$station['available_bikes'], '</td>
        <td>', $station['available_bike_stands'], '</td>
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
  Parce qu’il faut télécharger toutes les données relatives aux stations vélov à chaque chargement ! N’hésitez pas à améliorer cette page sur <a href="http://github.com/adrian-amaglio/velov">Github</a>
</p>
<p>
  <h4>Pourquoi ces couleurs ?</h4>
  Le <span class="success">vert</span> est réservé aux stations actives, le <span class="danger">rouge</span> est pour celles inutilisables.
</p>
<p>
  <h4>C’est tout dans le désordre !</h4>
  Oui.
</p>
</body>
</html>
