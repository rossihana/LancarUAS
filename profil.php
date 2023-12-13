<?php
session_start();
include 'db.php';

class Profil
{
    private $conn;
    private $adminData;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->checkLoginStatus();
        $this->fetchAdminData();
    }

    // Memeriksa status login. Jika tidak login, redirect ke halaman login.
    public function checkLoginStatus()
    {
        if ($_SESSION['status_login'] != true) {
            echo '<script>window.location="login.php"</script>';
        }
    }

    // Mengambil data admin dari database berdasarkan ID sesi.
    public function fetchAdminData()
    {
        $query = mysqli_query($this->conn, "SELECT * FROM tb_admin WHERE admin_id = '" . $_SESSION['id'] . "' ");
        $this->adminData = mysqli_fetch_object($query);
    }

    // Mengelola proses pengeditan profil admin.
    public function editProfile()
    {
        if (isset($_POST['submit'])) {
            $nama = ucwords($_POST['nama']);
            $user = $_POST['user'];
            $hp = $_POST['hp'];
            $email = $_POST['email'];
            $alamat = ucwords($_POST['alamat']);

            $update = mysqli_query($this->conn, "UPDATE tb_admin SET 
                            admin_name = '$nama',
                            username = '$user',
                            admin_telp = '$hp',
                            admin_email = '$email',
                            admin_address = '$alamat'
                            WHERE admin_id = '" . $this->adminData->admin_id . "' ");

            if ($update) {
                echo '<script>alert("Ubah data berhasil")</script>';
                echo '<script>window.location="profil.php"</script>';
            } else {
                echo 'gagal ' . mysqli_error($this->conn);
            }
        }
    }

    // Mengelola proses perubahan password admin.
    public function changePassword()
    {
        if (isset($_POST['ubah_password'])) {
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if ($pass2 != $pass1) {
                echo '<script>alert("Konfirmasi Password Baru tidak sesuai")</script>';
            } else {
                $u_pass = mysqli_query($this->conn, "UPDATE tb_admin SET 
                                password = '" . MD5($pass1) . "'
                                WHERE admin_id = '" . $this->adminData->admin_id . "' ");

                if ($u_pass) {
                    echo '<script>alert("Ubah data berhasil")</script>';
                    echo '<script>window.location="profil.php"</script>';
                } else {
                    echo 'gagal ' . mysqli_error($this->conn);
                }
            }
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

    // Menampilkan formulir profil dan formulir ubah password.
    public function renderProfileForm()
    {
        echo '
        <div class="section">
            <div class="container">
                <h3>Profil</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="' . $this->adminData->admin_name . '" required>
                        <input type="text" name="user" placeholder="Username" class="input-control" value="' . $this->adminData->username . '" required>
                        <input type="text" name="hp" placeholder="No Hp" class="input-control" value="' . $this->adminData->admin_telp . '" required>
                        <input type="email" name="email" placeholder="Email" class="input-control" value="' . $this->adminData->admin_email . '" required>
                        <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="' . $this->adminData->admin_address . '" required>
                        <input type="submit" name="submit" value="Ubah Profil" class="btn">
                    </form>';

        $this->editProfile();

        echo '
                </div>
    
                <h3>Ubah Password</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                        <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                        <input type="submit" name="ubah_password" value="Ubah Password" class="btn">
                    </form>';

        $this->changePassword();

        echo '
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

$warungBerkahUAS = new Profil($conn);
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
    $warungBerkahUAS->renderProfileForm();
    $warungBerkahUAS->renderFooter();
    ?>
</body>

</html>
