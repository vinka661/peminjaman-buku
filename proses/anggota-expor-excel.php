<?php 
require("../vendor/autoload.php");
require("../koneksi.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;   // panggil referensi namespace dari library Spreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;


// $menu = [
//     ['nama' => 'jeruk','jenis' => 'Buah', 'harga' => 14000],
//     ['nama' => 'pisang','jenis' => 'Buah', 'harga' => 12000],
//     ['nama' => 'Nasi Goreng','jenis' => 'Makanan', 'harga' => 12000],
//     ['nama' => 'Mie Goreng','jenis' => 'Makanan', 'harga' => 10000],
//     ['nama' => 'Jus Jambu','jenis' => 'Minuman', 'harga' => 6000],
//     ['nama' => 'Air Mineral','jenis' => 'Minuman', 'harga' => 3000],
//     ['nama' => 'Wortel','jenis' => 'sayur', 'harga' => 9000],
//     ['nama' => 'Tomat','jenis' => 'sayur', 'harga' => 5000],
// ];
$spreadsheet = new Spreadsheet();
$spreadsheet -> setActiveSheetIndex(0)
            ->setCellValue('A1', 'Data Anggota Perpustakaan Umum')     // isi data excel sesuai baris dan kolom
            ->setCellValue('A3', 'No')
            ->setCellValue('B3', 'ID Anggota')
            ->setCellValue('C3', 'Nama')
            ->setCellValue('D3', 'Foto')
            ->setCellValue('E3', 'Jenis Relamin')
            ->setCellValue('F3', 'Alamat');

            $sheet = $spreadsheet->getActiveSheet();

            $index = 4;     // baris mulai isi data dinamis, mulai baris 4
        
            // if (count ($menu) > 0) {
        
            //     foreach ($menu as $idx => $val) {
            //         $idx++;
        
            //         $sheet->setCellValue('A'.$index, $idx);
            //         $sheet->setCellValue('B'.$index, $val['nama']);
            //         $sheet->setCellValue('C'.$index, $val['jenis']);
            //         $sheet->setCellValue('D'.$index, $val['harga']);
        
            //         $index++;
            //     }
            // }
        
            $query = "SELECT * FROM tbanggota ORDER BY idanggota DESC"; 
            $q_tampil_anggota = mysqli_query($db, $query); 
            $idx = 0;
        
            if(mysqli_num_rows($q_tampil_anggota) > 0) { 
                // looping semua data pada tabel tbanggota 
                while($r_tampil_anggota=mysqli_fetch_array($q_tampil_anggota)) { 
                    $idx++;
                    if(empty($r_tampil_anggota['foto']) or ($r_tampil_anggota['foto'] == '-')) {
                        $foto = "admin-no-photo.jpg"; 
                    } else { 
                        $foto = $r_tampil_anggota['foto']; 
                    }
        
                    $sheet->setCellValue('A'.$index, $idx);
                    $sheet->setCellValue('B'.$index, $r_tampil_anggota['idanggota']);
                    $sheet->setCellValue('C'.$index, $r_tampil_anggota['nama']);
                    $sheet->setCellValue('D'.$index, $foto);
                    $sheet->setCellValue('E'.$index, $r_tampil_anggota['jeniskelamin']);
                    $sheet->setCellValue('F'.$index, $r_tampil_anggota['alamat']);
        
                    $index++;
                } // end looping 
            }
        
            $sheet->setTitle('Data Anggota');
            $spreadsheet->setActiveSheetIndex(0);
        
            $filename = 'Data-Anggota-Perpus.xlsx';
        
            ob_end_clean();     // antuk mengatasi excel cannot open the file format or file extension is not valid
        
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
        
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit();
        ?>