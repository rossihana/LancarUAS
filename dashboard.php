<?php
session_start();

class Dashboard
{
    private $isAdminLoggedIn;

    public function __construct()
    {
        $this->isAdminLoggedIn = isset($_SESSION['status_login']) && $_SESSION['status_login'] == true;
    }

    public function redirectToLogin()
    {
        if (!$this->isAdminLoggedIn) {
            echo '<script>window.location="login.php"</script>';
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

    public function renderContent()
    {
        echo '
        <div class="section">
            <div class="container">
                <h3>Dashboard</h3>
                <div class="box">
                    <h4>Selamat Datang ' . $_SESSION['a_global']->admin_name . ' di Pengeditan Toko Online</h4>
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

$warungBerkahUAS = new Dashboard();
$warungBerkahUAS->redirectToLogin();

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
    $warungBerkahUAS->renderContent();
    $warungBerkahUAS->renderFooter();
    ?>
</body>

</html>
