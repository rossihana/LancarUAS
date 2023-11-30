<?php
error_reporting(0);
include 'db.php';

class WarungBerkahUAS
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getContactInfo($adminId)
    {
        $kontak = mysqli_query($this->conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = $adminId");
        return mysqli_fetch_object($kontak);
    }

    public function getProductById($productId)
    {
        $produk = mysqli_query($this->conn, "SELECT * FROM tb_product WHERE product_id = '$productId'");
        return mysqli_fetch_object($produk);
    }
}

$warungBerkah = new WarungBerkahUAS($conn);

$adminId = 1;
$contactInfo = $warungBerkah->getContactInfo($adminId);

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
            <form action="produk.php">
                <input type="text" name="search" placeholder="Cari Produk" value="<?php echo $_GET['search'] ?>">
                <input type="hidden" name="kat" value="<?php echo $_GET['kat'] ?>">
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
                            Hubungin via Whatsapp
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
            <small>Copyright &copy; 2023 - Buatan Akhyar dan Ossi.</small>
        </div>
    </div>
</body>

</html>
