<h2><?= $config['plural_name'] ?></h2>

<!-- <details>
    <summary>Filters</summary>

    <article>
        <header><b>Filter</b></header>
        <form action="/resources/<?= $config['key'] ?>.php?action=index" method="get">
            <div class="grid">
                <div>
                    <label for="search_field">Search Field</label>
                    <select name="search_field" id="search_field">
                        <?php foreach ($config['fields'] as $field_name => $field): ?>
                            <?php if (isset($field['table']) && $field['table']): ?>
                                <option value="<?= $field_name ?>" <?= request()->get('search_field') == $field_name ? 'selected' : '' ?>><?= $field['label'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="search">Search</label>
                    <input type="text" name="search" value="<?= request()->get('search', '') ?>">
                </div>
                <div>
                    <label for="order">OrderByField</label>
                    <select name="order" id="order">
                        <?php foreach ($config['fields'] as $field_name => $field): ?>
                            <?php if (isset($field['table']) && $field['table']): ?>
                                <option value="<?= $field_name ?>" <?= request()->get('order') == $field_name ? 'selected' : '' ?>><?= $field['label'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid">
                <a href="/resources/<?= $config['key'] ?>.php?action=index"><button class="secondary">Reset</button></a>
                <button type="submit">Apply</button>
            </div>
        </form>
    </article>
</details> -->


<article>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <?php foreach ($config['fields'] as $field_name => $field): ?>
                <?php if (isset($field['table']) && $field['table']): ?>
                    <th><?= $field['label'] ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $item): ?>
            <tr>
                <td><?= $item->id ?></td>
                <?php foreach ($config['fields'] as $field_name => $field): ?>
                    <?php if (isset($field['table']) && $field['table']): ?>
                        <td><?= $item->{$field_name} ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td>
                    <a href="<?= BASE_URL ?>/resources/<?= $config['key'] ?>.php?action=show&id=<?= $item->id ?>">Show</a>  |
                    <a href="<?= BASE_URL ?>/resources/<?= $config['key'] ?>.php?action=edit&id=<?= $item->id ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <footer>
        <a href="<?= BASE_URL ?>/resources/<?= $config['key'] ?>.php?action=create"><button>Create <?= $config['name'] ?></button></a>
    </footer>
</article>
