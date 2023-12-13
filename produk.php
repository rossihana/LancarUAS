<?php
// Menghilangkan pesan error untuk production
error_reporting(0);

// Menggunakan file koneksi database
include 'db.php';

// Kelas dasar untuk manajemen warung
class Produk
{
    protected $conn;

    // Constructor untuk mengatur koneksi
    public function __construct($conn)
    {
        $this->setConn($conn);
    }

    // Getter untuk mendapatkan koneksi
    public function getConn()
    {
        return $this->conn;
    }

    // Setter untuk mengatur koneksi
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    // Mendapatkan informasi kontak admin berdasarkan adminId
    public function getContactInfo($adminId)
    {
        // Melakukan query untuk mendapatkan informasi kontak admin
        $kontak = mysqli_query($this->getConn(), "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = $adminId");

        // Mengembalikan hasil query dalam bentuk objek
        return mysqli_fetch_object($kontak);
    }
}

// Kelas yang mewarisi dari WarungBase, khusus untuk manajemen produk
class WarungBerkahUAS extends Produk
{
    // Mendapatkan produk berdasarkan pencarian dan kategori
    public function getProducts($search, $category)
    {
        $where = "";
        if ($search != '' || $category != '') {
            // Membuat kondisi WHERE berdasarkan pencarian dan kategori
            $where = "AND product_name LIKE '%$search%' AND category_id LIKE '%$category%'";
        }

        // Melakukan query untuk mendapatkan produk dengan status 1 (aktif) dan kondisi tambahan
        $produk = mysqli_query($this->getConn(), "SELECT * FROM tb_product WHERE product_status = 1 $where ORDER BY product_id DESC");

        // Mengembalikan hasil query
        return $produk;
    }
}

// Instansiasi objek WarungBerkahUAS dengan menggunakan koneksi dari file db.php
$warungBerkah = new WarungBerkahUAS($conn);

// Mendapatkan informasi kontak admin berdasarkan adminId
$adminId = 1;
$contactInfo = $warungBerkah->getContactInfo($adminId);

// Mendapatkan nilai pencarian (search) dan kategori (kat) dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['kat']) ? $_GET['kat'] : '';

// Mendapatkan daftar produk berdasarkan pencarian dan kategori
$products = $warungBerkah->getProducts($search, $category);
?>

<!-- HTML dan CSS untuk tampilan web -->
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
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">Warung Berkah UAS</a></h1>
            <a class="product-btn" href="produk.php">Produk</a>
        </div>
    </header>

    <!-- search -->
    <div class="search">
        <div class="container">
            <form action="produk.php">
                <input type="text" name="search" placeholder="Cari Produk" value="<?php echo $search ?>">
                <input type="hidden" name="kat" value="<?php echo $category ?>">
                <input type="submit" name="cari" value="Cari Produk">
            </form>
        </div>
    </div>

    <!-- Produk -->
    <div class="section">
        <div class="container">
            <h3>Produk</h3>
            <div class="box">
                <?php
                // Menampilkan daftar produk
                if (mysqli_num_rows($products) > 0) {
                    while ($product = mysqli_fetch_array($products)) {
                        ?>
                        <a href="detail-produk.php?id=<?php echo $product['product_id'] ?>">
                            <div class="col-4">
                                <img src="produk/<?php echo $product['product_image'] ?>">
                                <p class="nama"><?php echo substr($product['product_name'], 0, 30) ?></p>
                                <p class="harga">Rp. <?php echo number_format($product['product_price']) ?></p>
                            </div>
                        </a>
                    <?php }
                } else {
                    ?>
                    <p>Produk tidak ada</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div class="footer">
        <div class="container">
            <h4>Alamat</h4>
            <p><?php echo $contactInfo->admin_address ?></p>

            <h4>Email</h4>
            <p><?php echo $contactInfo->admin_email ?></p>

            <h4>No. Hp</h4>
            <p><?php echo $contactInfo->admin_telp ?></p>
            <small>Copyright &copy; 2023 - Buatan Akhyar dan Ossi.</small>
        </div>
    </div>
</body>

</html>
