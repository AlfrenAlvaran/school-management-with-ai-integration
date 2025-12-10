<h1><?= htmlspecialchars($title) ?></h1>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $field => $msgs): ?>
            <?php foreach ($msgs as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="/register">
    <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Register</button>
</form>
