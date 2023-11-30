<?php

include 'db.php';

class WarungBase
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getContactInfo($adminId)
    {
        $kontak = mysqli_query($this->conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = $adminId");
        return mysqli_fetch_object($kontak);
    }

    public function getCategories()
    {
        $kategori = mysqli_query($this->conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
        return $kategori;
    }

    public function getProducts()
    {
        $produk = mysqli_query($this->conn, "SELECT * FROM tb_product WHERE product_status = 1 ORDER BY product_id DESC LIMIT 8");
        return $produk;
    }
}

class WarungBerkahUAS extends WarungBase
{
    // Additional methods specific to WarungBerkahUAS can be added here.

}

$warungBerkah = new WarungBerkahUAS($conn);

$adminId = 1;
$contactInfo = $warungBerkah->getContactInfo($adminId);

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
                <input type="text" name="search" placeholder="Cari Produk">
                <input type="submit" name="cari" value="Cari Produk">
            </form>
        </div>
    </div>

    <!-- category -->
    <div class="section">
        <div class="container">
            <h3>Kategori</h3>
            <div class="box">
                <?php
                $categories = $warungBerkah->getCategories();
                if (mysqli_num_rows($categories) > 0) {
                    while ($category = mysqli_fetch_array($categories)) {
                        ?>
                        <a href="produk.php?kat=<?php echo $category['category_id'] ?>">
                            <div class="col-5">
                                <img src="img/icon-kategori.png" width="50px" style="margin-bottom:5px;">
                                <p><?php echo $category['category_name'] ?></p>
                            </div>
                        </a>
                        <?php
                    }
                } else {
                    ?>
                    <p>Kategori tidak ada</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- new product -->
    <div class="section">
        <div class="container">
            <h3>Produk Terbaru</h3>
            <div class="box">
                <?php
                $products = $warungBerkah->getProducts();
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
                        <?php
                    }
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
