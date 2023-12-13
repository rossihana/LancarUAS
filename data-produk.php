<?php
session_start();
include 'db.php';

class DataProduk
{
    private $conn;

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

    // Menampilkan tabel produk beserta fungsi tambah, edit, dan hapus.
    public function renderProductTable()
    {
        echo '
        <div class="section">
            <div class="container">
                <h3>Data Produk</h3>
                <div class="box">
                    <p><a href="tambah-produk.php">Tambah Data</a></p>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Gambar</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        $no = 1;
        $produk = mysqli_query($this->conn, "SELECT * FROM tb_product LEFT JOIN tb_category USING (category_id) ORDER BY product_id DESC");

        if (mysqli_num_rows($produk) > 0) {
            while ($row = mysqli_fetch_array($produk)) {
                echo '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $row['category_name'] . '</td>
                    <td>' . $row['product_name'] . '</td>
                    <td>Rp. ' . number_format($row['product_price']) . '</td>
                    <td><a href="produk/' . $row['product_image'] . '" target="_blank"> <img src="produk/' . $row['product_image'] . '" width="50px"> </a></td>
                    <td>' . ($row['product_status'] == 0 ? 'Tidak Aktif' : 'Aktif') . '</td>
                    <td>
                        <a href="edit-produk.php?id=' . $row['product_id'] . '">Edit</a> || 
                        <a href="proses-hapus.php?idp=' . $row['product_id'] . '" onclick="return confirm(\'Yakin ingin hapus ?\')">Hapus</a>
                    </td>
                </tr>';
            }
        } else {
            echo '
            <tr>
                <td colspan="7">Tidak ada data</td>
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

$warungBerkahUAS = new DataProduk($conn);
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
    $warungBerkahUAS->renderProductTable();
    $warungBerkahUAS->renderFooter();
    ?>
</body>

</html>
