<?php

if(isset($_GET['q'])){

	// VARIABLE
	$shortcut = htmlspecialchars($_GET['q']);

	
	$bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');
	$req =$bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		if($result['x'] != 1){
			header('location: ../?error=true&message=Adresse url non connue');
			exit();
		}

	}

	
	$req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
	$req->execute(array($shortcut));

	while($result = $req->fetch()){

		header('location: '.$result['url']);
		exit();

	}

}


if(isset($_POST['url'])){

	$url = $_POST['url'];

	if(!filter_var($url, FILTER_VALIDATE_URL)){
	header('location: ../?error=true&message=Adresse url non valide');
	exit();
	};
  $shortcut = crypt($url, rand());

  $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','');
  
  $req = $bdd->prepare('SELECT COUNT(*) AS X FROM links WHERE url = ?');

  $req->execute(array($url));

  while($result = $req->fetch()){

	if($result['x'] != 0){
		header('location; ../?error=true&message=adresse déjà raccourcie');
		exit();
	}
  }
  $req = $bdd->prepare('INSERT INTO links(url,shortcut) VALUES (?,?)');
 $req->execute(array($url, $shortcut));

 header('location: ../?short='.$shortcut);
 exit();






};

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="design/default.css">
	<link rel="shortcut icon" href="pictures/favico.png" type="image/x-icon">
	<title>Document</title>
</head>

<body>
	<!-- presentation -->
	<section id="hello">

		<div class='container'>

			 <header>
				<img src="pictures/logo.png" alt="logo" id='logo'>
	         </header>
			 
		</div>
		     <h1>Une url longue ? raccourcissez-là ?</h1>
			 <h2>largement meilleur et plus court que les autres</h2>
              <form method="post" action="../">
			<input type="url" name="url" placeholder="Collez un lien à raccourcir">
             <input type="submit" value="Raccourcir">
		</form>
		<?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
			<div class="center">
				<div class="result">
					<p><?php echo htmlspecialchars($_GET['message']);?></p>
		      </div>
			</div>
		<?php } else if(isset($_GET['short'])){
		?>
<div class="center">
				<div class="result">
					<p>URL RACCOURCIE : http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?></p>
					
		      </div>
			</div>
		<?php } ?>
	</section>
	<section class="brands">
		
			<h3>Ces marques nous font confiance</h3>
			<img src="pictures/1.png" alt="1" class="pictures">
			<img src="pictures/2.png" alt="2" class="pictures">
			<img src="pictures/3.png" alt="3" class="pictures">
			<img src="pictures/4.png" alt="4" class="pictures">
		
	</section>
	<footer>
		<img src="pictures/logo2.png" alt="logo" id="logo"><br>2018 bitly<br><a href="#">Contact </a>-<a href="#"> A propos</a>
	</footer>

</body>
</html>