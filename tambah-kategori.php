<?php
session_start();
include 'db.php';

class TambahKategori
{
    private $conn;

    // Construct
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addCategory($name)
    {
        $name = ucwords($name);

        $insert = mysqli_query($this->conn, "INSERT INTO tb_category VALUES ( null,'" . $name . "') ");

        if ($insert) {
            echo '<script>alert("Tambah data berhasil")</script>';
            echo '<script>window.location="data-kategori.php"</script>';
        } else {
            echo 'gagal ' . mysqli_error($this->conn);
        }
    }
}

$categoryManager = new TambahKategori($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $categoryManager->addCategory($_POST['nama']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warung Berkah UAS</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">Warung Berkah UAS</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="data-kategori.php">Data Kategori</a></li>
                <li><a href="data-produk.php">Data Produk</a></li>
                <li><a href="keluar.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Tambah Data Kategori</h3>
            <div class="box">
                <form action="" method="POST">
                    <input type="text" name="nama" placeholder="Nama Kategori" class="input-control" required>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2023 - Buatan Akhyar dan Ossi.</small>
        </div>
    </footer>
</body>

</html>
