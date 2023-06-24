<?php
$boolean = true;
?>
<h2>Edit <?= $config['name'] ?></h2>

<form action="?action=update&id=<?= $item->id ?>" method="POST">
    <article>
        <header><b>Details</b></header>
        <div class="grid">
            <?php foreach ($config['fields'] as $field_name => $field): ?>
                <?php if (!$field['readonly'] && !($field['hidden'] ?? false)): $boolean = !$boolean; ?>
                    <div>
                        <label for="<?= $field_name ?>"><?= $field['label'] ?></label>
                        <?php if ($field['type'] == 'boolean'): ?>
                            <input type="checkbox" name="<?= $field_name ?>" id="<?= $field_name ?>" <?= ($item->{$field_name} ?? $field['default']) ? 'checked' : '' ?>>
                        <?php elseif ($field['type'] == 'text'): ?>
                            <textarea name="<?= $field_name ?>" id="<?= $field_name ?>" cols="30" rows="10"><?= $item->{$field_name} ?? $field['default'] ?></textarea>
                        <?php else: ?>
                            <input type="<?= $field['type'] ?>" name="<?= $field_name ?>" id="<?= $field_name ?>" value="<?= $item->{$field_name} ?? $field['default'] ?>">
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
            <button type="submit">Save</button>
        </footer>
    </article>
</form>