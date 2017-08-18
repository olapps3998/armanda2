Select t_customer.nama As nama,
  t_invoice.invoice_id As invoice_id,
  t_invoice.nomor As nomor,
  t_invoice.tanggal As tanggal,
  t_invoice.no_sertifikat As no_sertifikat,
  t_invoice.total_ppn As total_ppn,
  t_invoice.no_kwitansi As no_kwitansi,
  v_invoice_pelaksanaan.tgl_pelaksanaan As tgl_pelaksanaan,
  t_invoice.periode As periode,
  t_invoice.tgl_bayar As tgl_bayar,
  Date_Format(t_invoice.tanggal, '%d %b %Y') As tanggal_short,
  date_format(t_invoice.periode, '%b %Y') As periode_short
From (t_invoice
  Join t_customer On t_invoice.customer_id = t_customer.customer_id)
  Left Join v_invoice_pelaksanaan On t_invoice.invoice_id =
    v_invoice_pelaksanaan.invoice_id
