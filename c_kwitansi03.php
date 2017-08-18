<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Armanda');
$pdf->SetTitle('Kwitansi');
$pdf->SetSubject('Kwitansi');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// set font
//$pdf->SetFont('times', 'BI', 20);

// add a page
//$pdf->AddPage("L", "A5");
$pdf->AddPage();

include("conn.php");

mysql_connect($hostname_conn, $username_conn, $password_conn) or die ("Tidak bisa terkoneksi ke Database server");
mysql_select_db($database_conn) or die ("Database tidak ditemukan");

// array nama bulan
$anamabln_ = array(
  1 => "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember"
  );

$msql = "
	select 
		a.*, 
		b.nama 
	from 
		t_invoice a 
		left join t_customer b on a.customer_id = b.customer_id 
	where 
		a.no_kwitansi = '".$_GET["no_kwitansi"]."'"; //echo $msql;
$mquery = mysql_query($msql);

$nilai_pasal23 = 0;
$total_ppn = 0;
while ($row = mysql_fetch_array($mquery)) {
	$no_kwitansi = $row["no_kwitansi"];
	$nama = $row["nama"];
	$keterangan = $row["keterangan"];
	$nomor .= $row["nomor"].", ";
	$no_sertifikat .= $row["no_sertifikat"].", ";
	$tanggal = $row["tanggal"];
	if ($row["pasal23"] == 1) $nilai_pasal23 = $row["total"] * 0.02;
	$total_ppn += $row["total_ppn"] - $nilai_pasal23; //$row["total_ppn"];	
}

$nomor = substr($nomor, 0, -2);
$no_sertifikat = substr($no_sertifikat, 0, -2);

function Terbilang($x) {
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
	return " " . $abil[$x];
  elseif ($x < 20)
	return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
	return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
	return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
	return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
	return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
	return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
	return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

$html = '';
$html .= '<table border="0">';
$html .= '<tr><td width="485">&nbsp;</td><td>Kwitansi No. '.$no_kwitansi.'</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$html .= '<tr><td width="155">Sudah terima dari</td><td width="485">: '.$nama.'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td width="155">Banyaknya uang</td><td>: '.Terbilang($total_ppn).' rupiah</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td width="155">Untuk Pembayaran</td><td>: '.$keterangan.'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td width="155">&nbsp;</td><td>No. Invoice : '.$nomor.'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td width="155">&nbsp;</td><td>No. Sertifikat : '.$no_sertifikat.'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td width="155">&nbsp;</td><td>NO. ACC. CV. ARMANDA NUSANTARA</td></tr>';
$html .= '<tr><td width="155">&nbsp;</td><td>512 - 015691 - 0 &nbsp; &nbsp; BANK BCA CABANG PRAPEN</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$tgl_invoice = strtotime($tanggal);
$html .= '<tr><td width="435">&nbsp;</td><td>Surabaya, '.date("d", $tgl_invoice).' '.$anamabln_[intval(date("m", $tgl_invoice))].' '.date("Y", $tgl_invoice).'</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$html .= '<tr><td width="155" align="right">Terbilang &nbsp;&nbsp;&nbsp;&nbsp;Rp.</td><td> '.number_format($total_ppn).'</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$html .= '<tr><td width="385">&nbsp;</td><td align="center">&nbsp;</td></tr>';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td><td align="center">SEINDRI SUSANTI</td></tr>';
$html .= '</table>';

//echo $html;
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Kwitansi.pdf', 'I');
?>
