<?php 
    include '../koneksi.php'; // menyisipkan/memanggil file koneksi.php untuk koneksi data dengan basis data 
    
    $id_buku = $_POST['id_buku']; 
    $judul = $_POST['judul']; 
    $kategori = $_POST['kategori']; 
    $pengarang = $_POST['pengarang'];  
    $penerbit = $_POST['penerbit']; 
    $status = $_POST['status'];
    
    if(isset($_POST['simpan'])) { // cek jika ada form yang di submit 
        extract($_POST); 
        $judul_file = $_FILES['cover']['name']; 
    
        if(!empty($judul_file)){ 
            // Baca lokasi file sementara dan judul file dari form (upload) 
            $lokasi_file = $_FILES['cover']['tmp_name']; 
            $tipe_file = pathinfo($judul_file, PATHINFO_EXTENSION); 
            $file_cover = $id_buku.".".$tipe_file; 
    
            $folder = "../images/$file_cover"; // Tentukan folder untuk menyimpan file 
            @unlink ("$folder"); // hapus cover yang lama, tanda @ untuk menyembunyikan pesan warning, jika file tidak ditemukan 
            move_uploaded_file($lokasi_file,"$folder"); // Apabila file berhasil di upload 
        } else {
            $file_cover=$cover_awal; 
        } 
    
        mysqli_query($db, "UPDATE tbbuku 
                            SET judul='$judul', kategori='$kategori', pengarang='$pengarang', penerbit='$penerbit', status='$status', cover='$file_cover' 
                            WHERE idbuku = '$id_buku'"); 
    
        header("location:../index.php?p=buku"); 
    }
?>