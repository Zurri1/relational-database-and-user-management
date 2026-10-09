<?php
include 'auth.php';
include 'config.php';

require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id && $id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit;
?>