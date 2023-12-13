<?php

include 'db.php';

class Hapus
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->deleteCategory();
        $this->deleteProduct();
    }

    public function deleteCategory()
    {
        if (isset($_GET['idk'])) {
            $delete = mysqli_query($this->conn, "DELETE FROM tb_category WHERE category_id = '" . $_GET['idk'] . "' ");
            echo '<script>window.location="data-kategori.php"</script>';
        }
    }

    public function deleteProduct()
    {
        if (isset($_GET['idp'])) {
            $produk = mysqli_query($this->conn, "SELECT product_image FROM tb_product WHERE product_id = '" . $_GET['idp'] . "' ");
            $p = mysqli_fetch_object($produk);

            unlink('./produk/' . $p->product_image);

            $delete = mysqli_query($this->conn, "DELETE FROM tb_product WHERE product_id = '" . $_GET['idp'] . "' ");
            echo '<script>window.location="data-produk.php"</script>';
        }
    }
}

$dataDeletion = new Hapus($conn);

?>
