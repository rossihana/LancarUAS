<?php
include 'db.php';

class DetailProduk
{
    protected $conn;

    // Construct
    public function __construct($conn)
    {
        $this->setConn($conn);
    }

    // Getter untuk $conn
    public function getConn()
    {
        return $this->conn;
    }

    // Setter untuk $conn
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    public function getContactInfo($adminId)
    {
        // Menghindari SQL injection dengan escape parameter
        $adminId = mysqli_real_escape_string($this->getConn(), $adminId);
        
        // Query untuk mendapatkan informasi kontak admin
        $kontak = mysqli_query($this->getConn(), "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = '$adminId'");
        
        // Mengembalikan hasil query dalam bentuk objek
        return mysqli_fetch_object($kontak);
    }

    // Metode yang akan di-overriding
    public function getProductById($productId)
    {
        // Menghindari SQL injection dengan escape parameter
        $productId = mysqli_real_escape_string($this->getConn(), $productId);
        
        // Query untuk mendapatkan informasi produk berdasarkan ID
        $produk = mysqli_query($this->getConn(), "SELECT * FROM tb_product WHERE product_id = '$productId'");
        
        // Mengembalikan hasil query dalam bentuk objek
        return mysqli_fetch_object($produk);
    }
}

// Pewarisan
class WarungBerkahUAS extends DetailProduk
{
    // Metode overriding
    public function getProductById($productId)
    {
        // Implementasi khusus untuk WarungBerkahUAS
        // Menambahkan kondisi WHERE untuk product_status
        $productId = mysqli_real_escape_string($this->getConn(), $productId);
        $produk = mysqli_query($this->getConn(), "SELECT * FROM tb_product WHERE product_id = '$productId' AND product_status = 1");
        
        // Mengembalikan hasil query dalam bentuk objek
        return mysqli_fetch_object($produk);
    }
}

// Instansiasi objek WarungBerkahUAS
$warungBerkah = new WarungBerkahUAS($conn);

// Mendapatkan informasi admin
$adminId = 1;
$contactInfo = $warungBerkah->getContactInfo($adminId);

// Mendapatkan informasi produk berdasarkan ID
$productId = isset($_GET['id']) ? $_GET['id'] : '';
$product = $warungBerkah->getProductById($productId);
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
            <form action="produk.php" method="GET">
                <input type="text" name="search" placeholder="Cari Produk" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <input type="hidden" name="kat" value="<?php echo isset($_GET['kat']) ? $_GET['kat'] : ''; ?>">
                <input type="submit" name="cari" value="Cari Produk">
            </form>
        </div>
    </div>

    <!-- product detail -->
    <div class="section">
        <div class="container">
            <h3>Detail Produk</h3>
            <div class="box">
                <div class="col-2">
                    <img src="produk/<?php echo $product->product_image ?>" width="100%">
                </div>
                <div class="col-2">
                    <h3><?php echo $product->product_name ?></h3>
                    <h4>Rp. <?php echo number_format($product->product_price) ?></h4>
                    <p>Deskripsi :<br>
                        <?php echo $product->product_description ?>
                    </p>
                    <p><a href="https://api.whatsapp.com/send?phone=<?php echo $contactInfo->admin_telp ?>&text=Hai, saya tertarik dengan produk Anda." target="_blank">
                            Hubungi via Whatsapp
                            <img src="img/wa.png" width="50px"></a>
                    </p>
                </div>
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

            <h4>Admin Login</h4>
            <p><a class="admin-btn" href="login.php">Login</a></p>

            <small>Copyright &copy; 2023 - Buatan Akhyar dan Ossi.</small>
        </div>
    </div>
</body>

</html>