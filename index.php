<!DOCTYPE HTML>

<?php
// api and config
require 'mojang-api.class.php';
include("config.php");



// mysql connecting
$pdo = new PDO('mysql:host='.$ip.';dbname='.$database.'', $user, $password);

?>


<html>
<head>
    <title><?php echo $richname; ?></title>
    <meta name="description" content="<?php echo $richtext; ?>">
    <meta name="theme-color" content="#424242">
    <meta charset="UTF-8">	
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		</noscript>
		<script src='https://www.google.com/recaptcha/api.js'></script>
<style>

table {
    width: 100%;
    margin: 2em 0;
    border-collapse: collapse;
    word-break:normal;
}

td {
    padding: .5em;
    vertical-align: top;
    border: 1px solid #bbbbbb;
}

th {
    padding: .5em;
    text-align: left;
    border: 1px solid #bbbbbb;
    border-bottom: 3px solid #bbbbbb;
    background:#f4f7fa;
}

	
.table-scrollable {
	width: 100%;
	overflow-y: auto;
	margin: 0 0 1em;	
}

.table-scrollable::-webkit-scrollbar {
	-webkit-appearance: none;
	width: 14px;
	height: 14px;
}

.table-scrollable::-webkit-scrollbar-thumb {
	border-radius: 8px;
	border: 3px solid #fff;
	background-color: rgba(0, 0, 0, .3);
}

</style>
		
		
</head>

<body id="landing">
<!-- Header -->

<header id="header">


    <h1><a href="<?php echo $maindomain; ?>"><?php echo $servername; ?></a></h1>
    <nav id="nav">
        <ul>
			<li><a href="<?php echo $domain;?>">Startseite</a></li>
        </ul>
	</nav>	
</header>
<!-- One -->
<section class="wrapper style special">
    <header class="major">
        <h2><?php echo $servername; ?></h2>
    </header>	
    <div class="container">
        <div class="row">
        
			<div class="6u 12u$(medium)">
                <section class="box">
                    <h3>Spieler suchen</h3>
						<p></p>
                    <form id="playersearch" action="search.php" method="post">
						<p>Spielername: <input type="text" name="username" class="form-control" placeholder="Spielername" required /></p>
						<p></p>
						<input type="submit" value="Spieler suchen" />
						<p></p>
					</form>

                </section>
				
			</div>
			<div class="6u 12u$(medium)">
                <section class="box">
                    <h3>DkCoins Stats</h3>
                    <h4>Version: <?php echo $version;?> von TheSystems</h4>

                    <?php

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://project.the-systems.eu/api/resource/?resourceid=4&type=latest",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 2,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    if ($err) {
                        ?><h4>Ein Fehler beim Versions-Überprüfen ist aufgetreten.</h4><?php
                    } else {
                        $response = json_decode($response);
                        if($response->name == $version){
                            ?><h4>Du nutzt die neuste Version.</h4><?php
                        } else {
                            ?><h4>Du nutzt eine alte Version. Die neuste ist: <?php echo $response->name; ?></h4><?php
                        }
                    }

                    ?>
                    <?php
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://project.the-systems.eu/api/resource/?resourceid=4&type=info",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 2,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    if ($err) {
                        ?><h4>Ein Fehler beim Versions-Überprüfen ist aufgetreten.</h4><?php
                    } else {
                        $response = json_decode($response);
                        $support = $response->support;
                        $url = $response->url;

                    }
                    ?>
                    <p></p>
                    <p><a href="<?php echo $support; ?>" class="button">Support Discord</a></p>
                    <p><a href="<?php echo $url; ?>" class="button">Webseite</a></p>

                </section>
				</div>
				<div class="12u 12u$(medium)">
					<section class="box">
					
						<h3>Top <?php echo $topplayeramount;?> Coins</h3>
						

					
					<div class="table-scrollable">
						<table>
						<tr>
							<th>Rang</th>
							<th>Name</th>
							<th></th>
							<th>Coins</th>
						</tr>
					                    <?php
                    // mysql data 
						$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$statement = $pdo->prepare("SELECT * FROM DKCoins_players ORDER BY coins DESC LIMIT :top");
						$statement->execute(array('top' => $topplayeramount));   
						
						$anzahl_user = $statement->rowCount();	
						if ($anzahl_user != "0") {
							// users found
                            $num = 1;
							while($row = $statement->fetch()) {
								echo '<tr>';
								echo '<td>'.$num++.'.</td>';
								echo '<td>'.$row['name'].'</td>';
								echo '<td><img src="https://minotar.net/helm/'.$row['name'].'/50" class="img-responsive"></td>';
								echo '<td>'.$row['coins'].'</td>';
								echo '</tr>';
								
							}
                            } else {
								// no users in db found
                                echo '
								<tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><img src="https://minotar.net/helm/Notch/50" class="img-responsive"></td>
                                    <td>-</td>
                                </tr>';
								
                            
                            }
                    

					
                    ?>
					</section>
				</div>
			</section>
		</div>
	</section>
	
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
</body>
</html>
