<?php

$nav = [
    'Home' => '/index.php',
    'Users' => '/resources/users.php',
    'Posts' => '/resources/posts.php',
];

?>
<nav>
    <ul>
        <li><strong>Admin Panel</strong></li>
        <?php foreach ($nav as $name => $url) : ?>
            <li><a href="<?= $url ?>"><?= $name ?></a></li>
        <?php endforeach; ?>
    </ul>
    <ul>
        <li><?= auth()->user()->name ?></li>
        <li><a href="/actions/logout.php">Logout</a></li>
    </ul>
</nav>