<?php
session_start();
include 'db.php';

class WarungBerkahUAS
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkLoginStatus()
    {
        if ($_SESSION['status_login'] != true) {
            echo '<script>window.location="login.php"</script>';
        }
    }

    public function getProductData()
    {
        $productId = $_GET['id'];
        $produk = mysqli_query($this->conn, "SELECT * FROM tb_product WHERE product_id = '$productId'");

        if (mysqli_num_rows($produk) == 0) {
            echo '<script>window.location="data-produk.php"</script>';
        }

        return mysqli_fetch_object($produk);
    }

    public function editProduct()
    {
        if (isset($_POST['submit'])) {
            $kategori = $_POST['kategori'];
            $nama = $_POST['nama'];
            $harga = $_POST['harga'];
            $deskripsi = $_POST['deskripsi'];
            $status = $_POST['status'];
            $foto = $_POST['foto'];

            $filename = $_FILES['gambar']['name'];
            $tmp_name = $_FILES['gambar']['tmp_name'];

            if ($filename != '') {
                $type1 = explode('.', $filename);
                $type2 = $type1[1];

                $newname = 'produk' . time() . '.' . $type2;

                $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

                if (!in_array($type2, $tipe_diizinkan)) {
                    echo '<script>alert("Format file tidak diizinkan")</scrtip>';
                } else {
                    unlink('./produk/' . $foto);
                    move_uploaded_file($tmp_name, './produk/' . $newname);
                    $namagambar = $newname;
                }
            } else {
                $namagambar = $foto;
            }

            $update = mysqli_query($this->conn, "UPDATE tb_product SET 
                                    category_id = '$kategori',
                                    product_name = '$nama',
                                    product_price = '$harga',
                                    product_description = '$deskripsi',
                                    product_image = '$namagambar',
                                    product_status = '$status'
                                    WHERE product_id = '" . $this->getProductData()->product_id . "'	");

            if ($update) {
                echo '<script>alert("Ubah data berhasil")</script>';
                echo '<script>window.location="data-produk.php"</script>';
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

    public function renderEditProductForm()
    {
        $productData = $this->getProductData();

        echo '
        <div class="section">
            <div class="container">
                <h3>Edit Data Produk</h3>
                <div class="box">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <select class="input-control" name="kategori" required>
                            <option value="">--Pilih--</option>';

        $kategori = mysqli_query($this->conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
        while ($r = mysqli_fetch_array($kategori)) {
            echo '<option value="' . $r['category_id'] . '" ' . ($r['category_id'] == $productData->category_id ? 'selected' : '') . '>' . $r['category_name'] . '</option>';
        }

        echo '
                        </select>
                        <input type="text" name="nama" class="input-control" placeholder="Nama Produk" value="' . $productData->product_name . '" required>
                        <input type="text" name="harga" class="input-control" placeholder="Harga" value="' . $productData->product_price . '" required>
                        
                        <img src="produk/' . $productData->product_image . '" width="100px">
                        <input type="hidden" name="foto" value="' . $productData->product_image . '">
                        <input type="file" name="gambar" class="input-control">
                        <textarea class="input-control" name="deskripsi" placeholder="Deskripsi">' . $productData->product_description . '</textarea><br>
                        <select class="input-control" name="status">
                            <option value="">--Pilih--</option>
                            <option value="1" ' . ($productData->product_status == 1 ? 'selected' : '') . '>Aktif</option>
                            <option value="0" ' . ($productData->product_status == 0 ? 'selected' : '') . '>Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>';

        $this->editProduct();

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

$warungBerkahUAS = new WarungBerkahUAS($conn);
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
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
</head>

<body>
    <?php
    $warungBerkahUAS->renderHeader();
    $warungBerkahUAS->renderEditProductForm();
    $warungBerkahUAS->renderFooter();
    ?>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>
</body>

</html>
