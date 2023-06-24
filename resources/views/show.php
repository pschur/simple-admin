<h2>Show <?= $config['name'] ?></h2>
<article>
    <header><b>Details</b></header>

    <table>
        <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>ID</th>
            <td><?= $item->id ?></td>
        </tr>
        <?php foreach ($config['fields'] as $field_name => $field): ?>
            <tr>
                <td><?= $field['label'] ?></td>
                <td>
                    <?php if ($field['type'] == 'boolean'): ?>
                        <?= $item->{$field_name} ? 'Yes' : 'No' ?>
                    <?php elseif ($field['type'] == 'password'): ?>
                        <span data-tooltip="You can`t see the user password. It`s impossible!">***********</span>
                    <?php else: ?>
                        <?= $item->{$field_name} ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <footer>
        <a href="?action=edit&id=<?= $item->id ?>" role="button">Edit</a>
        <a href="?action=delete&id=<?= $item->id ?>" class="secondary" role="button">Delete</a>
    </footer>
</article>