<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bürgerservice</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<nav>
    <ul>
        <li><a href="#">Startseite</a></li>
        <li><a href="/de/buergerservice">Bürgerservice</a></li>
        <li><a href="/de/schwimmbad">Schwimmbad</a></li>
    </ul>
</nav>

<main>
    <div class="content">
        <!-- optiGov -->
        <?php
        require_once __DIR__ . '/optiGov.php';
        optiGov::renderWidget('/de/buergerservice');
        ?>
    </div>
</main>

<footer>
    <p>&copy; 2023</p>
</footer>

</body>
</html>