<h2>Delete <?= $config['name'] ?></h2>

<form action="?action=destroy&id=<?= request()->get('id') ?>" method="POST">
    <article>
        <header><b>Are you sure?</b></header>
        <p>Do you realy want to delete this user?</p>

        <footer class="grid">
            <button class="secondary" type="submit">Delete</button>
            <a href="/resources/<?= $config['key'] ?>.php?action=show&id=<?= request()->get('id') ?>"><button>Cancle</button></a>
        </footer>
    </article>
</form>