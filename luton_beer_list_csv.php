<?php
/* vars for export */
$csv_filename = 'Luton Beer & Cider Festival - Beer List.csv';

require_once($_SERVER["DOCUMENT_ROOT"] . '../../../Inc/config.inc.php');
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if (mysqli_connect_errno())
{echo "Failed to connect to MySQL: " . mysqli_connect_error();}

// create empty variable to be filled with export data
$csv_export = "";

// get the Bar from the query string
$bar = $_POST['bar'];

// query to get data from database
if ($bar=="all" || $bar=="")
{$query = mysqli_query($conn,"SELECT * FROM BeerList_2018");}
elseif ($bar=="theatre")
{$query = mysqli_query($conn,"SELECT * FROM BeerList_2018 WHERE `Bar` LIKE 'Theatre - Locale & London%'");}
elseif ($bar=="main")
{$query = mysqli_query($conn,"SELECT * FROM BeerList_2018 WHERE `Bar` LIKE 'Main Hall - Rest of UK%'");}
elseif ($bar=="keg")
{$query = mysqli_query($conn,"SELECT * FROM BeerList_2018 WHERE `Bar` LIKE 'Main Hall - Craft Keg%'");}

// Create header line..
		$csv_export.='Id,';
		$csv_export.='Bar,';
		$csv_export.='Style,';
		$csv_export.='Colour,';
		$csv_export.='Brewery,';
		$csv_export.='Beer,';
		$csv_export.='ABV,';
		$csv_export.='Dietary Requirements,';
		$csv_export.='Tasting Notes,';
		$csv_export.= "\n";
// loop through database query and fill export variable
while($row = mysqli_fetch_array($query)) {

    $csv_export.= '"'.$row['id'].'",';
		$csv_export.= '"'.$row['Bar'].'",';
		$csv_export.= '"'.$row['Style'].'",';
		$csv_export.= '"'.$row['Colour'].'",';
		$csv_export.= '"'.$row['Brewery'].'",';
		$csv_export.= '"'.$row['Beer'].'",';
		$csv_export.= '"'.$row['ABV'].'",';
		$csv_export.= '"'.$row['DietaryReq'].'",';
		$csv_export.= '"'.$row['TastingNotes'].'",';
		$csv_export.= "\n";

}
$csv_export.="This is a provisional list which is subject to change, we will aim to confirm this list closer to the Festival.\n";
$csv_export.= "\n\n";
$csv_export.= "Key:\r\n";
$csv_export.= "VG - &quot;Vegetarian-friendly&quot; beers in which no isinglass finings are used but the presence of other ingredients which make them unsuitable for vegans.\n";
$csv_export.= "VF - &quot;Vegan-friendly&quot; beers in which no isinglass finings or other ingredients derived from animals are used.\n";
$csv_export.= "GF - Gluten free beers in which cereals containing gluten have not been used to produce the beers, or the gluten has been removed from the beer post-production.\n";

// Export the data and prompt a csv file for download
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=".$csv_filename."");
echo($csv_export);

?>
