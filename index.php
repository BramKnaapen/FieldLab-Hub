<?php include("Includes/head.php");
if (isset($_SESSION["id"])) {
    echo "<script> window.location = 'projects.php'; </script>";
}
?>

<body>

    <div class="login-container">
        <div class="login">
            <div class="title-login">
                <h1>Inloggen</h1>
            </div>

            <form action="" method="post" class="form-email">

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" required>
                </div>


                <div>
                    <input type="submit" value="Verzend Email" name="send-email" id="send-email " class="emailbutton">
                </div>

                <a class="linkr" href="registration.php">Register</a>

        </div>
        </form>
        <?php
        if (isset($_POST["send-email"])) {
            $_SESSION["email"] = $_POST["email"];
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindparam(':email', $_SESSION["email"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo '<style> .form-email, .login{display: none;}</style>
                 <div class="login1">
                  <div class="title-login">
                <h1>Inloggen</h1>
            </div>
                      <form action="" method="post" class="form-token">
                      <div class="form-group">
                    <label for="token">Code</label>
                    <input type="text" name="token" id="token" required>
                     </div>
                     <input type="submit" value="Login" name="send-token" id="send-token"  class="emailbutton" >
                     </form>
                 </div>';
                $token = getToken();
                $stmt = $conn->prepare("UPDATE users SET token = :token, expirationdate = CURRENT_TIMESTAMP + INTERVAL 5 MINUTE WHERE email = :email");
                $stmt->bindparam(':email', $_SESSION["email"]);
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                echo '<script>
            Swal.fire({
                icon: "success",
                title: "Gelukt!",
                text: "Code is verzonden!",
            });
            </script>';
            } else {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Je email is niet gevonden, maak een account aan!",
                })
                .then(function() {
                    window.location = "registration.php";
                });
                </script>';
                session_destroy();
            }
        }

        if (isset($_SESSION["second_form"])) {
            echo '<style> .form-email, .login{display: none;}</style>
            <div class="login1">
            <div class="title-login">
            <h1>Inloggen</h1>
            </div>
            <form action="" method="post" class="form-token">
            <div class="form-group">
            <label for="token">Code</label>
            <input type="text" name="token" id="token" required>
            </div>
            <input type="submit" value="Login" name="send-token" id="send-token"  class="emailbutton" >
            </form>
            </div>';
        }

        if (isset($_POST["send-token"])) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindparam(':email', $_SESSION["email"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user["expirationdate"] > date("Y-m-d H:i:s")) {
                if ($_POST["token"] == $user["token"]) {
                    $_SESSION['rol'] = $user['rol'];
                    $_SESSION['id'] = $user['userID'];
                    echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Gelukt!",
                            text: "U bent succesvol ingelogd.",
                        })
                        .then(function() {
                            window.location = "projects.php";
                        });
                        </script>';
                } else {
                    $_SESSION["second_form"] = "yes";
                    echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Je code is komt niet over een. Probeer het opnieuw!",
                        })
                            .then(function() {
                            window.location = "index.php";
                        });
                        </script>';
                }
            } else {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Je code is helaas verlopen. Maak een nieuwe aan!",
                })
                .then(function() {
                    window.location = "index.php";
                });
                </script>';
                session_destroy();
            }
        }


        ?>

    </div>

</body>
<script src="/custom/sweetalert2.js"></script>

</html>