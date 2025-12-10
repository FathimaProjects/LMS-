<?php
    session_start();

    include 'Include PHP/database_connection.php';

    if (isset($_SESSION['email'])) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/home.css"> 

    <title>FACULTY MANAGEMENT SYSTEM</title>
   
</head>
<body>

<div class="container">
    <h2 class="text-center page-heading">FACULTY MANAGEMENT SYSTEM</h2>



    <div class="row justify-content-between mb-3">
        <div class="col-auto">
            <a href="student_registration/registration.php" class="btn btn-primary">Register Student</a>
        </div>
        <div class="col-auto">
            <form class="form-inline" onsubmit="return performSearch();">
                <input id="search" class="form-control form-control-lg mr-sm-2" type="text" placeholder="Search" name="search">
                <button type="submit" class="btn btn-outline-success"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="col-auto">
            <a href="index.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <?php

        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // If there is a search parameter, execute the search query using prepared statement
        if (!empty($search)) {
            $stmt = $conn->prepare("SELECT * FROM student WHERE first_name LIKE ? OR last_name LIKE ? OR reg_no LIKE ? OR index_no LIKE ? OR gender LIKE ? OR batch LIKE ? OR department LIKE ? OR email_address LIKE ? OR phone_number LIKE ?");
            $param = "%$search%";
            $stmt->bind_param("sssssssss", $param, $param, $param, $param, $param, $param, $param, $param, $param);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            // If there is no search parameter, display all students
            $result = $conn->query("SELECT * FROM student");
        }

        while ($row = $result->fetch_assoc()): ?>
            <div class="bubble-row">
                <div><img class="profile-pic" src="profile/<?= htmlspecialchars($row['IMAGE']) ?>" alt="Profile Pic"></div>
                <div><?= htmlspecialchars($row['FIRSTNAME']) ?></div>
                <div><?= htmlspecialchars($row['LASTNAME']) ?></div>
                <div><?= htmlspecialchars($row['REGNO']) ?></div>
                <div><?= htmlspecialchars($row['INDEXNO']) ?></div>
                <div><?= htmlspecialchars($row['GENDER']) ?></div>
                <div><?= htmlspecialchars($row['BATCH']) ?></div>
                <div><?= htmlspecialchars($row['DEPARTMENT']) ?></div>
                <div><?= htmlspecialchars($row['EMAIL']) ?></div>
                <div><?= htmlspecialchars($row['PHONENUMBER']) ?></div>
            
            </div>
    <?php endwhile;
        $conn->close();
    ?>
</div>

<script>
    function performSearch() {
        const searchInput = document.getElementById("search");
        const searchTerm = searchInput.value.trim().toLowerCase();

        document.querySelectorAll(".bubble-row").forEach(function (row) {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) || searchTerm === '' ? "flex" : "none";
        });

        return false;
    }
</script>

</body>
</html>
<?php
    } else {
        header("Location: index.php?error=Please Login to Access");
        exit();
    }
?>