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
Where t_invoice.ppn <> 0
