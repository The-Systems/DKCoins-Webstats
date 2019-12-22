<!DOCTYPE HTML>

<?php

// api and config
require 'mojang-api.class.php';
include("config.php");



// mysql connecting
$pdo = new PDO('mysql:host='.$ip.';dbname='.$database.'', $user, $password);

// if no post set, rec to startpage
if(!isset($_POST['username'])){
	header('Location: '.$domain);
 }
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
                    <form id="login" action="search.php" method="post">
						<p>Spielername oder Rang: <input type="text" name="username" class="form-control" placeholder="Spielername oder Rang" required /></p>
						<p></p>
						<input type="submit" value="Spieler suchen" />
						<p></p>
					</form>
					

                </section>
				
			</div>

			<div class="6u 12u$(medium)">
                <section class="box">
                    <h3>Coins </h3>
				<?php
// convert name to uuid
    $name = htmlspecialchars($_POST['username']);
		$uuid = MojangAPI::getUuid($name);
		$fulluuid = MojangAPI::formatUuid($uuid);

		// mysql data
	$statement = $pdo->prepare("SELECT * FROM DKCoins_players WHERE uuid = ?");
	$statement->execute(array($fulluuid)); 
	$anzahl_user = $statement->rowCount();	
	if ($anzahl_user == "1") {
  // user found
	while($row = $statement->fetch()) {

										echo '<h4>von '.$row['name'].'</h4>';
										echo '<p><img src="https://minotar.net/helm/'.$row['name'].'/100" class="img-responsive"></p>';
										echo '<p>Er hat zurzeit '.$row['coins'].' Coins</p>';
	  
		}
	} else {
		// noch user found
		?><h4>Der Spieler <?php echo $_POST['username'];?> wurde nicht gefunden, er hat anscheinend noch nie auf dem Server gespielt. <p>
		<p>mh schade..</h4><?php
	}
	
	  
		?>
					</section>
				</div>

		</div>
	</section>
	
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
</body>
</html>
