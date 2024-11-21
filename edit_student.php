<?php
require_once 'db_connection.php';

// Check if USN is provided
if (!isset($_GET['usn'])) {
    header("Location: index.php");
    exit();
}

$usn = $_GET['usn'];

// Fetch student details
$sql = "SELECT * FROM students WHERE usn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usn);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $semester = $_POST['semester'];
    $course = $_POST['course'];

    $sql = "UPDATE students SET name = ?, semester = ?, course = ? WHERE usn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $name, $semester, $course, $usn);

    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit();
    } else {
        $error = "Error updating student: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Edit Student</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="usn" class="form-label">USN (Unchangeable)</label>
                        <input type="text" class="form-control" id="usn" name="usn" value="<?= htmlspecialchars($student['usn']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="1" <?= $student['semester'] == 1 ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= $student['semester'] == 2 ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= $student['semester'] == 3 ? 'selected' : '' ?>>3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="course" class="form-label">Course</label>
                        <input type="text" class="form-control" id="course" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>