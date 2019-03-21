
<?php
	if(!empty($_POST) && !empty($_POST['email'])){

		require_once 'inc/db.php';
		require_once 'inc/functions.php';

		//on fait une requete préparée qui permet a l'utilisateur
		//de se connecter en  entrant son email ou son pseudo
		$req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
		$req->execute([$_POST['email']]);

		$user = $req->fetch();


		if ($user) {
			session_start();

			$reset_token = str_random(60);

			$pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);

			$_SESSION['flash']['success'] = "Les instructions du rappel de mot de passe vous ont été envoyées par email.";
			mail($_POST['email'], 'Réinitialisation de votre mot de passe', "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nhttp://localhost:8080/Comptes/reset.php?id={$user->id}&token=$reset_token");
			header('Location: login.php');
			exit();
		} else{
			session_start(); 
			$_SESSION['flash']['danger'] = "Aucun compte ne correspond à cette adresse email.";
		}
		

	}
?>




<?php require 'inc/header.php'; ?>

	<h1>Mot de passe oublié</h1>

	<form method="post" action="">
	
		<div class="form-group">
			<label for="">Email</label>
			<input type="email" name="email" class="form-control"/>
		</div>



		<button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>

	</form>

<?php require 'inc/footer.php'; ?>