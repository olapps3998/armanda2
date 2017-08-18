<?php

// customer_id
// nomor
// tanggal
// no_order
// no_referensi
// kegiatan
// no_sertifikat
// keterangan
// total
// ppn
// total_ppn
// terbilang
// terbayar
// tgl_bayar
// pasal23
// no_kwitansi
// periode

?>
<?php if ($t_invoice->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_invoice->TableCaption() ?></h4> -->
<table id="tbl_t_invoicemaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_invoice->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_invoice->customer_id->Visible) { // customer_id ?>
		<tr id="r_customer_id">
			<td><?php echo $t_invoice->customer_id->FldCaption() ?></td>
			<td<?php echo $t_invoice->customer_id->CellAttributes() ?>>
<span id="el_t_invoice_customer_id">
<span<?php echo $t_invoice->customer_id->ViewAttributes() ?>>
<?php echo $t_invoice->customer_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->nomor->Visible) { // nomor ?>
		<tr id="r_nomor">
			<td><?php echo $t_invoice->nomor->FldCaption() ?></td>
			<td<?php echo $t_invoice->nomor->CellAttributes() ?>>
<span id="el_t_invoice_nomor">
<span<?php echo $t_invoice->nomor->ViewAttributes() ?>>
<?php echo $t_invoice->nomor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->tanggal->Visible) { // tanggal ?>
		<tr id="r_tanggal">
			<td><?php echo $t_invoice->tanggal->FldCaption() ?></td>
			<td<?php echo $t_invoice->tanggal->CellAttributes() ?>>
<span id="el_t_invoice_tanggal">
<span<?php echo $t_invoice->tanggal->ViewAttributes() ?>>
<?php echo $t_invoice->tanggal->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->no_order->Visible) { // no_order ?>
		<tr id="r_no_order">
			<td><?php echo $t_invoice->no_order->FldCaption() ?></td>
			<td<?php echo $t_invoice->no_order->CellAttributes() ?>>
<span id="el_t_invoice_no_order">
<span<?php echo $t_invoice->no_order->ViewAttributes() ?>>
<?php echo $t_invoice->no_order->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->no_referensi->Visible) { // no_referensi ?>
		<tr id="r_no_referensi">
			<td><?php echo $t_invoice->no_referensi->FldCaption() ?></td>
			<td<?php echo $t_invoice->no_referensi->CellAttributes() ?>>
<span id="el_t_invoice_no_referensi">
<span<?php echo $t_invoice->no_referensi->ViewAttributes() ?>>
<?php echo $t_invoice->no_referensi->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->kegiatan->Visible) { // kegiatan ?>
		<tr id="r_kegiatan">
			<td><?php echo $t_invoice->kegiatan->FldCaption() ?></td>
			<td<?php echo $t_invoice->kegiatan->CellAttributes() ?>>
<span id="el_t_invoice_kegiatan">
<span<?php echo $t_invoice->kegiatan->ViewAttributes() ?>>
<?php echo $t_invoice->kegiatan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->no_sertifikat->Visible) { // no_sertifikat ?>
		<tr id="r_no_sertifikat">
			<td><?php echo $t_invoice->no_sertifikat->FldCaption() ?></td>
			<td<?php echo $t_invoice->no_sertifikat->CellAttributes() ?>>
<span id="el_t_invoice_no_sertifikat">
<span<?php echo $t_invoice->no_sertifikat->ViewAttributes() ?>>
<?php echo $t_invoice->no_sertifikat->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->keterangan->Visible) { // keterangan ?>
		<tr id="r_keterangan">
			<td><?php echo $t_invoice->keterangan->FldCaption() ?></td>
			<td<?php echo $t_invoice->keterangan->CellAttributes() ?>>
<span id="el_t_invoice_keterangan">
<span<?php echo $t_invoice->keterangan->ViewAttributes() ?>>
<?php echo $t_invoice->keterangan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->total->Visible) { // total ?>
		<tr id="r_total">
			<td><?php echo $t_invoice->total->FldCaption() ?></td>
			<td<?php echo $t_invoice->total->CellAttributes() ?>>
<span id="el_t_invoice_total">
<span<?php echo $t_invoice->total->ViewAttributes() ?>>
<?php echo $t_invoice->total->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->ppn->Visible) { // ppn ?>
		<tr id="r_ppn">
			<td><?php echo $t_invoice->ppn->FldCaption() ?></td>
			<td<?php echo $t_invoice->ppn->CellAttributes() ?>>
<span id="el_t_invoice_ppn">
<span<?php echo $t_invoice->ppn->ViewAttributes() ?>>
<?php echo $t_invoice->ppn->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->total_ppn->Visible) { // total_ppn ?>
		<tr id="r_total_ppn">
			<td><?php echo $t_invoice->total_ppn->FldCaption() ?></td>
			<td<?php echo $t_invoice->total_ppn->CellAttributes() ?>>
<span id="el_t_invoice_total_ppn">
<span<?php echo $t_invoice->total_ppn->ViewAttributes() ?>>
<?php echo $t_invoice->total_ppn->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->terbilang->Visible) { // terbilang ?>
		<tr id="r_terbilang">
			<td><?php echo $t_invoice->terbilang->FldCaption() ?></td>
			<td<?php echo $t_invoice->terbilang->CellAttributes() ?>>
<span id="el_t_invoice_terbilang">
<span<?php echo $t_invoice->terbilang->ViewAttributes() ?>>
<?php echo $t_invoice->terbilang->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->terbayar->Visible) { // terbayar ?>
		<tr id="r_terbayar">
			<td><?php echo $t_invoice->terbayar->FldCaption() ?></td>
			<td<?php echo $t_invoice->terbayar->CellAttributes() ?>>
<span id="el_t_invoice_terbayar">
<span<?php echo $t_invoice->terbayar->ViewAttributes() ?>>
<?php echo $t_invoice->terbayar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->tgl_bayar->Visible) { // tgl_bayar ?>
		<tr id="r_tgl_bayar">
			<td><?php echo $t_invoice->tgl_bayar->FldCaption() ?></td>
			<td<?php echo $t_invoice->tgl_bayar->CellAttributes() ?>>
<span id="el_t_invoice_tgl_bayar">
<span<?php echo $t_invoice->tgl_bayar->ViewAttributes() ?>>
<?php echo $t_invoice->tgl_bayar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->pasal23->Visible) { // pasal23 ?>
		<tr id="r_pasal23">
			<td><?php echo $t_invoice->pasal23->FldCaption() ?></td>
			<td<?php echo $t_invoice->pasal23->CellAttributes() ?>>
<span id="el_t_invoice_pasal23">
<span<?php echo $t_invoice->pasal23->ViewAttributes() ?>>
<?php echo $t_invoice->pasal23->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->no_kwitansi->Visible) { // no_kwitansi ?>
		<tr id="r_no_kwitansi">
			<td><?php echo $t_invoice->no_kwitansi->FldCaption() ?></td>
			<td<?php echo $t_invoice->no_kwitansi->CellAttributes() ?>>
<span id="el_t_invoice_no_kwitansi">
<span<?php echo $t_invoice->no_kwitansi->ViewAttributes() ?>>
<?php echo $t_invoice->no_kwitansi->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_invoice->periode->Visible) { // periode ?>
		<tr id="r_periode">
			<td><?php echo $t_invoice->periode->FldCaption() ?></td>
			<td<?php echo $t_invoice->periode->CellAttributes() ?>>
<span id="el_t_invoice_periode">
<span<?php echo $t_invoice->periode->ViewAttributes() ?>>
<?php echo $t_invoice->periode->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
