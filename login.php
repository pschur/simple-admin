<?php
const MAIN = true;

require __DIR__.'/config.php';

if (auth()->check()) {
    redirect(request()->get('back', request()->post('back', '/')));
}

$message = '';

if (request()->isPost()) {
    $username = request()->post('username');
    $password = request()->post('password');

    if (auth()->loginWithCredentials($username, $password)) {
        redirect(request()->post('back', BASE_URL.'/'));
    } else {
        $message = 'Invalid username or password';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - Admin Panel</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>
    <main class="container">
        <h2>Login</h2>
        <form method="post">
            <article>
                <div class="grid">
                    <div>
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <input type="hidden" name="back" value="<?= $_GET['back'] ?? '/' ?>">
                </div>
                <button type="submit">Login</button>
            </article>
        </form>
    </main>
</body>
</html>
