<?php
include 'config.php';

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Laporan_Mahasiswa.xls");
header("Pragma: no-cache");
header("Expires: 0");

$result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
?>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jurusan</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="mso-number-format:'\@';"><?= htmlspecialchars($row['nim'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['major'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>