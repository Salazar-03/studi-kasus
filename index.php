<?php
include_once("koneksi.php");

// Ambil data untuk mengisi form ketika mengklik "Ubah"
$isi = '';
$tgl_awal = '';
$tgl_akhir = '';
$id = ''; // Menyimpan ID yang digunakan

if (isset($_GET['id'])) {
    $ambil = mysqli_query($koneksi, "SELECT * FROM tabelkegiatan WHERE id='" . $_GET['id'] . "'");
    if ($row = mysqli_fetch_array($ambil)) {
        $isi = $row['isi'];
        $tgl_awal = $row['tgl_awal'];
        $tgl_akhir = $row['tgl_akhir'];
        $id = $row['id']; // Ambil ID untuk form
    } else {
        echo "<script>alert('Data tidak ditemukan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>To Do List</title>
</head>
<body>

<div class="container">
    <h3>To Do List
        <small class="text-muted">Catat semua hal yang akan kamu kerjakan disini.</small>
    </h3>
    <hr>

    <!-- Form Input Data -->
    <form class="row" method="POST" action="">
        <div class="col-md-3">
            <label for="inputIsi" class="form-label fw-bold">Kegiatan</label>
            <input type="text" class="form-control" name="isi" id="inputIsi" placeholder="Kegiatan" value="<?php echo isset($isi) ? $isi : ''; ?>" required>
        </div>
        <div class="col-md-3">
            <label for="inputTanggalAwal" class="form-label fw-bold">Tanggal Awal</label>
            <input type="date" class="form-control" name="tgl_awal" id="inputTanggalAwal" value="<?php echo isset($tgl_awal) ? $tgl_awal : ''; ?>" required>
        </div>
        <div class="col-md-3">
            <label for="inputTanggalAkhir" class="form-label fw-bold">Tanggal Akhir</label>
            <input type="date" class="form-control" name="tgl_akhir" id="inputTanggalAkhir" value="<?php echo isset($tgl_akhir) ? $tgl_akhir : ''; ?>" required>
        </div>

        <!-- Tombol simpan berada di samping Tanggal Akhir -->
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4" name="simpan">Simpan</button>
            <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Input hidden untuk ID -->
        </div>
    </form> 

    

    <!-- Table -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kegiatan</th>
                <th scope="col">Awal</th>
                <th scope="col">Akhir</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM tabelkegiatan ORDER BY id");
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <th scope="row"><?php echo $no++ ?></th>
                <td><?php echo $data['isi'] ?></td>
                <td><?php echo $data['tgl_awal'] ?></td>
                <td><?php echo $data['tgl_akhir'] ?></td>
                <td>
                <?php
                if ($data['status'] == '1') {
                ?>
                    <a class="btn btn-success rounded-pill px-3" type="button"
                       href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=0">Sudah</a>
                <?php
                } else {
                ?>
                    <a class="btn btn-warning rounded-pill px-3" type="button"
                       href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=1">Belum</a>
                <?php
                }
                ?>
                </td>
                <td>
                    <a class="btn btn-info rounded-pill px-3" href="index.php?id=<?php echo $data['id'] ?>">Ubah</a>
                    <a class="btn btn-danger rounded-pill px-3" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>

    <?php
    if (isset($_POST['simpan'])) {
        // Jika ada ID, kita melakukan update, jika tidak berarti insert
        if (!empty($_POST['id'])) {
            $ubah = mysqli_query($koneksi, "UPDATE tabelkegiatan SET
                                            isi = '" . $_POST['isi'] . "',
                                            tgl_awal = '" . $_POST['tgl_awal'] . "',
                                            tgl_akhir = '" . $_POST['tgl_akhir'] . "'
                                            WHERE id = '" . $_POST['id'] . "'");
        } else {
            $tambah = mysqli_query($koneksi, "INSERT INTO tabelkegiatan(isi,tgl_awal,tgl_akhir,status)
                                            VALUES (
                                                '" . $_POST['isi'] . "',
                                                '" . $_POST['tgl_awal'] . "',
                                                '" . $_POST['tgl_akhir'] . "',
                                                'Belum')");
        }
        echo "<script>document.location='index.php';</script>";
    }

    if (isset($_GET['aksi'])) {
        if ($_GET['aksi'] == 'hapus') {
            $hapus = mysqli_query($koneksi, "DELETE FROM tabelkegiatan WHERE id = '" . $_GET['id'] . "'");
        } else if ($_GET['aksi'] == 'ubah_status') {
            if ($_GET['status'] == 0) {
                $ubah_status = mysqli_query($koneksi, "UPDATE tabelkegiatan SET status = 0 WHERE id = '" . $_GET['id'] . "'");
            } else {
                $ubah_status = mysqli_query($koneksi, "UPDATE tabelkegiatan SET status = 1 WHERE id = '" . $_GET['id'] . "'");
            }
        }
        echo "<script>document.location='index.php';</script>";
    }
    ?>
</div>

</body>
</html>
