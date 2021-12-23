<?php
require("../vendor/autoload.php");     //load file autoload.php dari composser
require("../koneksi.php");      //load konfigurasi untuk koneksi ke DB

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//membuat heading dari tabel dengan nama masing" kolom
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Peminjaman');
$sheet->setCellValue('C1', 'ID Anggota');
$sheet->setCellValue('D1', 'ID Buku');
$sheet->setCellValue('E1', 'Tanggal Peminjaman');

 
$query = mysqli_query($db,"select * from tbpinjam");	//mengambil data table tbbuku dari db
$i = 2;	//index yg akan digunakan untuk mengisi pertama kali
$no = 1;	//memberi nomor urut data

//extract hasil query dan setiap data yg dihasilkan dari perulangan akan
//disimpan di var $row
while($row = mysqli_fetch_array($query))
{
	//digunakan untuk menuliskan data dari hasil query sesuai kolom yg ditentukan
	$sheet->setCellValue('A'.$i, $no++);
	$sheet->setCellValue('B'.$i, $row['idpeminjaman']);
	$sheet->setCellValue('C'.$i, $row['idanggota']);
	$sheet->setCellValue('D'.$i, $row['idbuku']);	
	$sheet->setCellValue('E'.$i, $row['tglpinjam']);		
	$i++;
}

//membuat array $styleArray dimana didalamnya terdapat settingan border untuk cell
$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
$i = $i - 1;
//hasil array di Line 26 yaitu $styleArray yang berisi settingan border,
//agar digunakan dari Cell A1 hingga kolom F
$sheet->getStyle('A1:G'.$i)->applyFromArray($styleArray);
 
$filename = 'Data-Peminjaman-Perpustakaan.xlsx';
ob_end_clean();     //untuk mengatasi excel cannot open the file format or file extension

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragme: public');

$writer = IOFActory::createWriter($spreadsheet, 'Xlsx');
$writer -> save('php://output');
exit();
?>