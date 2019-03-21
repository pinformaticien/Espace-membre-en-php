<?php 
	session_start();
	require 'inc/functions.php';
	logged_only();	

	//debut if parent
	if (!empty($_POST)) {
		
		//debut if imbrique
		if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){


			$_SESSION['flash']['danger'] = "Les mots de passe ne correspondent pas.";

		} else{

			$user_id = $_SESSION['auth']->id;

			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

			require_once 'inc/db.php';
			$pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password, $user_id]);
			$_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour.";


		}
		//fin if imbrique


	}
	//fin if parent
	
	require 'inc/header.php'; 
?>

	
		<h1>Bonjour <?= $_SESSION['auth']->username; ?></h1>

		<form method="post" action="">

			<div class="form-group">
				<input type="password" name="password" placeholder="Changer votre mot de passe" class="form-control">
			</div>

			<div class="form-group">
				<input type="password" name="password_confirm" placeholder="Confirmation du nouveau mot de passe" class="form-control">
			</div>

			<button type="submit" class="btn btn-primary">Changer mon mot de passe</button>

		</form>

		



<?php require 'inc/footer.php'; ?>