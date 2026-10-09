<?php
include 'auth.php';
include 'config.php';


$query = "SELECT 
            students.id,
            students.nim,
            students.name,
            majors.name AS major_name
          FROM students
          JOIN majors ON students.major_id = majors.id
          ORDER BY students.id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Student Management</h4>

            <a href="create.php" class="btn btn-success btn-sm">
                + Add Student
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Name</th>
                            <th>Major</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($row['nim']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['major_name']) ?>
                                </td>

                                <td class="text-center">
                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?= $row['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this student?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>