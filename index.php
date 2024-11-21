<?php
require_once 'db_connection.php';

// Fetch students based on semester
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 0;

if ($semester > 0) {
    $sql = "SELECT * FROM students WHERE semester = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $semester);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM students";
    $result = $conn->query($sql);
}

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
        }
        .student-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            overflow: hidden;
        }
        .student-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .student-card-header {
            background: linear-gradient(to right, #2c3e50, #34495e);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        .student-actions {
            display: flex;
            gap: 10px;
        }
        .card-body {
            background: white;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-white">Student Management Dashboard</h1>
                    <a href="add_student.php" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Add New Student
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Student List</h3>
                        <div class="btn-group" role="group">
                            <a href="index.php" class="btn btn-outline-light">All</a>
                            <a href="index.php?semester=1" class="btn btn-outline-light">Semester 1</a>
                            <a href="index.php?semester=2" class="btn btn-outline-light">Semester 2</a>
                            <a href="index.php?semester=3" class="btn btn-outline-light">Semester 3</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($result->num_rows > 0): ?>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                <?php while ($student = $result->fetch_assoc()): ?>
                                    <div class="col">
                                        <div class="card student-card">
                                            <div class="student-card-header">
                                                <h5 class="mb-0"><?= htmlspecialchars($student['name']) ?></h5>
                                                <div class="student-actions">
                                                    <a href="edit_student.php?usn=<?= urlencode($student['usn']) ?>" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="delete_student.php?usn=<?= urlencode($student['usn']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($student['name']) ?>&background=0D8ABC&color=fff" alt="Student Avatar" class="student-avatar me-3">
                                                    <div>
                                                        <p class="mb-1"><strong>USN:</strong> <?= htmlspecialchars($student['usn']) ?></p>
                                                        <p class="mb-1"><strong>Semester:</strong> <?= htmlspecialchars($student['semester']) ?></p>
                                                    </div>
                                                </div>
                                                <p class="card-text"><strong>Course:</strong> <?= htmlspecialchars($student['course']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                No students found in this semester.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>