<?php
include("Includes/head.php");
if (!isset($_SESSION["rol"])) {
    echo "<script> window.location = 'index.php'; </script>";
}
if ($_SESSION["rol"] == "Opdrachtnemer") {
    echo "<script> window.location = 'projects.php'; </script>";
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
    <?php include("Includes/nav.php");
    if (isset($_GET["projectID"])) {
        $stmt = $conn->prepare("SELECT * FROM projects WHERE projectID = :projectID");
        $stmt->bindparam(':projectID', $_GET['projectID']);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT * FROM users WHERE userID = :userID");
        $stmt->bindparam(':userID', $_SESSION["id"]);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt1 = $conn->prepare("SELECT * FROM requirements WHERE projectID = :projectID");
        $stmt1->bindparam(':projectID', $_GET["projectID"]);
        $stmt1->execute();
        $requirements = $stmt1->fetchALL(PDO::FETCH_ASSOC);
    } else {
        echo "<script> window.location = 'myprojects.php'; </script>";
    }
    ?>
    <div class="fromcreate-container">
        <form action="" method="post" class="fromcreate">
            <h1>Opdracht aanmaken</h1>
            <div class="form-group">
                <label for="project_name"> Project Naam</label>
                <input type="text" name="project_name" id="project_name" value="<?php echo htmlspecialchars($project["projectname"]); ?>" required>
            </div>
            <div class="form-group">
                <label for="name_project_owner"> Naam Opdrachtgever</label>
                <input type="text" name="name_project_owner" id="name_project_owner" value="<?php echo htmlspecialchars($user["username"]); ?>" required>
            </div>
            <div class="form-group">
                <label for="omschrijving_project"> Omschrijving Project</label>
                <input type="text" name="omschrijving_project" id="omschrijving_project" value="<?php echo $project["summary"]; ?>" required>
            </div>

            <div id="requirements">
                <div class="form-group">
                    <label for="requirements[]">Requirements</label>
                </div>
                <?php
                $aantalRequirements = 0;
                foreach ($requirements as $requirement) {
                    echo '<div class="form-group">
                <input type="text" name="requirements[]" value="' . htmlspecialchars($requirement["requirement"]) . '" required>
                <button type="button" onclick="removeRequirement(this)">Verwijder</button>
            </div>';
                    $aantalRequirements++;
                }
                ?>
            </div>
            <button type="button" onclick="addRequirement()">Voeg Requirement Toe</button>

            <div class="form-group">
                <label for="Beschikbare_plaatsen">Beschikbare Plaatsen</label>
                <input type="number" name="Beschikbare_plaatsen" id="Beschikbare_plaatsen" value="<?php echo htmlspecialchars($project["avaliblePlaces"]); ?>">
            </div>

            <input type="submit" name="update" value="Update">
        </form>

        <?php

        if (isset($_POST['update'])) {
            $_SESSION["project_name"] = $_POST['project_name'];
            $_SESSION["name_project_owner"]  = $_POST['name_project_owner'];
            $_SESSION["omschrijving_project"] = $_POST['omschrijving_project'];
            $_SESSION["Beschikbare_plaatsen"] = $_POST['Beschikbare_plaatsen'];
            $_SESSION["requirements"] = $_POST["requirements"];

            echo '<script>
            Swal.fire({
                title: "Project aanpassen?",
                showDenyButton: true,
                confirmButtonText: "Aanpassen",
                denyButtonText: "annuleren"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("verzenden").submit();
                } else if (result.isDenied) {
                    document.getElementById("anuleren").submit();
                }
            });
        </script>';
        }

        ?>
        <form method="post" id="verzenden">
            <input type="hidden" name="verzenden" id="verzenden" value="<?php echo htmlspecialchars($project["projectID"]); ?>">
        </form>

        <form method="post" id="anuleren">
            <input type="hidden" name="anuleren" id="anuleren" value="<?php echo htmlspecialchars($project["projectID"]); ?>">
        </form>
    </div>
    <?php

    if (isset($_POST["verzenden"])) {
        $stmt = $conn->prepare("UPDATE projects SET projectname = :project_name, summary = :omschrijving_project, avaliblePlaces = :Beschikbare_plaatsen WHERE projectID = :projectID");
        $stmt->bindParam(':project_name', $_SESSION["project_name"]);
        $stmt->bindParam(':omschrijving_project', $_SESSION["omschrijving_project"]);
        $stmt->bindParam(':Beschikbare_plaatsen', $_SESSION["Beschikbare_plaatsen"]);
        $stmt->bindParam(':projectID', $_GET['projectID']);
        $stmt->execute();

        $stmt1 = $conn->prepare("SELECT requirementID FROM requirements WHERE projectID = :projectID");
        $stmt1->bindparam(':projectID', $_GET["projectID"]);
        $stmt1->execute();
        $requirements = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        $nieuweRequirements = 0;
        foreach ($_SESSION["requirements"] as $requirement) {
            if ($aantalRequirements > $nieuweRequirements) {
                // $requirementID = $requirements[$nieuweRequirements]["requirementID"];
                $stmt = $conn->prepare("UPDATE requirements SET requirement = :requirement WHERE requirementID = :requirementID");
                $stmt->bindParam(':requirement', $requirement);
                $stmt->bindParam(':requirementID', $requirements[$nieuweRequirements]["requirementID"]);
                $stmt->execute();
            } else {
                $stmt1 = $conn->prepare("INSERT INTO requirements (requirement, projectID) VALUES (:requirement, :projectID)");
                $stmt1->bindParam(':requirement', $requirement);
                $stmt1->bindParam(':projectID', $_GET["projectID"]);
                $stmt1->execute();
            }
            $nieuweRequirements++;
        }
        for ($i=$nieuweRequirements; $i < $aantalRequirements ; $i++) {
            $stmt1 = $conn->prepare("DELETE FROM requirements WHERE requirementID = :requirementID");
            $stmt1->bindparam(':requirementID', $requirements[$nieuweRequirements]["requirementID"]);
            $stmt1->execute();
        }
        unset($_SESSION["project_name"]);
        unset($_SESSION["name_project_owner"]);
        unset($_SESSION["omschrijving_project"]);
        unset($_SESSION["Beschikbare_plaatsen"]);
        unset($_SESSION["requirements"]);
        echo "<script> Swal.fire('Aangepast!', '', 'success').then((result) => {
         window.location = 'updateprojects.php?projectID=" . $project["projectID"] . "'}); </script>";
    }

    if (isset($_POST["anuleren"])) {
        echo '<script> Swal.fire("Changes are not saved", "", "info"); </script>';
        unset($_SESSION["project_name"]);
        unset($_SESSION["name_project_owner"]);
        unset($_SESSION["omschrijving_project"]);
        unset($_SESSION["Beschikbare_plaatsen"]);
        unset($_SESSION["requirements"]);
    }

    include("Includes/footer.php"); ?>

</body>

</html>