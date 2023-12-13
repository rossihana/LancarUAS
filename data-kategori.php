<?php
session_start();
include 'db.php';

class DataKategori
{
    private $conn;

    // Construct
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Memeriksa status login. Jika tidak login, redirect ke halaman login.
    public function checkLoginStatus()
    {
        if ($_SESSION['status_login'] != true) {
            echo '<script>window.location="login.php"</script>';
        }
    }

    // Menampilkan bagian header HTML.
    public function renderHeader()
    {
        echo '
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
        </header>';
    }

    // Menampilkan tabel kategori beserta fungsionalitas tambah, edit, dan hapus.
    public function renderCategoryTable()
    {
        echo '
        <div class="section">
            <div class="container">
                <h3>Data Kategori</h3>
                <div class="box">
                    <p><a href="tambah-kategori.php">Tambah Data</a></p>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Kategori</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        $no = 1;
        $kategori = mysqli_query($this->conn, "SELECT * FROM tb_category ORDER BY category_id DESC");

        if (mysqli_num_rows($kategori) > 0) {
            while ($row = mysqli_fetch_array($kategori)) {
                echo '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $row['category_name'] . '</td>
                    <td>
                        <a href="edit-kategori.php?id=' . $row['category_id'] . '">Edit</a> || 
                        <a href="proses-hapus.php?idk=' . $row['category_id'] . '" onclick="return confirm(\'Yakin ingin hapus ?\')">Hapus</a>
                    </td>
                </tr>';
            }
        } else {
            echo '
            <tr>
                <td colspan="3">Tidak ada data</td>
            </tr>';
        }

        echo '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';
    }

    // Menampilkan bagian footer HTML.
    public function renderFooter()
    {
        echo '
        <footer>
            <div class="container">
                <small>Copyright &copy; 2023 - Buatan Akhyar dan Ossi.</small>
            </div>
        </footer>';
    }
}

$warungBerkahUAS = new DataKategori($conn);
$warungBerkahUAS->checkLoginStatus();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warung Berkah UAS</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    $warungBerkahUAS->renderHeader();
    $warungBerkahUAS->renderCategoryTable();
    $warungBerkahUAS->renderFooter();
    ?>
</body>

</html>
