Select t_invoice_pelaksanaan.invoice_id As invoice_id,
  Concat(Group_Concat(Date_Format(t_invoice_pelaksanaan.tanggal, '%d') Separator
  ', '), ' ', Date_Format(t_invoice_pelaksanaan.tanggal, '%b'), ' ',
  Date_Format(t_invoice_pelaksanaan.tanggal, '%Y')) As tgl_pelaksanaan
From t_invoice_pelaksanaan
Group By t_invoice_pelaksanaan.invoice_id,
  Concat(Month(t_invoice_pelaksanaan.tanggal),
  Year(t_invoice_pelaksanaan.tanggal))
order by invoice_id, tanggal
