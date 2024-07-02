<?php include("Includes/head.php");
if (isset($_SESSION["id"])) {
    echo "<script> window.location = 'projects.php'; </script>";
}
?>

<body>
    <div class="login-container">
        <div class="login">

            <div class="title-login">
                <h1>Registreren</h1>
            </div>
            <form action="" method="post">

                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="button1">
                    <input type="submit" name="submit" id="submit" value="Registreren" class="emailbutton">
                </div>

            </form>
            <a class="linkr" href="index.php">Login</a>
        </div>
    </div>
</body>
<script src="/custom/sweetalert2.js"></script>


</html>

<?php

if (isset($_POST['submit'])) {
    try {
        $stmtCheck = $conn->prepare("SELECT * FROM users WHERE email = :email");
        if ($stmtCheck->execute([':email' => $_POST['email']])) {
            $emailCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            var_dump($emailCheck);
            if (isset($emailCheck['email'])) {
                echo '<script>
            Swal.fire({
                icon: "error",
                title: "E-mail bestaad al",
                text: "Maak een andere email aan!",
            })
            </script>';
                exit;
            };
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];
        $token = getToken();

        $stmt = $conn->prepare("INSERT INTO users (username, email, token) VALUES (:username, :email, :token)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt) {
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Gelukt!",
                text: "U bent succesvol geregistreerd!",
            })
            .then(function() {
                window.location = "index.php";
            });
            </script>';
        }
    } catch (PDOException $e) {
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Er ging iets mis!",
        })
        </script>';
    }
}



?>