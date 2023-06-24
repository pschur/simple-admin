<?php
$boolean = true;
?>
<h2>Create <?= $config['name'] ?></h2>

<form action="?action=store" method="POST">
    <article>
        <header><b>Details</b></header>
        <div class="grid">
            <?php foreach ($config['fields'] as $field_name => $field): ?>
                <?php if ($field['readonly'] != true): $boolean = !$boolean; ?>
                    <div>
                        <label for="<?= $field_name ?>"><?= $field['label'] ?></label>
                        <?php if ($field['type'] == 'boolean'): ?>
                            <input type="checkbox" name="<?= $field_name ?>" id="<?= $field_name ?>" <?= $field['default'] ? 'checked' : '' ?>>
                        <?php elseif ($field['type'] == 'text'): ?>
                            <textarea name="<?= $field_name ?>" id="<?= $field_name ?>" cols="30" rows="10"><?= $field['default'] ?></textarea>
                        <?php else: ?>
                            <input type="<?= $field['type'] ?>" name="<?= $field_name ?>" id="<?= $field_name ?>" value="<?= $field['default'] ?>">
                        <?php endif; ?>
                    </div>
                    <?php if ($boolean): ?>
        </div>
        <div class="grid">
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <footer>
            <button type="submit">Create</button>
        </footer>
    </article>
</form>