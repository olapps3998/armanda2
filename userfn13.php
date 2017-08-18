<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function Terbilang($x)
{
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

function tgl_indo($tgl) {
	$a_namabln = array(
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
	$tgl_data = strtotime($tgl);

	//$tgl_data = $tgl;
	$tanggal = date("d", $tgl_data);
	$bulan = $a_namabln[date("m", $tgl_data)];
	$tahun = date("Y", $tgl_data);
	return $tanggal." ".$bulan." ".$tahun;
}
?>
