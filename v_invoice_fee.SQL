SELECT t_customer.nama AS nama,
  t_customer.alamat AS alamat,
  t_customer.kota AS kota,
  t_customer.kodepos AS kodepos,
  t_customer.npwp AS npwp,
  t_invoice.invoice_id AS invoice_id,
  t_invoice.nomor AS nomor,
  t_invoice.tanggal AS tanggal,
  t_invoice.no_order AS no_order,
  t_invoice.no_referensi AS no_referensi,
  t_invoice.kegiatan AS kegiatan,
  t_invoice.no_sertifikat AS no_sertifikat,
  t_invoice.keterangan AS keterangan,
  t_invoice.total AS total,
  t_invoice.ppn AS ppn,
  t_invoice.total_ppn AS total_ppn,
  t_invoice.terbilang AS terbilang,
  t_invoice.terbayar AS terbayar,
  t_invoice.pasal23 AS pasal23,
  t_invoice.no_kwitansi AS no_kwitansi,
  t_fee.jenis AS jenis,
  t_invoice_fee.harga AS harga,
  t_invoice_fee.qty AS qty,
  t_invoice_fee.satuan AS satuan,
  t_invoice_fee.jumlah AS jumlah,
  t_invoice_fee.keterangan AS keterangan1,
  v_invoice_pelaksanaan.tgl_pelaksanaan AS tgl_pelaksanaan
FROM (((t_invoice
  JOIN t_customer ON t_invoice.customer_id = t_customer.customer_id)
  left JOIN t_invoice_fee ON t_invoice.invoice_id = t_invoice_fee.invoice_id)
  left JOIN t_fee ON t_invoice_fee.fee_id = t_fee.fee_id)
  left JOIN v_invoice_pelaksanaan ON t_invoice.invoice_id =
    v_invoice_pelaksanaan.invoice_id
