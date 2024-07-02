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
    <?php include("Includes/nav.php");
    echo "<div class='read-container'>";
    if (isset($_GET["projectID"])) {
        $stmt = $conn->prepare("SELECT * FROM projects WHERE projectID = :projectID");
        $stmt->bindparam(':projectID', $_GET["projectID"]);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt1 = $conn->prepare("SELECT * FROM requirements WHERE projectID = :projectID");
        $stmt1->bindparam(':projectID', $_GET["projectID"]);
        $stmt1->execute();
        $requirements = $stmt1->fetchALL(PDO::FETCH_ASSOC);

        echo '
        <div class="project-specefic-container">
        <div class="requirements">
        <h1>' . htmlspecialchars($project["projectname"]) . '</h1> 
        <h3>' . htmlspecialchars($project["nameprojectOwner"]) . '</h3>
        </div>
        <div class="requirements">
        <p>' . htmlspecialchars($project["summary"]) . '</p>
        </div>
        <div class="requirements">
        <p> Beschikbare plaatsen : ' . htmlspecialchars($project["avaliblePlaces"]) . '</p>
        </div>
       <div class="requirements">
        <ul>';
        foreach ($requirements as $requirement) {
            echo '<li>' . htmlspecialchars($requirement["requirement"]) . '</li>';
        }
        echo "</ul>
        </div>";
    } else {
        echo "<script> window.location = 'myprojects.php'; </script>";
    }

    $stmt = $conn->prepare("SELECT username FROM users WHERE userID = :userID");
    $stmt->bindparam(':userID', $_SESSION["id"]);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <br>

    <form method="post">
        <div class="requirements">
            <div class="form-group">
                <label for="Team_name"> Team Naam</label>
                <input type="text" name="Team_name" id="Team_name" value="<?php echo htmlspecialchars($user["username"]); ?> required" class="input-inschrijven">
            </div>
            <div class="form-group">
                <label for="motief"> Motief voor Opdracht</label>
                <input type="text" name="motief" id="motief" required class="input-inschrijven">
            </div>
        </div>
        <input type="hidden" name="projectID" value="<?php echo htmlspecialchars($_GET["projectID"]) ?>">
        <input type="submit" name="submit" value="Inschrijven" id="inschrijf-knop">
    </form>

    </div>
    </div>

    <?php
    if (isset($_POST["submit"])) {
        $projectID = $_POST['projectID'];
        $motive = $_POST['motief'];
        $userID = $_SESSION['id'];
        $avaliblePlaces = $project["avaliblePlaces"] - 1;

        $stmt = $conn->prepare("INSERT INTO registration (userID, motive, projectID) VALUES (:userID, :motive, :projectID)");
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':motive', $motive);
        $stmt->bindParam(':projectID', $projectID);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE projects SET  avaliblePlaces = :Beschikbare_plaatsen WHERE projectID = :projectID");
        $stmt->bindParam(':Beschikbare_plaatsen', $avaliblePlaces);
        $stmt->bindParam(':projectID', $projectID);
        $stmt->execute();

        echo '<script>
        Swal.fire({
        icon: "success",
        title: "Gelukt!",
        text: "Je bent ingeschreven.",
        }).then((result) => {
        window.location = "myprojects"});
        </script>';
    }



    include("Includes/footer.php");
    ?>
</body>

</html>