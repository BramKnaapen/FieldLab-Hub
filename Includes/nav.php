<nav class="navbar">
    <div class="logo">
        <img src="../fieldlab-hub/img/logo.png" alt="Logo">
    </div>

    <div class="nav-container">
        <ul class="nav-links">
            <?php
            if ($_SESSION["rol"] == "Admin") {
                echo "<li><a href='myprojects.php'>ALLE OPDRACHTEN</a></li>";
            } else {
                echo "<li><a href='myprojects.php'>MIJN OPDRACHTEN</a></li>";
            }
            if ($_SESSION["rol"] == "Opdrachtnemer") {
                echo "<li><a href='projects.php'>OPDRACHTEN</a></li>";
            }
            if ($_SESSION["rol"] == "Opdrachtgever") {
                echo '<li><a href="createprojects.php">OPDRACHT MAKEN</a></li>';
            } ?>
            <form method="post">
                <button type="submit" name="logout" style="background-color: transparent; border: none;"><i class="fa-solid fa-arrow-right-from-bracket" style="color:#FFFFFF; font-size: 20px;
                cursor: pointer"></i></button>
            </form>
            <?php if (isset($_POST["logout"])) {
                session_destroy();
                echo "<script> window.location = 'index.php'; </script>";
            } ?>
        </ul>
    </div>
    <button class="menu-toggle" aria-label="Toggle navigation">
        &#9776;
    </button>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navContainer = document.querySelector('.nav-container');

        menuToggle.addEventListener('click', function() {
            navContainer.classList.toggle('active');
        });
    });
</script>