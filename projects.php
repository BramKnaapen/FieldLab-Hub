<?php
include("Includes/head.php");
if (!isset($_SESSION["rol"])) {
    echo "<script> window.location = 'index.php'; </script>";
}
if ($_SESSION["rol"] == "Opdrachtgever" || $_SESSION["rol"] == "Admin") {
    echo "<script> window.location = 'myprojects.php'; </script>";
}
?>

<body>
    <?php include("Includes/nav.php") ?>

    <h1 class="titel">All projects</h1>

    <?php

    $stmt = $conn->prepare("SELECT * FROM projects");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($projects);
    echo '<div class="projects-container">
        
    ';

    if ($projects) {
        foreach ($projects as $project) {
            if ($project["avaliblePlaces"] >= 1) {
                echo '
            <div class="project-container">
            <h1>' . htmlspecialchars($project["projectname"]) . '</h1>
            <h3>' . htmlspecialchars($project["nameprojectOwner"]) . '</h3>
            <p>' . htmlspecialchars($project["summary"]) . '</p>
            <form method="post">
            <input name="inschrijven' . htmlspecialchars($project["projectID"]) . '" type="submit" value="Inschrijven">
            </form>
            </div>
            ';
                $nameProject = "inschrijven" . htmlspecialchars($project["projectID"]);
                if (isset($_POST[$nameProject])) {
                    echo "<script> window.location = 'signupproject.php?projectID=" . htmlspecialchars($project["projectID"]) . "'; </script>";
                }
            }
        }
    } else {
        echo '<h1>Geen projecten gevonden</h1>';
    }
    echo '</div>';



    include("Includes/footer.php") ?>
</body>


</html>