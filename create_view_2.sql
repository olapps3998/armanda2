CREATE VIEW v_invoice_pelaksanaan AS
Select t_invoice_pelaksanaan.invoice_id As invoice_id,
  Concat(Group_Concat(Date_Format(t_invoice_pelaksanaan.tanggal, '%d') Separator
  ', '), ' ', Date_Format(t_invoice_pelaksanaan.tanggal, '%b'), ' ',
  Date_Format(t_invoice_pelaksanaan.tanggal, '%Y')) As tgl_pelaksanaan
From t_invoice_pelaksanaan
Group By t_invoice_pelaksanaan.invoice_id,
  Concat(Month(t_invoice_pelaksanaan.tanggal),
  Year(t_invoice_pelaksanaan.tanggal))
Order By invoice_id,
  t_invoice_pelaksanaan.tanggal;
  
CREATE VIEW v_invoice_pelaksanaan_bln AS
Select t_invoice_pelaksanaan.invoice_id As invoice_id,
  Concat(Group_Concat(Date_Format(t_invoice_pelaksanaan.tanggal, '%d') Separator
  ', '), ' ', Date_Format(t_invoice_pelaksanaan.tanggal, '%b'), ' ',
  Date_Format(t_invoice_pelaksanaan.tanggal, '%Y')) As tgl_pelaksanaan,
  Concat(Date_Format(t_invoice_pelaksanaan.tanggal, '%Y'), ' ',
  Date_Format(t_invoice_pelaksanaan.tanggal, '%m')) As bln_pelaksanaan
From t_invoice_pelaksanaan
Group By t_invoice_pelaksanaan.invoice_id,
  Concat(Month(t_invoice_pelaksanaan.tanggal),
  Year(t_invoice_pelaksanaan.tanggal))
Order By invoice_id,
  t_invoice_pelaksanaan.tanggal;
  
CREATE VIEW v_invoice_pelaksanaan_gab AS
Select v_invoice_pelaksanaan.invoice_id As invoice_id,
  v_invoice_pelaksanaan.tgl_pelaksanaan As tgl_pelaksanaan,
  Group_Concat(v_invoice_pelaksanaan.tgl_pelaksanaan Separator ' ~ ') As gabung
From v_invoice_pelaksanaan
Group By v_invoice_pelaksanaan.invoice_id;
  
CREATE VIEW v_invoice_fee AS
Select t_customer.nama As nama,
  t_customer.alamat As alamat,
  t_customer.kota As kota,
  t_customer.kodepos As kodepos,
  t_customer.npwp As npwp,
  t_invoice.invoice_id As invoice_id,
  t_invoice.nomor As nomor,
  t_invoice.tanggal As tanggal,
  t_invoice.no_order As no_order,
  t_invoice.no_referensi As no_referensi,
  t_invoice.kegiatan As kegiatan,
  t_invoice.no_sertifikat As no_sertifikat,
  t_invoice.keterangan As keterangan,
  t_invoice.total As total,
  t_invoice.ppn As ppn,
  t_invoice.total_ppn As total_ppn,
  t_invoice.terbilang As terbilang,
  t_invoice.terbayar As terbayar,
  t_invoice.pasal23 As pasal23,
  t_invoice.no_kwitansi As no_kwitansi,
  t_fee.jenis As jenis,
  t_invoice_fee.harga As harga,
  t_invoice_fee.qty As qty,
  t_invoice_fee.satuan As satuan,
  t_invoice_fee.jumlah As jumlah,
  t_invoice_fee.keterangan As keterangan1,
  v_invoice_pelaksanaan.tgl_pelaksanaan As tgl_pelaksanaan
From (((t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id)
  Left Join t_invoice_fee On t_invoice.invoice_id = t_invoice_fee.invoice_id)
  Left Join t_fee On t_invoice_fee.fee_id = t_fee.fee_id)
  Left Join v_invoice_pelaksanaan On t_invoice.invoice_id =
    v_invoice_pelaksanaan.invoice_id;
	
CREATE VIEW v_rekap_hutang AS
Select t_customer.nama As nama,
  v_invoice_pelaksanaan.tgl_pelaksanaan As tgl_pelaksanaan,
  t_invoice.no_kwitansi As no_kwitansi,
  t_invoice.nomor As nomor,
  t_invoice.total_ppn As total_ppn,
  t_invoice.invoice_id As invoice_id
From (t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id)
  Left Join v_invoice_pelaksanaan On t_invoice.invoice_id =
    v_invoice_pelaksanaan.invoice_id
Where t_invoice.terbayar = 0;

CREATE VIEW v_rekap_hutang_bln AS
Select t_customer.nama As nama,
  v_invoice_pelaksanaan_bln.tgl_pelaksanaan As tgl_pelaksanaan,
  t_invoice.no_kwitansi As no_kwitansi,
  t_invoice.nomor As nomor,
  t_invoice.total_ppn As total_ppn,
  t_invoice.invoice_id As invoice_id,
  v_invoice_pelaksanaan_bln.bln_pelaksanaan As bln_pelaksanaan
From (t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id)
  Left Join v_invoice_pelaksanaan_bln On t_invoice.invoice_id =
    v_invoice_pelaksanaan_bln.invoice_id
Where t_invoice.terbayar = 0;

CREATE VIEW v_rekap_invoice_all AS
Select t_customer.nama As nama,
  t_invoice.invoice_id As invoice_id,
  t_invoice.nomor As nomor,
  t_invoice.tanggal As tanggal,
  t_invoice.no_sertifikat As no_sertifikat,
  t_invoice.total_ppn As total_ppn,
  t_invoice.no_kwitansi As no_kwitansi,
  v_invoice_pelaksanaan_gab.gabung As tgl_pelaksanaan,
  t_invoice.periode As periode,
  t_invoice.tgl_bayar As tgl_bayar,
  Date_Format(t_invoice.tanggal, '%d %b %Y') As tanggal_short,
  Date_Format(t_invoice.periode, '%b %Y') As periode_short
From (t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id)
  Left Join v_invoice_pelaksanaan_gab On t_invoice.invoice_id =
    v_invoice_pelaksanaan_gab.invoice_id;
	
CREATE VIEW v_rekap_invoice_ppn AS
Select t_invoice.tanggal As tanggal,
  t_customer.nama As nama,
  t_invoice.no_kwitansi As no_kwitansi,
  t_invoice.nomor As nomor,
  t_invoice.no_referensi As no_referensi,
  ((t_invoice.ppn / 100) * t_invoice.total) As nilai_ppn,
  t_invoice.total_ppn As total_ppn,
  t_invoice.invoice_id As invoice_id,
  t_invoice.periode As periode,
  Date_Format(t_invoice.tanggal, '%d %b %Y') As tanggal_short,
  Date_Format(t_invoice.periode, '%b %Y') As periode_short
From t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id
Where t_invoice.ppn <> 0;

