<?php
include("Includes/head.php");
if (!isset($_SESSION["rol"])) {
    echo "<script> window.location = 'index.php'; </script>";
}
?>

<body>
    <?php include("Includes/nav.php") ?>

    <h1 class="titel">Mijn Opdrachten</h1>

    <div class="projects-container">

        <?php

        if ($_SESSION["rol"] == "Opdrachtnemer") {
            $stmt = $conn->prepare("SELECT * FROM registration WHERE userID = :userID");
            $stmt->bindparam(':userID', $_SESSION["id"]);
            $stmt->execute();
            $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($registrations as $registration) {
                $stmt = $conn->prepare("SELECT * FROM projects WHERE projectID = :projectID ");
                $stmt->bindparam(':projectID', $registration["projectID"]);
                $stmt->execute();
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($projects as $project) {
                    echo '
            <div class="project-container">
            <h1>' . htmlspecialchars($project["projectname"]) . '</h1>
            <h3>' . htmlspecialchars($project["nameprojectOwner"]) . '</h3>
            <p>' . htmlspecialchars($project["summary"]) . '</p>
            <form method="post">
            <input name="bekijk' . htmlspecialchars($project["projectID"]) . '" type="submit" value="Bekijk">
            </form>
            </div>';
                    $nameProject = "bekijk" . $project["projectID"];
                    if (isset($_POST[$nameProject])) {
                        echo "<script> window.location = 'readprojects.php?projectID=" . htmlspecialchars($project["projectID"]) . "'</script>";
                    }
                }
                $_SESSION["projects"] = true;
            }
        } else {

            if ($_SESSION["rol"] == "Opdrachtgever") {
                $stmt = $conn->prepare("SELECT * FROM projects WHERE userID = :userID");
                $stmt->bindparam(':userID', $_SESSION["id"]);
                $stmt->execute();
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if ($_SESSION["rol"] == "Admin") {
                $stmt = $conn->prepare("SELECT * FROM projects");
                $stmt->execute();
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            if ($projects) {
                foreach ($projects as $project) {

                    echo '
                    <div class="project-container">
                    <h1>' . htmlspecialchars($project["projectname"]) . '</h1>
                    <h3>' . htmlspecialchars($project["nameprojectOwner"]) . '</h3>
                    <p>' . htmlspecialchars($project["summary"]) . '</p>
                    <p>beschikbare plaatsen : ' . htmlspecialchars($project["avaliblePlaces"]) .'
                    <form method="post">
                    <input name="bekijk' . htmlspecialchars($project["projectID"]) . '" type="submit" value="Bekijk">';


                    if ($_SESSION["rol"] == "Opdrachtgever" || $_SESSION["rol"] == "Admin") {
                        echo '        
                <input name="update' . htmlspecialchars($project["projectID"]) . '" type="submit" value="Update">
                <input name="verwijder' . htmlspecialchars($project["projectID"]) . '" type="submit" value="Verwijder">';
                    }
                    echo '</form>
                    </div>';

                    $nameProject = "bekijk" . $project["projectID"];
                    if (isset($_POST[$nameProject])) {
                        echo "<script> window.location = 'readprojects.php?projectID=" . htmlspecialchars($project["projectID"]) . "'</script>";
                    }

                    $nameProject = "update" . $project["projectID"];
                    if (isset($_POST[$nameProject])) {
                        echo "<script> window.location = 'updateprojects.php?projectID=" . htmlspecialchars($project["projectID"]) . "'</script>";
                    }

                    $nameProject = "verwijder" . $project["projectID"];
                    if (isset($_POST[$nameProject])) {
                        echo '<script> Swal.fire({
                title: "Weet je het zeker?",
                text: "Dit kan je niet terugdraaien!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Verwijder"
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    title: "Verwijderd!",
                    text: "Jou project is verwijderd.",
                    icon: "success"
                    }).then(function() {
                    document.getElementById("verwijderen").submit();
                    });
                }
                });</script>';
                    }
                }
                $_SESSION["projects"] = true;
            }
        }
        if (!isset($_SESSION["projects"])) {
            echo '<h1>Geen projecten gevonden</h1>';
        }

        unset($_SESSION["projects"]);
        ?>

    </div>
    <form method="post" id="verwijderen">
        <input type="hidden" name="verwijderen" id="verwijderen" value="<?php echo htmlspecialchars($project["projectID"]); ?>">
    </form>

    <?php
    if (isset($_POST["verwijderen"])) {
        $stmt2 = $conn->prepare("DELETE FROM registration WHERE projectID = :projectID");
        $stmt2->bindparam(':projectID', $_POST["verwijderen"]);
        $stmt2->execute();

        $stmt1 = $conn->prepare("DELETE FROM requirements WHERE projectID = :projectID");
        $stmt1->bindparam(':projectID', $_POST["verwijderen"]);
        $stmt1->execute();

        $stmt = $conn->prepare("DELETE FROM projects WHERE projectID = :projectID");
        $stmt->bindparam(':projectID', $_POST["verwijderen"]);
        $stmt->execute();
        echo "<script> window.location = 'myprojects.php'; </script>";
    }

    include("Includes/footer.php") ?>
</body>


</html>