<?php
session_start();
include 'db.php';

class EditKategori
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Metode getter untuk mendapatkan nilai dari $conn
    public function getConn()
    {
        return $this->conn;
    }

    // Metode setter untuk mengatur nilai $conn
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    // Metode getter untuk mendapatkan nilai dari $_SESSION['status_login']
    public function getLoginStatus()
    {
        return $_SESSION['status_login'];
    }

    // Metode setter untuk mengatur nilai $_SESSION['status_login']
    public function setLoginStatus($status)
    {
        $_SESSION['status_login'] = $status;
    }

    public function checkLoginStatus()
    {
        // Gunakan metode getter
        if ($this->getLoginStatus() != true) {
            echo '<script>window.location="login.php"</script>';
        }
    }

    public function getCategoryData()
    {
        $kategoriId = $_GET['id'];
        $kategori = mysqli_query($this->conn, "SELECT * FROM tb_category WHERE category_id = '$kategoriId'");

        if (mysqli_num_rows($kategori) == 0) {
            echo '<script>window.location="data-kategori.php"</script>';
        }

        return mysqli_fetch_object($kategori);
    }

    public function editCategory()
    {
        if (isset($_POST['submit'])) {
            $nama = ucwords($_POST['nama']);
            $kategoriId = $this->getCategoryData()->category_id;

            $update = mysqli_query($this->conn, "UPDATE tb_category SET category_name = '$nama' WHERE category_id = '$kategoriId'");

            if ($update) {
                echo '<script>alert("Edit data berhasil")</script>';
                echo '<script>window.location="data-kategori.php"</script>';
            } else {
                echo 'gagal ' . mysqli_error($this->conn);
            }
        }
    }

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

    public function renderEditCategoryForm()
    {
        $kategoriData = $this->getCategoryData();

        echo '
        <div class="section">
            <div class="container">
                <h3>Edit Data Kategori</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="nama" placeholder="Nama Kategori" class="input-control" value="' . $kategoriData->category_name . '" required>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>';
        $this->editCategory();
        echo '
                </div>
            </div>
        </div>';
    }

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

$warungBerkahUAS = new EditKategori($conn);
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
    $warungBerkahUAS->renderEditCategoryForm();
    $warungBerkahUAS->renderFooter();
    ?>
</body>

</html>
