<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '../../../Inc/config.inc.php');

	$timedate = date("Y-m-d")." ".date("H:i:s");

	$bar = $_POST['bar'];

	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s<br />", mysqli_connect_error());
	    exit();
	}

// query to get data from database
	if ($bar=="all" || $bar=="")
	{$result = mysqli_query($conn,"SELECT * FROM BeerList");}
	elseif ($bar=="theatre")
	{$result = mysqli_query($conn,"SELECT * FROM BeerList WHERE `Bar` LIKE 'Theatre - Locale & London%'");}
	elseif ($bar=="main")
	{$result = mysqli_query($conn,"SELECT * FROM BeerList WHERE `Bar` LIKE 'Main Hall - Rest of UK%'");}
	elseif ($bar=="keg")
	{$result = mysqli_query($conn,"SELECT * FROM BeerList WHERE `Bar` LIKE 'Main Hall - Craft Keg%'");}

	$sendemail = TRUE;
	$to = $email;
	$subject = "Luton Beer Festival - Full Beer List";
	$headers = "From: no-reply@xxxxx.org.uk\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$userlist = "";
	//Start compiling HTML based email as we are using formatting..
	$emailoutput = "<html>
									<head>
									<h1>Beer List for 35th Luton Beer & Cider Festival</h1>
									</head>
									<body>
									Thank you for requesting the Beer List for the 35th Luton Beer & Cider Festival, which takes place between 13th to 18th February 2018.
									<p>
									This is a provisional list which is subject to change, we will aim to confirm this list closer to the Festival.
									<p>
									Simply print out this email, or copy into Excel or similar to create your own Beer Menu.
									<p>
									<table rules='all' style='border: 1px solid black; cell-padding=10'>
									<tr>
										<thead>
										<th>No</th>
										<th>Bar</th>
										<th>Style</th>
										<th>Colour</th>
										<th>Brewery</th>
										<th>Beer</th>
										<th>ABV</th>
										<th>Dietary Requirement</th>
										<th>Tasting Notes</th>
										</thead>
									</tr>
									<tbody>";
// loop through database query and fill email output variable.
	while($row = mysqli_fetch_array($result))
		{
			$emailoutput.= "<tr>";
				$emailoutput.= "<td>" . $row['id'] . "</td>";
				$emailoutput.= "<td>" . $row['Bar'] . "</td>";
				$emailoutput.= "<td>" . $row['Style'] . "</td>";
				if ($row['Colour'] == "a"){
					$emailoutput.= "<td style='background-color: #FFB533'></td>";
				}
				elseif ($row['Colour'] == "g"){
					$emailoutput.= "<td style='background-color: #FFFC33'></td>";
				}
				elseif ($row['Colour'] == "d"){
					$emailoutput.= "<td style='background-color: #000000'></td>";
				}
				$emailoutput.= "<td>" . $row['Brewery'] . "</td>";
				$emailoutput.= "<td>" . $row['Beer'] . "</td>";
				$emailoutput.= "<td>" . $row['ABV'] . "</td>";
				$emailoutput.= "<td>" . $row['DietaryReq'] . "</td>";
				$emailoutput.= "<td>" . $row['TastingNotes'] . "</td>";
				$emailoutput.= "</tr>";
		}
		$emailoutput.= "</tbody>";
		$emailoutput.= "</table>";

		$emailoutput.= "Key:\r\n";
		$emailoutput.= "<p>VG - &quot;Vegetarian-friendly&quot; beers in which no isinglass finings are used but the presence of other ingredients which make them unsuitable for vegans.</p>";
		$emailoutput.= "<p>VF - &quot;Vegan-friendly&quot; beers in which no isinglass finings or other ingredients derived from animals are used.</p>";
		$emailoutput.= "<p>GF - Gluten free beers in which cereals containing gluten have not been used to produce the beers, or the gluten has been removed from the beer post-production.</p>";
		$emailoutput.= "</body>
										</html>";

		$emailoutput.= "\r\n****PLEASE NOTE: This email was sent from an un-monitored account****\r\n";
	// Send the email
	if ($sendemail == FALSE)
	{
		echo "<p>Email output is DISABLED</p>\n";
	}
	else
	{
	if(mail($to, $subject, $emailoutput, $headers))
	{
	  printf("<p>Email sent successfully.</p>\n");
	}
	else
	{
	  printf("<p>Email send failed.</p>\n");
	}
}


?>
