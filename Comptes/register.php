


<?php

require_once 'inc/functions.php'; 

session_start();


//VERIFIER SI VARIABLE SUPERGLOBALE $_POST EST VIDE OU PAS
if (!empty($_POST)) {




	$errors = array();
	require_once 'inc/db.php';

	//verifier si $_POST['username'] est correct ou pas
	if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
		$errors['username'] = "Votre pseudo n'est pas valide (alphanumérique)";
	} else {
		$req = $pdo->prepare('SELECT id FROM users WHERE username=?');
		$req->execute([$_POST['username']]);
		$user = $req->fetch();
		if ($user) {
			$errors['username'] = "Ce pseudo est déjà pris.";
		}
	}


	//verifier si $_POST['email'] est correct ou pas 
	if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Votre email n'est pas valide";
	} else {
		$req = $pdo->prepare('SELECT id FROM users WHERE email=?');
		$req->execute([$_POST['email']]);
		$user = $req->fetch();
		if ($user) {
			$errors['email'] = "Cet email est déjà utilisé pour un autre compte.";
		}
	}


	//verifier si $_POST['password'] est correct ou pas
	if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
		$errors['password'] = "Vous devez entrer un mot de passe valide";
	}


	//si le tableau d'erreurs est vide
	if (empty($errors)) {
		$req = $pdo->prepare("INSERT INTO users SET username=?, password=?, email=?, confirmation_token=?");
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$token = str_random(60);
		
		$req->execute([$_POST['username'], $password, $_POST['email'], $token]);
		$user_id = $pdo->lastInsertId();


		//On envoie à l'utilisateur un email de confirmation.
		mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8080/Comptes/confirm.php?id=$user_id&token=$token");

		$_SESSION['flash']['success'] = "Un email de confirmation vous a été envoyé pour valider votre compte.";

		header('Location: login.php');
		exit();
		
	}
	//fin d'execution de la requete si le tableau d'erreurs est vide


}

?>


<?php require 'inc/header.php'; ?>



<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
	<p>Vous n'avez pas rempli le formulaire correctement.</p>

	<ul>
		<?php foreach($errors as $error): ?>

			<li><?= $error; ?></li>

		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<form method="post" action="">
	
	<div class="form-group">
		<label for="">Pseudo</label>
		<input type="text" name="username" class="form-control"/>
	</div>

	<div class="form-group">
		<label for="">Email</label>
		<input type="text" name="email" class="form-control"/>
	</div>

	<div class="form-group">
		<label for="">Mot de passe</label>
		<input type="password" name="password" class="form-control"/>
	</div>

	<div class="form-group">
		<label for="">Confirmer votre mot de passe</label>
		<input type="password" name="password_confirm" class="form-control"/>
	</div>

	<button type="submit" class="btn btn-primary">S'inscrire</button>

</form>

<?php require 'inc/footer.php'; ?>