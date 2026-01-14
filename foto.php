<?php 
include "upload_foto.php";

//jika tombol simpan diklik
if (isset($_POST['simpan'])) {
    // $password = md5($_POST['password']); 
    // Not updating password here, dedicated page exists.
    
    $foto = $_FILES['foto']['name'];
    $foto_lama = $_POST['foto_lama']; 

    $upload_status = true; // Assume success unless upload fails
    $foto_baru = $foto_lama; // Default to old photo

    // Jika ada file yang diupload, lakukan pengecekan
    if ($foto != "") {
        $cek_upload = upload_foto($_FILES['foto']); 
        
        if ($cek_upload['status']) {
            $foto_baru = $cek_upload['message'];
        } else {
            $upload_status = false;
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=foto';
            </script>";
            die; // Stop execution if upload fails
        }
    } 
    
    if ($upload_status) {
        // Update Data (Nama and/or Foto)
        // We always update 'nama' and 'foto' (even if foto is same)
        // Using prepared statements for security
        
        if(isset($_POST['nama'])) {
             $stmt = $conn->prepare("UPDATE user SET nama = ?, foto = ? WHERE username = ?");
             $stmt->bind_param("sss", $_POST['nama'], $foto_baru, $_SESSION['username']);
             $simpan = $stmt->execute();
        } else {
             // Fallback if nama is somehow missing? But input is required.
             $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE username = ?");
             $stmt->bind_param("ss", $foto_baru, $_SESSION['username']);
             $simpan = $stmt->execute();
        }

        if ($simpan) {
            echo "<script>
                alert('Simpan data sukses');
                document.location='admin.php?page=foto';
            </script>";
        } else {
            echo "<script>
                alert('Simpan data gagal');
                document.location='admin.php?page=foto';
            </script>";
        }

        $stmt->close();
    }
}

//Fetch User Data to display
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="container">
    <form action="" method="post" enctype="multipart/form-data">
        
        <!-- Field Username (Readonly) -->
         <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control rounded-4" name="username" value="<?= $data['username'] ?>" readonly>
        </div>

        <!-- Field Nama -->
         <!-- Added styling to match existing theme -->
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control rounded-4" name="nama" value="<?= $data['nama'] ?>" required>
        </div>
        
        <!-- Field Foto -->
        <div class="mb-3">
            <label for="foto" class="form-label">Ganti Foto Profil</label>
            <input type="file" name="foto" class="form-control rounded-4">
        </div>
        
        <!-- Display Current Photo -->
        <div class="mb-3">
            <label class="form-label">Foto Saat Ini:</label><br>
            <?php 
            if ($data['foto'] != "") {
                if(file_exists("img/" . $data['foto'])){
            ?>
                <img src="img/<?= $data['foto'] ?>" width="150" class="img-thumbnail rounded-circle border shadow">
            <?php
                } else {
                    //fallback
                    echo '<img src="img/profil.png" width="150" class="img-thumbnail rounded-circle border shadow">';
                }
            } else {
                echo '<img src="img/profil.png" width="150" class="img-thumbnail rounded-circle border shadow">';
            }
            ?>
            <input type="hidden" name="foto_lama" value="<?= $data['foto'] ?>">
        </div>
        
        <div class="mb-3 d-grid">
            <button type="submit" name="simpan" class="btn btn-danger rounded-4">Simpan</button>
        </div>
    </form>
</div>