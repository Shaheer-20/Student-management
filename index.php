<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the semester filter value from the URL if set
$semester_filter = isset($_GET['semester']) ? $_GET['semester'] : '';

// Build the SQL query to fetch students based on the selected semester filter
$sql = "SELECT * FROM students";
if (!empty($semester_filter)) {
    $sql .= " WHERE semester = ?";
}

$stmt = $conn->prepare($sql);

// Bind parameter if filtering by semester
if (!empty($semester_filter)) {
    $stmt->bind_param("i", $semester_filter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Student Management</a>
            <div class="navbar-nav ml-auto">
                <a class="nav-link" href="index.php">Home</a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarSemDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter by Semester
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarSemDropdown">
                        <li><a class="dropdown-item" href="index.php?semester=1">Semester 1</a></li>
                        <li><a class="dropdown-item" href="index.php?semester=2">Semester 2</a></li>
                        <li><a class="dropdown-item" href="index.php?semester=3">Semester 3</a></li>
                        <li><a class="dropdown-item" href="index.php?semester=4">Semester 4</a></li>
                    </ul>
                </div>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Student Table -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Student List</h4>
            </div>
            <div class="card-body">
                <!-- Table to display student data -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>USN</th>
                            <th>Name</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($student['usn']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['semester']) ?></td>
                                <td><?= htmlspecialchars($student['course']) ?></td>
                                <td>
                                    <a href="edit_student.php?usn=<?= htmlspecialchars($student['usn']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_student.php?usn=<?= htmlspecialchars($student['usn']) ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional: Add New Student Button -->
    <div class="container mt-3">
        <a href="add_student.php" class="btn btn-success">Add New Student</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the connection
$conn->close();
?>
