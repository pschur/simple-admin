<?php
const MAIN = true;
const INSTALLER = true;
require __DIR__.'/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$required = ['name', 'email', 'username', 'password'];
	foreach ($required as $field){
		if (!isset($_POST[$field]) || empty($_POST[$field])){
			$error = "Filed $field not found!";
		}
	}

	if (!isset($error)){
		DB::table('users')->insert([
			'name' => $_POST['name'],
			'email' => $_POST['email'],
			'username' => $_POST['username'],
			'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
		]);

		unlink(__FILE__);
		redirect(BASE_URL);
	}
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
</head>
<body class="container">
    <form method="POST" action="#">
		<h2>Setup first user</h2>
		<article>
			<header>Details</header>

			<label for="name">Name</label>
			<input type="text" name="name" />
			
			<label for="email">Email</label>
			<input type="email" name="email" />
			
			<label for="username">Username</label>
			<input type="text" name="username" />
			
			<label for="password">Password</label>
			<input type="password" name="password" />

			<footer>
				<button type="submit">Save</button>
			</footer>
		</article>
	</form>
</body>
</html>
