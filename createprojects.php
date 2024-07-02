<?php
include("Includes/head.php");
if (!isset($_SESSION["rol"])) {
    echo "<script> window.location = 'index.php'; </script>";
}
if ($_SESSION["rol"] == "Opdrachtnemer" || $_SESSION["rol"] == "Admin") {
    echo "<script> window.location = 'myprojects.php'; </script>";
}
?>
<script>
    function addRequirement() {
        var requirementsDiv = document.getElementById("requirements");
        var newRequirement = document.createElement("div");
        newRequirement.setAttribute("class", "form-group");
        newRequirement.innerHTML = '<input type="text" name="requirements[]" required /> <button type="button" onclick="removeRequirement(this)">Verwijder</button>';
        requirementsDiv.appendChild(newRequirement);
    }

    function removeRequirement(button) {
        var requirementsDiv = document.getElementById("requirements");
        requirementsDiv.removeChild(button.parentNode);
    }
</script>

<body>
    <?php
    include("Includes/nav.php");
    ?>
    <?php
    $stmt = $conn->prepare("SELECT username FROM users WHERE userID = :userID");
    $stmt->bindparam(':userID', $_SESSION["id"]);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
<div class="fromcreate-container">
    <form action="" method="post" class="fromcreate">
        <h1>Opdracht aanmaken</h1>
        <div class="form-group">
            <label for="project_name"> Project Naam</label>
            <input type="text" name="project_name" id="project_name" required>
        </div>
        <div class="form-group">
            <label for="name_project_owner"> Naam Opdrachtgever</label>
            <input type="text" name="name_project_owner" id="name_project_owner" value="<?php echo htmlspecialchars($user["username"]); ?>" required>
        </div>
        <div class="form-group">
            <label for="omschrijving_project"> Omschrijving Project</label>
            <input type="text" name="omschrijving_project" id="omschrijving_project" required>
        </div>

        <div id="requirements">
            <div class="form-group">
                <label for="requirements[]">Requirements</label>
            </div>
            <div class="form-group">
                <input type="text" name="requirements[]" required>
                <button type="button" onclick="removeRequirement(this)">Verwijder</button>
            </div>
            <div class="form-group">
                <input type="text" name="requirements[]" required>
                <button type="button" onclick="removeRequirement(this)">Verwijder</button>
            </div>
        </div>
        <button type="button" onclick="addRequirement()">Voeg Requirement Toe</button>

        <div class="form-group">
            <label for="Beschikbare_plaatsen">Beschikbare Plaatsen</label>
            <input type="number" name="Beschikbare_plaatsen" id="Beschikbare_plaatsen" required>
        </div>

        <input type="submit" name="submit" value="aanmaken">
    </form>
</div>


    <?php



    try {
        if (isset($_POST['submit'])) {
            $projectNaam = $_POST['project_name'];
            $nameProductOwner = $_POST['name_project_owner'];
            $summary = $_POST['omschrijving_project'];
            $places = $_POST['Beschikbare_plaatsen'];
            $userID = $_SESSION['id'];

            $stmt = $conn->prepare("INSERT INTO projects (projectname, nameprojectOwner, summary, avaliblePlaces, userID) VALUES (:projectname, :nameprojectOwner, :summary , :avaliblePlaces, :userID)");
            $stmt->bindParam(':projectname', $projectNaam);
            $stmt->bindParam(':nameprojectOwner', $nameProductOwner);
            $stmt->bindParam(':summary', $summary);
            $stmt->bindParam(':avaliblePlaces', $places);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();

            $projectID = $conn->lastInsertId();
            $stmt = $conn->prepare("INSERT INTO requirements (requirement, projectID) VALUES (:requirement, :projectID)");
            foreach ($_POST['requirements'] as $requirement) {
                $stmt->bindParam(':requirement', $requirement);
                $stmt->bindParam(':projectID', $projectID);
                $stmt->execute();
            }
            echo '<script>
             Swal.fire({
            icon: "success",
            title: "Gelukt!",
            text: "Opdracht is geplaatst.",
            }) 
            .then(function() {
                    window.location = "createprojects.php";
                });
            </script>';
        }
    } catch (Exception $e) {
        echo "";
    }



    include("Includes/footer.php");
    ?>
</body>

</html>