<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_invoice_fee_grid)) $t_invoice_fee_grid = new ct_invoice_fee_grid();

// Page init
$t_invoice_fee_grid->Page_Init();

// Page main
$t_invoice_fee_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_invoice_fee_grid->Page_Render();
?>
<?php if ($t_invoice_fee->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_invoice_feegrid = new ew_Form("ft_invoice_feegrid", "grid");
ft_invoice_feegrid.FormKeyCountName = '<?php echo $t_invoice_fee_grid->FormKeyCountName ?>';

// Validate form
ft_invoice_feegrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_fee_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice_fee->fee_id->FldCaption(), $t_invoice_fee->fee_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice_fee->harga->FldCaption(), $t_invoice_fee->harga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice_fee->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice_fee->qty->FldCaption(), $t_invoice_fee->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice_fee->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice_fee->jumlah->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_invoice_feegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fee_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "harga", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qty", false)) return false;
	if (ew_ValueChanged(fobj, infix, "satuan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jumlah", false)) return false;
	if (ew_ValueChanged(fobj, infix, "keterangan", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_invoice_feegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_invoice_feegrid.ValidateRequired = true;
<?php } else { ?>
ft_invoice_feegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_invoice_feegrid.Lists["x_fee_id"] = {"LinkField":"x_fee_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_fee"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_invoice_fee->CurrentAction == "gridadd") {
	if ($t_invoice_fee->CurrentMode == "copy") {
		$bSelectLimit = $t_invoice_fee_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_invoice_fee_grid->TotalRecs = $t_invoice_fee->SelectRecordCount();
			$t_invoice_fee_grid->Recordset = $t_invoice_fee_grid->LoadRecordset($t_invoice_fee_grid->StartRec-1, $t_invoice_fee_grid->DisplayRecs);
		} else {
			if ($t_invoice_fee_grid->Recordset = $t_invoice_fee_grid->LoadRecordset())
				$t_invoice_fee_grid->TotalRecs = $t_invoice_fee_grid->Recordset->RecordCount();
		}
		$t_invoice_fee_grid->StartRec = 1;
		$t_invoice_fee_grid->DisplayRecs = $t_invoice_fee_grid->TotalRecs;
	} else {
		$t_invoice_fee->CurrentFilter = "0=1";
		$t_invoice_fee_grid->StartRec = 1;
		$t_invoice_fee_grid->DisplayRecs = $t_invoice_fee->GridAddRowCount;
	}
	$t_invoice_fee_grid->TotalRecs = $t_invoice_fee_grid->DisplayRecs;
	$t_invoice_fee_grid->StopRec = $t_invoice_fee_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_invoice_fee_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_invoice_fee_grid->TotalRecs <= 0)
			$t_invoice_fee_grid->TotalRecs = $t_invoice_fee->SelectRecordCount();
	} else {
		if (!$t_invoice_fee_grid->Recordset && ($t_invoice_fee_grid->Recordset = $t_invoice_fee_grid->LoadRecordset()))
			$t_invoice_fee_grid->TotalRecs = $t_invoice_fee_grid->Recordset->RecordCount();
	}
	$t_invoice_fee_grid->StartRec = 1;
	$t_invoice_fee_grid->DisplayRecs = $t_invoice_fee_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_invoice_fee_grid->Recordset = $t_invoice_fee_grid->LoadRecordset($t_invoice_fee_grid->StartRec-1, $t_invoice_fee_grid->DisplayRecs);

	// Set no record found message
	if ($t_invoice_fee->CurrentAction == "" && $t_invoice_fee_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_invoice_fee_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_invoice_fee_grid->SearchWhere == "0=101")
			$t_invoice_fee_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_invoice_fee_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_invoice_fee_grid->RenderOtherOptions();
?>
<?php $t_invoice_fee_grid->ShowPageHeader(); ?>
<?php
$t_invoice_fee_grid->ShowMessage();
?>
<?php if ($t_invoice_fee_grid->TotalRecs > 0 || $t_invoice_fee->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_invoice_fee">
<div id="ft_invoice_feegrid" class="ewForm form-inline">
<div id="gmp_t_invoice_fee" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_invoice_feegrid" class="table ewTable">
<?php echo $t_invoice_fee->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_invoice_fee_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_invoice_fee_grid->RenderListOptions();

// Render list options (header, left)
$t_invoice_fee_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_invoice_fee->fee_id->Visible) { // fee_id ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->fee_id) == "") { ?>
		<th data-name="fee_id"><div id="elh_t_invoice_fee_fee_id" class="t_invoice_fee_fee_id"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->fee_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fee_id"><div><div id="elh_t_invoice_fee_fee_id" class="t_invoice_fee_fee_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->fee_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->fee_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->fee_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_invoice_fee->harga->Visible) { // harga ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->harga) == "") { ?>
		<th data-name="harga"><div id="elh_t_invoice_fee_harga" class="t_invoice_fee_harga"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->harga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="harga"><div><div id="elh_t_invoice_fee_harga" class="t_invoice_fee_harga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->harga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_invoice_fee->qty->Visible) { // qty ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->qty) == "") { ?>
		<th data-name="qty"><div id="elh_t_invoice_fee_qty" class="t_invoice_fee_qty"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty"><div><div id="elh_t_invoice_fee_qty" class="t_invoice_fee_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_invoice_fee->satuan->Visible) { // satuan ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->satuan) == "") { ?>
		<th data-name="satuan"><div id="elh_t_invoice_fee_satuan" class="t_invoice_fee_satuan"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->satuan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan"><div><div id="elh_t_invoice_fee_satuan" class="t_invoice_fee_satuan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->satuan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->satuan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->satuan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_invoice_fee->jumlah->Visible) { // jumlah ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->jumlah) == "") { ?>
		<th data-name="jumlah"><div id="elh_t_invoice_fee_jumlah" class="t_invoice_fee_jumlah"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->jumlah->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jumlah"><div><div id="elh_t_invoice_fee_jumlah" class="t_invoice_fee_jumlah">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->jumlah->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->jumlah->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->jumlah->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_invoice_fee->keterangan->Visible) { // keterangan ?>
	<?php if ($t_invoice_fee->SortUrl($t_invoice_fee->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_t_invoice_fee_keterangan" class="t_invoice_fee_keterangan"><div class="ewTableHeaderCaption"><?php echo $t_invoice_fee->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div><div id="elh_t_invoice_fee_keterangan" class="t_invoice_fee_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_invoice_fee->keterangan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_invoice_fee->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_invoice_fee->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_invoice_fee_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_invoice_fee_grid->StartRec = 1;
$t_invoice_fee_grid->StopRec = $t_invoice_fee_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_invoice_fee_grid->FormKeyCountName) && ($t_invoice_fee->CurrentAction == "gridadd" || $t_invoice_fee->CurrentAction == "gridedit" || $t_invoice_fee->CurrentAction == "F")) {
		$t_invoice_fee_grid->KeyCount = $objForm->GetValue($t_invoice_fee_grid->FormKeyCountName);
		$t_invoice_fee_grid->StopRec = $t_invoice_fee_grid->StartRec + $t_invoice_fee_grid->KeyCount - 1;
	}
}
$t_invoice_fee_grid->RecCnt = $t_invoice_fee_grid->StartRec - 1;
if ($t_invoice_fee_grid->Recordset && !$t_invoice_fee_grid->Recordset->EOF) {
	$t_invoice_fee_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_invoice_fee_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_invoice_fee_grid->StartRec > 1)
		$t_invoice_fee_grid->Recordset->Move($t_invoice_fee_grid->StartRec - 1);
} elseif (!$t_invoice_fee->AllowAddDeleteRow && $t_invoice_fee_grid->StopRec == 0) {
	$t_invoice_fee_grid->StopRec = $t_invoice_fee->GridAddRowCount;
}

// Initialize aggregate
$t_invoice_fee->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_invoice_fee->ResetAttrs();
$t_invoice_fee_grid->RenderRow();
if ($t_invoice_fee->CurrentAction == "gridadd")
	$t_invoice_fee_grid->RowIndex = 0;
if ($t_invoice_fee->CurrentAction == "gridedit")
	$t_invoice_fee_grid->RowIndex = 0;
while ($t_invoice_fee_grid->RecCnt < $t_invoice_fee_grid->StopRec) {
	$t_invoice_fee_grid->RecCnt++;
	if (intval($t_invoice_fee_grid->RecCnt) >= intval($t_invoice_fee_grid->StartRec)) {
		$t_invoice_fee_grid->RowCnt++;
		if ($t_invoice_fee->CurrentAction == "gridadd" || $t_invoice_fee->CurrentAction == "gridedit" || $t_invoice_fee->CurrentAction == "F") {
			$t_invoice_fee_grid->RowIndex++;
			$objForm->Index = $t_invoice_fee_grid->RowIndex;
			if ($objForm->HasValue($t_invoice_fee_grid->FormActionName))
				$t_invoice_fee_grid->RowAction = strval($objForm->GetValue($t_invoice_fee_grid->FormActionName));
			elseif ($t_invoice_fee->CurrentAction == "gridadd")
				$t_invoice_fee_grid->RowAction = "insert";
			else
				$t_invoice_fee_grid->RowAction = "";
		}

		// Set up key count
		$t_invoice_fee_grid->KeyCount = $t_invoice_fee_grid->RowIndex;

		// Init row class and style
		$t_invoice_fee->ResetAttrs();
		$t_invoice_fee->CssClass = "";
		if ($t_invoice_fee->CurrentAction == "gridadd") {
			if ($t_invoice_fee->CurrentMode == "copy") {
				$t_invoice_fee_grid->LoadRowValues($t_invoice_fee_grid->Recordset); // Load row values
				$t_invoice_fee_grid->SetRecordKey($t_invoice_fee_grid->RowOldKey, $t_invoice_fee_grid->Recordset); // Set old record key
			} else {
				$t_invoice_fee_grid->LoadDefaultValues(); // Load default values
				$t_invoice_fee_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_invoice_fee_grid->LoadRowValues($t_invoice_fee_grid->Recordset); // Load row values
		}
		$t_invoice_fee->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_invoice_fee->CurrentAction == "gridadd") // Grid add
			$t_invoice_fee->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_invoice_fee->CurrentAction == "gridadd" && $t_invoice_fee->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_invoice_fee_grid->RestoreCurrentRowFormValues($t_invoice_fee_grid->RowIndex); // Restore form values
		if ($t_invoice_fee->CurrentAction == "gridedit") { // Grid edit
			if ($t_invoice_fee->EventCancelled) {
				$t_invoice_fee_grid->RestoreCurrentRowFormValues($t_invoice_fee_grid->RowIndex); // Restore form values
			}
			if ($t_invoice_fee_grid->RowAction == "insert")
				$t_invoice_fee->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_invoice_fee->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_invoice_fee->CurrentAction == "gridedit" && ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT || $t_invoice_fee->RowType == EW_ROWTYPE_ADD) && $t_invoice_fee->EventCancelled) // Update failed
			$t_invoice_fee_grid->RestoreCurrentRowFormValues($t_invoice_fee_grid->RowIndex); // Restore form values
		if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_invoice_fee_grid->EditRowCnt++;
		if ($t_invoice_fee->CurrentAction == "F") // Confirm row
			$t_invoice_fee_grid->RestoreCurrentRowFormValues($t_invoice_fee_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_invoice_fee->RowAttrs = array_merge($t_invoice_fee->RowAttrs, array('data-rowindex'=>$t_invoice_fee_grid->RowCnt, 'id'=>'r' . $t_invoice_fee_grid->RowCnt . '_t_invoice_fee', 'data-rowtype'=>$t_invoice_fee->RowType));

		// Render row
		$t_invoice_fee_grid->RenderRow();

		// Render list options
		$t_invoice_fee_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_invoice_fee_grid->RowAction <> "delete" && $t_invoice_fee_grid->RowAction <> "insertdelete" && !($t_invoice_fee_grid->RowAction == "insert" && $t_invoice_fee->CurrentAction == "F" && $t_invoice_fee_grid->EmptyRow())) {
?>
	<tr<?php echo $t_invoice_fee->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_invoice_fee_grid->ListOptions->Render("body", "left", $t_invoice_fee_grid->RowCnt);
?>
	<?php if ($t_invoice_fee->fee_id->Visible) { // fee_id ?>
		<td data-name="fee_id"<?php echo $t_invoice_fee->fee_id->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_fee_id" class="form-group t_invoice_fee_fee_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id"><?php echo (strval($t_invoice_fee->fee_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_invoice_fee->fee_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_invoice_fee->fee_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_invoice_fee->fee_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->CurrentValue ?>"<?php echo $t_invoice_fee->fee_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_fee_id" class="form-group t_invoice_fee_fee_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id"><?php echo (strval($t_invoice_fee->fee_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_invoice_fee->fee_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_invoice_fee->fee_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_invoice_fee->fee_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->CurrentValue ?>"<?php echo $t_invoice_fee->fee_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_fee_id" class="t_invoice_fee_fee_id">
<span<?php echo $t_invoice_fee->fee_id->ViewAttributes() ?>>
<?php echo $t_invoice_fee->fee_id->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_invoice_fee_grid->PageObjName . "_row_" . $t_invoice_fee_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_invoice_detail_id" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->invoice_detail_id->CurrentValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_invoice_detail_id" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->invoice_detail_id->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT || $t_invoice_fee->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_invoice_detail_id" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_invoice_detail_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->invoice_detail_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_invoice_fee->harga->Visible) { // harga ?>
		<td data-name="harga"<?php echo $t_invoice_fee->harga->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_harga" class="form-group t_invoice_fee_harga">
<input type="text" data-table="t_invoice_fee" data-field="x_harga" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->harga->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->harga->EditValue ?>"<?php echo $t_invoice_fee->harga->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_harga" class="form-group t_invoice_fee_harga">
<input type="text" data-table="t_invoice_fee" data-field="x_harga" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->harga->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->harga->EditValue ?>"<?php echo $t_invoice_fee->harga->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_harga" class="t_invoice_fee_harga">
<span<?php echo $t_invoice_fee->harga->ViewAttributes() ?>>
<?php echo $t_invoice_fee->harga->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $t_invoice_fee->qty->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_qty" class="form-group t_invoice_fee_qty">
<input type="text" data-table="t_invoice_fee" data-field="x_qty" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->qty->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->qty->EditValue ?>"<?php echo $t_invoice_fee->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_qty" class="form-group t_invoice_fee_qty">
<input type="text" data-table="t_invoice_fee" data-field="x_qty" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->qty->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->qty->EditValue ?>"<?php echo $t_invoice_fee->qty->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_qty" class="t_invoice_fee_qty">
<span<?php echo $t_invoice_fee->qty->ViewAttributes() ?>>
<?php echo $t_invoice_fee->qty->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->satuan->Visible) { // satuan ?>
		<td data-name="satuan"<?php echo $t_invoice_fee->satuan->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_satuan" class="form-group t_invoice_fee_satuan">
<input type="text" data-table="t_invoice_fee" data-field="x_satuan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->satuan->EditValue ?>"<?php echo $t_invoice_fee->satuan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_satuan" class="form-group t_invoice_fee_satuan">
<input type="text" data-table="t_invoice_fee" data-field="x_satuan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->satuan->EditValue ?>"<?php echo $t_invoice_fee->satuan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_satuan" class="t_invoice_fee_satuan">
<span<?php echo $t_invoice_fee->satuan->ViewAttributes() ?>>
<?php echo $t_invoice_fee->satuan->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->jumlah->Visible) { // jumlah ?>
		<td data-name="jumlah"<?php echo $t_invoice_fee->jumlah->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_jumlah" class="form-group t_invoice_fee_jumlah">
<input type="text" data-table="t_invoice_fee" data-field="x_jumlah" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->jumlah->EditValue ?>"<?php echo $t_invoice_fee->jumlah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_jumlah" class="form-group t_invoice_fee_jumlah">
<input type="text" data-table="t_invoice_fee" data-field="x_jumlah" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->jumlah->EditValue ?>"<?php echo $t_invoice_fee->jumlah->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_jumlah" class="t_invoice_fee_jumlah">
<span<?php echo $t_invoice_fee->jumlah->ViewAttributes() ?>>
<?php echo $t_invoice_fee->jumlah->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $t_invoice_fee->keterangan->CellAttributes() ?>>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_keterangan" class="form-group t_invoice_fee_keterangan">
<textarea data-table="t_invoice_fee" data-field="x_keterangan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->getPlaceHolder()) ?>"<?php echo $t_invoice_fee->keterangan->EditAttributes() ?>><?php echo $t_invoice_fee->keterangan->EditValue ?></textarea>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->OldValue) ?>">
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_keterangan" class="form-group t_invoice_fee_keterangan">
<textarea data-table="t_invoice_fee" data-field="x_keterangan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->getPlaceHolder()) ?>"<?php echo $t_invoice_fee->keterangan->EditAttributes() ?>><?php echo $t_invoice_fee->keterangan->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_invoice_fee_grid->RowCnt ?>_t_invoice_fee_keterangan" class="t_invoice_fee_keterangan">
<span<?php echo $t_invoice_fee->keterangan->ViewAttributes() ?>>
<?php echo $t_invoice_fee->keterangan->ListViewValue() ?></span>
</span>
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="ft_invoice_feegrid$x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->FormValue) ?>">
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="ft_invoice_feegrid$o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_invoice_fee_grid->ListOptions->Render("body", "right", $t_invoice_fee_grid->RowCnt);
?>
	</tr>
<?php if ($t_invoice_fee->RowType == EW_ROWTYPE_ADD || $t_invoice_fee->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_invoice_feegrid.UpdateOpts(<?php echo $t_invoice_fee_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_invoice_fee->CurrentAction <> "gridadd" || $t_invoice_fee->CurrentMode == "copy")
		if (!$t_invoice_fee_grid->Recordset->EOF) $t_invoice_fee_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_invoice_fee->CurrentMode == "add" || $t_invoice_fee->CurrentMode == "copy" || $t_invoice_fee->CurrentMode == "edit") {
		$t_invoice_fee_grid->RowIndex = '$rowindex$';
		$t_invoice_fee_grid->LoadDefaultValues();

		// Set row properties
		$t_invoice_fee->ResetAttrs();
		$t_invoice_fee->RowAttrs = array_merge($t_invoice_fee->RowAttrs, array('data-rowindex'=>$t_invoice_fee_grid->RowIndex, 'id'=>'r0_t_invoice_fee', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_invoice_fee->RowAttrs["class"], "ewTemplate");
		$t_invoice_fee->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_invoice_fee_grid->RenderRow();

		// Render list options
		$t_invoice_fee_grid->RenderListOptions();
		$t_invoice_fee_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_invoice_fee->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_invoice_fee_grid->ListOptions->Render("body", "left", $t_invoice_fee_grid->RowIndex);
?>
	<?php if ($t_invoice_fee->fee_id->Visible) { // fee_id ?>
		<td data-name="fee_id">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_fee_id" class="form-group t_invoice_fee_fee_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id"><?php echo (strval($t_invoice_fee->fee_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_invoice_fee->fee_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_invoice_fee->fee_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_invoice_fee->fee_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->CurrentValue ?>"<?php echo $t_invoice_fee->fee_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="s_x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo $t_invoice_fee->fee_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_fee_id" class="form-group t_invoice_fee_fee_id">
<span<?php echo $t_invoice_fee->fee_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->fee_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_fee_id" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_fee_id" value="<?php echo ew_HtmlEncode($t_invoice_fee->fee_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->harga->Visible) { // harga ?>
		<td data-name="harga">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_harga" class="form-group t_invoice_fee_harga">
<input type="text" data-table="t_invoice_fee" data-field="x_harga" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->harga->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->harga->EditValue ?>"<?php echo $t_invoice_fee->harga->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_harga" class="form-group t_invoice_fee_harga">
<span<?php echo $t_invoice_fee->harga->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->harga->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_harga" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_invoice_fee->harga->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->qty->Visible) { // qty ?>
		<td data-name="qty">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_qty" class="form-group t_invoice_fee_qty">
<input type="text" data-table="t_invoice_fee" data-field="x_qty" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->qty->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->qty->EditValue ?>"<?php echo $t_invoice_fee->qty->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_qty" class="form-group t_invoice_fee_qty">
<span<?php echo $t_invoice_fee->qty->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->qty->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_qty" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_invoice_fee->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->satuan->Visible) { // satuan ?>
		<td data-name="satuan">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_satuan" class="form-group t_invoice_fee_satuan">
<input type="text" data-table="t_invoice_fee" data-field="x_satuan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->satuan->EditValue ?>"<?php echo $t_invoice_fee->satuan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_satuan" class="form-group t_invoice_fee_satuan">
<span<?php echo $t_invoice_fee->satuan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->satuan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_satuan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_satuan" value="<?php echo ew_HtmlEncode($t_invoice_fee->satuan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->jumlah->Visible) { // jumlah ?>
		<td data-name="jumlah">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_jumlah" class="form-group t_invoice_fee_jumlah">
<input type="text" data-table="t_invoice_fee" data-field="x_jumlah" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->getPlaceHolder()) ?>" value="<?php echo $t_invoice_fee->jumlah->EditValue ?>"<?php echo $t_invoice_fee->jumlah->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_jumlah" class="form-group t_invoice_fee_jumlah">
<span<?php echo $t_invoice_fee->jumlah->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->jumlah->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_jumlah" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_jumlah" value="<?php echo ew_HtmlEncode($t_invoice_fee->jumlah->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_invoice_fee->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan">
<?php if ($t_invoice_fee->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_invoice_fee_keterangan" class="form-group t_invoice_fee_keterangan">
<textarea data-table="t_invoice_fee" data-field="x_keterangan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->getPlaceHolder()) ?>"<?php echo $t_invoice_fee->keterangan->EditAttributes() ?>><?php echo $t_invoice_fee->keterangan->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_invoice_fee_keterangan" class="form-group t_invoice_fee_keterangan">
<span<?php echo $t_invoice_fee->keterangan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_invoice_fee->keterangan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="x<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_invoice_fee" data-field="x_keterangan" name="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" id="o<?php echo $t_invoice_fee_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($t_invoice_fee->keterangan->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_invoice_fee_grid->ListOptions->Render("body", "right", $t_invoice_fee_grid->RowCnt);
?>
<script type="text/javascript">
ft_invoice_feegrid.UpdateOpts(<?php echo $t_invoice_fee_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_invoice_fee->CurrentMode == "add" || $t_invoice_fee->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_invoice_fee_grid->FormKeyCountName ?>" id="<?php echo $t_invoice_fee_grid->FormKeyCountName ?>" value="<?php echo $t_invoice_fee_grid->KeyCount ?>">
<?php echo $t_invoice_fee_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_invoice_fee->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_invoice_fee_grid->FormKeyCountName ?>" id="<?php echo $t_invoice_fee_grid->FormKeyCountName ?>" value="<?php echo $t_invoice_fee_grid->KeyCount ?>">
<?php echo $t_invoice_fee_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_invoice_fee->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_invoice_feegrid">
</div>
<?php

// Close recordset
if ($t_invoice_fee_grid->Recordset)
	$t_invoice_fee_grid->Recordset->Close();
?>
<?php if ($t_invoice_fee_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_invoice_fee_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_invoice_fee_grid->TotalRecs == 0 && $t_invoice_fee->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_invoice_fee_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_invoice_fee->Export == "") { ?>
<script type="text/javascript">
ft_invoice_feegrid.Init();
</script>
<?php } ?>
<?php
$t_invoice_fee_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_invoice_fee_grid->Page_Terminate();
?>
