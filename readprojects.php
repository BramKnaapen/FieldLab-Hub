<?php
include("Includes/head.php");
if (!isset($_SESSION["rol"])) {
    echo "<script> window.location = 'index.php'; </script>";
}
?>

<body>

    <?php include("Includes/nav.php");
    echo '    <div class="read-container">';
    $stmt = $conn->prepare("SELECT * FROM projects WHERE projectID = :projectID");
    $stmt->bindparam(':projectID', $_GET['projectID']);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt1 = $conn->prepare("SELECT * FROM requirements WHERE projectID = :projectID");
    $stmt1->bindparam(':projectID', $_GET["projectID"]);
    $stmt1->execute();
    $requirements = $stmt1->fetchALL(PDO::FETCH_ASSOC);

    echo '<div class="project-specefic-container">
    <div class="project-maxwidth-container">
    <div class="requirements">
    <h1>' . htmlspecialchars($project["projectname"]) . '</h1>
    <h2>' . htmlspecialchars($project["nameprojectOwner"]) . '</h2>
     </div>
    <div class="requirements">
    <h2>Omschijving</h2>
    <p class="p-left">' . htmlspecialchars($project["summary"]) . '</p>
    </div>
    <div class="requirements">
    <h2>Requirements</h2>
    <ul class="requirements">';
    foreach ($requirements as $requirement) {
        echo '<li>' . htmlspecialchars($requirement["requirement"]) . '</li>';
    }
    echo '</ul>
    </div>
    <div class="requirements">
    <h2>Beschikbare plaatsen</h2>
    <p>'  . $project["avaliblePlaces"] . '</p>
    </div>
    </div>
    </div>
    </div>';

    if ($_SESSION["rol"] == "Opdrachtgever" || $_SESSION["rol"] == "Admin") {
        $stmt = $conn->prepare("SELECT * FROM registration WHERE projectID = :projectID");
        $stmt->bindparam(':projectID', $_GET['projectID']);
        $stmt->execute();
        $registrations = $stmt->fetchALL(PDO::FETCH_ASSOC);

        if ($registrations) {
            echo "
            <h1>Ingeschreven groepen</h1>
            <div class='read-container'>
            <div class='project-specefic-container'>";
            foreach ($registrations as $registration) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE userID = :userID");
                $stmt->bindparam(':userID', $registration["userID"]);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '
                <div class="project-maxwidth-container">
                <div class="requirements">
                <h2>' . $user["username"] . '</h2>
                <h4> email: <a style="color: #ffffff" href= "mailto:' . $user["email"] . '" target="_blank"> ' . $user["email"] . ' </a></h4>
                <p class="p-left">' . $registration["motive"] . '</p>
                </div>
                ';
            }
            echo "</div>
            </div>
            </div>";
        } else {
            echo "<h1>Nog geen inschrijvingen</h1>";
        }
    }

    include("Includes/footer.php");
    ?>
</body>

</html>