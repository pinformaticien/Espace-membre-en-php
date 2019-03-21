
<?php
	if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){

		require_once 'inc/db.php';
		require_once 'inc/functions.php';

		//on fait une requete préparée qui permet a l'utilisateur
		//de se connecter en  entrant son email ou son pseudo
		$req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
		$req->execute(['username' => $_POST['username']]);

		$user = $req->fetch();


		if (password_verify($_POST['password'], $user->password)) {
			session_start(); 
			$_SESSION['auth'] = $user;
			$_SESSION['flash']['success'] = "Vous êtes maintenant bien connecté.";
			header('Location: account.php');
			exit();
		} else{
			session_start(); 
			$_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrecte.";
		}
		

	}
?>


<?php require_once 'inc/functions.php'; ?>


<?php require 'inc/header.php'; ?>

	<h1>Se connecter</h1>

	<form method="post" action="">
	
		<div class="form-group">
			<label for="">Pseudo ou email</label>
			<input type="text" name="username" class="form-control"/>
		</div>

		
		<div class="form-group">
			<label for="">Mot de passe <a href="forget.php">(J'ai oublié mon mot de passe.)</a></label>
			<input type="password" name="password" class="form-control"/>
		</div>

		<div class="form-group">
			<label>
				<input type="checkbox" name="remember" value="1"/>Se souvenir de moi
			</label>
		</div>


		<button type="submit" class="btn btn-primary">Se connecter</button>

	</form>

<?php require 'inc/footer.php'; ?>