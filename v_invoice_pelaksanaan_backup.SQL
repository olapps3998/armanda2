Select t_invoice_pelaksanaan.invoice_id As invoice_id,
  Group_Concat(Date_Format(t_invoice_pelaksanaan.tanggal, '%d-%m-%Y') Separator
  ', ') As tgl_pelaksanaan
From t_invoice_pelaksanaan
Group By t_invoice_pelaksanaan.invoice_id
