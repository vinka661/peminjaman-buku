<?php 
include '../koneksi.php'; // menyisipkan/memanggil file koneksi.php untuk koneksi data dengan basis data 

$id_buku = $_POST['id_buku']; 
$judul = $_POST['judul']; 
$kategori = $_POST['kategori']; 
$pengarang = $_POST['pengarang'];
$penerbit = $_POST['penerbit'];
$status = $_POST['status'];
 
if(isset($_POST['simpan'])) { 
    extract($_POST); 
    $judul_file = $_FILES['cover']['name']; 

    if(!empty($judul_file)) { 
        // Baca lokasi file sementara dan judul file dari form (upload) 
        $lokasi_file = $_FILES['cover']['tmp_name']; 
        $tipe_file = pathinfo($judul_file, PATHINFO_EXTENSION); 
        $file_cover = $id_buku.".".$tipe_file; 

        $folder = "../images/$file_cover"; // Tentukan folder untuk menyimpan file 
        move_uploaded_file($lokasi_file,"$folder"); // Apabila file berhasil di upload 
    } else { 
        $filecover="-"; 
    } 
    
    $sql = "INSERT INTO tbbuku VALUES('$id_buku','$judul','$kategori','$pengarang', '$penerbit', '$status','$file_cover')"; 
    $query = mysqli_query($db, $sql); 

    header("location:../index.php?p=buku");
}
?> 
