<?php

// Global variable for table object
$v_invoice_fee = NULL;

//
// Table class for v_invoice_fee
//
class cv_invoice_fee extends cTable {
	var $nama;
	var $alamat;
	var $kota;
	var $kodepos;
	var $npwp;
	var $invoice_id;
	var $nomor;
	var $tanggal;
	var $no_order;
	var $no_referensi;
	var $kegiatan;
	var $no_sertifikat;
	var $keterangan;
	var $total;
	var $ppn;
	var $total_ppn;
	var $terbilang;
	var $terbayar;
	var $pasal23;
	var $no_kwitansi;
	var $jenis;
	var $harga;
	var $qty;
	var $satuan;
	var $jumlah;
	var $keterangan1;
	var $tgl_pelaksanaan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_invoice_fee';
		$this->TableName = 'v_invoice_fee';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`v_invoice_fee`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// nama
		$this->nama = new cField('v_invoice_fee', 'v_invoice_fee', 'x_nama', 'nama', '`nama`', '`nama`', 200, -1, FALSE, '`nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;

		// alamat
		$this->alamat = new cField('v_invoice_fee', 'v_invoice_fee', 'x_alamat', 'alamat', '`alamat`', '`alamat`', 201, -1, FALSE, '`alamat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->alamat->Sortable = TRUE; // Allow sort
		$this->fields['alamat'] = &$this->alamat;

		// kota
		$this->kota = new cField('v_invoice_fee', 'v_invoice_fee', 'x_kota', 'kota', '`kota`', '`kota`', 200, -1, FALSE, '`kota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kota->Sortable = TRUE; // Allow sort
		$this->fields['kota'] = &$this->kota;

		// kodepos
		$this->kodepos = new cField('v_invoice_fee', 'v_invoice_fee', 'x_kodepos', 'kodepos', '`kodepos`', '`kodepos`', 200, -1, FALSE, '`kodepos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kodepos->Sortable = TRUE; // Allow sort
		$this->fields['kodepos'] = &$this->kodepos;

		// npwp
		$this->npwp = new cField('v_invoice_fee', 'v_invoice_fee', 'x_npwp', 'npwp', '`npwp`', '`npwp`', 200, -1, FALSE, '`npwp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->npwp->Sortable = TRUE; // Allow sort
		$this->fields['npwp'] = &$this->npwp;

		// invoice_id
		$this->invoice_id = new cField('v_invoice_fee', 'v_invoice_fee', 'x_invoice_id', 'invoice_id', '`invoice_id`', '`invoice_id`', 3, -1, FALSE, '`invoice_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->invoice_id->Sortable = TRUE; // Allow sort
		$this->invoice_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] = &$this->invoice_id;

		// nomor
		$this->nomor = new cField('v_invoice_fee', 'v_invoice_fee', 'x_nomor', 'nomor', '`nomor`', '`nomor`', 200, -1, FALSE, '`nomor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nomor->Sortable = TRUE; // Allow sort
		$this->fields['nomor'] = &$this->nomor;

		// tanggal
		$this->tanggal = new cField('v_invoice_fee', 'v_invoice_fee', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 133, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// no_order
		$this->no_order = new cField('v_invoice_fee', 'v_invoice_fee', 'x_no_order', 'no_order', '`no_order`', '`no_order`', 200, -1, FALSE, '`no_order`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_order->Sortable = TRUE; // Allow sort
		$this->fields['no_order'] = &$this->no_order;

		// no_referensi
		$this->no_referensi = new cField('v_invoice_fee', 'v_invoice_fee', 'x_no_referensi', 'no_referensi', '`no_referensi`', '`no_referensi`', 200, -1, FALSE, '`no_referensi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_referensi->Sortable = TRUE; // Allow sort
		$this->fields['no_referensi'] = &$this->no_referensi;

		// kegiatan
		$this->kegiatan = new cField('v_invoice_fee', 'v_invoice_fee', 'x_kegiatan', 'kegiatan', '`kegiatan`', '`kegiatan`', 201, -1, FALSE, '`kegiatan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->kegiatan->Sortable = TRUE; // Allow sort
		$this->fields['kegiatan'] = &$this->kegiatan;

		// no_sertifikat
		$this->no_sertifikat = new cField('v_invoice_fee', 'v_invoice_fee', 'x_no_sertifikat', 'no_sertifikat', '`no_sertifikat`', '`no_sertifikat`', 201, -1, FALSE, '`no_sertifikat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->no_sertifikat->Sortable = TRUE; // Allow sort
		$this->fields['no_sertifikat'] = &$this->no_sertifikat;

		// keterangan
		$this->keterangan = new cField('v_invoice_fee', 'v_invoice_fee', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 201, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// total
		$this->total = new cField('v_invoice_fee', 'v_invoice_fee', 'x_total', 'total', '`total`', '`total`', 4, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;

		// ppn
		$this->ppn = new cField('v_invoice_fee', 'v_invoice_fee', 'x_ppn', 'ppn', '`ppn`', '`ppn`', 3, -1, FALSE, '`ppn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ppn->Sortable = TRUE; // Allow sort
		$this->ppn->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ppn'] = &$this->ppn;

		// total_ppn
		$this->total_ppn = new cField('v_invoice_fee', 'v_invoice_fee', 'x_total_ppn', 'total_ppn', '`total_ppn`', '`total_ppn`', 4, -1, FALSE, '`total_ppn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_ppn->Sortable = TRUE; // Allow sort
		$this->total_ppn->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total_ppn'] = &$this->total_ppn;

		// terbilang
		$this->terbilang = new cField('v_invoice_fee', 'v_invoice_fee', 'x_terbilang', 'terbilang', '`terbilang`', '`terbilang`', 201, -1, FALSE, '`terbilang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->terbilang->Sortable = TRUE; // Allow sort
		$this->fields['terbilang'] = &$this->terbilang;

		// terbayar
		$this->terbayar = new cField('v_invoice_fee', 'v_invoice_fee', 'x_terbayar', 'terbayar', '`terbayar`', '`terbayar`', 16, -1, FALSE, '`terbayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->terbayar->Sortable = TRUE; // Allow sort
		$this->terbayar->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['terbayar'] = &$this->terbayar;

		// pasal23
		$this->pasal23 = new cField('v_invoice_fee', 'v_invoice_fee', 'x_pasal23', 'pasal23', '`pasal23`', '`pasal23`', 16, -1, FALSE, '`pasal23`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pasal23->Sortable = TRUE; // Allow sort
		$this->pasal23->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pasal23'] = &$this->pasal23;

		// no_kwitansi
		$this->no_kwitansi = new cField('v_invoice_fee', 'v_invoice_fee', 'x_no_kwitansi', 'no_kwitansi', '`no_kwitansi`', '`no_kwitansi`', 200, -1, FALSE, '`no_kwitansi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_kwitansi->Sortable = TRUE; // Allow sort
		$this->fields['no_kwitansi'] = &$this->no_kwitansi;

		// jenis
		$this->jenis = new cField('v_invoice_fee', 'v_invoice_fee', 'x_jenis', 'jenis', '`jenis`', '`jenis`', 201, -1, FALSE, '`jenis`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->jenis->Sortable = TRUE; // Allow sort
		$this->fields['jenis'] = &$this->jenis;

		// harga
		$this->harga = new cField('v_invoice_fee', 'v_invoice_fee', 'x_harga', 'harga', '`harga`', '`harga`', 4, -1, FALSE, '`harga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->harga->Sortable = TRUE; // Allow sort
		$this->harga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['harga'] = &$this->harga;

		// qty
		$this->qty = new cField('v_invoice_fee', 'v_invoice_fee', 'x_qty', 'qty', '`qty`', '`qty`', 131, -1, FALSE, '`qty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->qty->Sortable = TRUE; // Allow sort
		$this->qty->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['qty'] = &$this->qty;

		// satuan
		$this->satuan = new cField('v_invoice_fee', 'v_invoice_fee', 'x_satuan', 'satuan', '`satuan`', '`satuan`', 200, -1, FALSE, '`satuan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->satuan->Sortable = TRUE; // Allow sort
		$this->fields['satuan'] = &$this->satuan;

		// jumlah
		$this->jumlah = new cField('v_invoice_fee', 'v_invoice_fee', 'x_jumlah', 'jumlah', '`jumlah`', '`jumlah`', 4, -1, FALSE, '`jumlah`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jumlah->Sortable = TRUE; // Allow sort
		$this->jumlah->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jumlah'] = &$this->jumlah;

		// keterangan1
		$this->keterangan1 = new cField('v_invoice_fee', 'v_invoice_fee', 'x_keterangan1', 'keterangan1', '`keterangan1`', '`keterangan1`', 201, -1, FALSE, '`keterangan1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->keterangan1->Sortable = TRUE; // Allow sort
		$this->fields['keterangan1'] = &$this->keterangan1;

		// tgl_pelaksanaan
		$this->tgl_pelaksanaan = new cField('v_invoice_fee', 'v_invoice_fee', 'x_tgl_pelaksanaan', 'tgl_pelaksanaan', '`tgl_pelaksanaan`', '`tgl_pelaksanaan`', 201, -1, FALSE, '`tgl_pelaksanaan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->tgl_pelaksanaan->Sortable = TRUE; // Allow sort
		$this->fields['tgl_pelaksanaan'] = &$this->tgl_pelaksanaan;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_invoice_fee`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('invoice_id', $rs))
				ew_AddFilter($where, ew_QuotedName('invoice_id', $this->DBID) . '=' . ew_QuotedValue($rs['invoice_id'], $this->invoice_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`invoice_id` = @invoice_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->invoice_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@invoice_id@", ew_AdjustSql($this->invoice_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "v_invoice_feelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_invoice_feelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("v_invoice_feeview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("v_invoice_feeview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "v_invoice_feeadd.php?" . $this->UrlParm($parm);
		else
			$url = "v_invoice_feeadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("v_invoice_feeedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("v_invoice_feeadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_invoice_feedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "invoice_id:" . ew_VarToJson($this->invoice_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->invoice_id->CurrentValue)) {
			$sUrl .= "invoice_id=" . urlencode($this->invoice_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["invoice_id"]))
				$arKeys[] = ew_StripSlashes($_POST["invoice_id"]);
			elseif (isset($_GET["invoice_id"]))
				$arKeys[] = ew_StripSlashes($_GET["invoice_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->invoice_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->nama->setDbValue($rs->fields('nama'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->kota->setDbValue($rs->fields('kota'));
		$this->kodepos->setDbValue($rs->fields('kodepos'));
		$this->npwp->setDbValue($rs->fields('npwp'));
		$this->invoice_id->setDbValue($rs->fields('invoice_id'));
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->no_order->setDbValue($rs->fields('no_order'));
		$this->no_referensi->setDbValue($rs->fields('no_referensi'));
		$this->kegiatan->setDbValue($rs->fields('kegiatan'));
		$this->no_sertifikat->setDbValue($rs->fields('no_sertifikat'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->total->setDbValue($rs->fields('total'));
		$this->ppn->setDbValue($rs->fields('ppn'));
		$this->total_ppn->setDbValue($rs->fields('total_ppn'));
		$this->terbilang->setDbValue($rs->fields('terbilang'));
		$this->terbayar->setDbValue($rs->fields('terbayar'));
		$this->pasal23->setDbValue($rs->fields('pasal23'));
		$this->no_kwitansi->setDbValue($rs->fields('no_kwitansi'));
		$this->jenis->setDbValue($rs->fields('jenis'));
		$this->harga->setDbValue($rs->fields('harga'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan->setDbValue($rs->fields('satuan'));
		$this->jumlah->setDbValue($rs->fields('jumlah'));
		$this->keterangan1->setDbValue($rs->fields('keterangan1'));
		$this->tgl_pelaksanaan->setDbValue($rs->fields('tgl_pelaksanaan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// nama
		// alamat
		// kota
		// kodepos
		// npwp
		// invoice_id
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
		// pasal23
		// no_kwitansi
		// jenis
		// harga
		// qty
		// satuan
		// jumlah
		// keterangan1
		// tgl_pelaksanaan
		// nama

		$this->nama->ViewValue = $this->nama->CurrentValue;
		$this->nama->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// kota
		$this->kota->ViewValue = $this->kota->CurrentValue;
		$this->kota->ViewCustomAttributes = "";

		// kodepos
		$this->kodepos->ViewValue = $this->kodepos->CurrentValue;
		$this->kodepos->ViewCustomAttributes = "";

		// npwp
		$this->npwp->ViewValue = $this->npwp->CurrentValue;
		$this->npwp->ViewCustomAttributes = "";

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// nomor
		$this->nomor->ViewValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// tanggal
		$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
		$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 0);
		$this->tanggal->ViewCustomAttributes = "";

		// no_order
		$this->no_order->ViewValue = $this->no_order->CurrentValue;
		$this->no_order->ViewCustomAttributes = "";

		// no_referensi
		$this->no_referensi->ViewValue = $this->no_referensi->CurrentValue;
		$this->no_referensi->ViewCustomAttributes = "";

		// kegiatan
		$this->kegiatan->ViewValue = $this->kegiatan->CurrentValue;
		$this->kegiatan->ViewCustomAttributes = "";

		// no_sertifikat
		$this->no_sertifikat->ViewValue = $this->no_sertifikat->CurrentValue;
		$this->no_sertifikat->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// ppn
		$this->ppn->ViewValue = $this->ppn->CurrentValue;
		$this->ppn->ViewCustomAttributes = "";

		// total_ppn
		$this->total_ppn->ViewValue = $this->total_ppn->CurrentValue;
		$this->total_ppn->ViewCustomAttributes = "";

		// terbilang
		$this->terbilang->ViewValue = $this->terbilang->CurrentValue;
		$this->terbilang->ViewCustomAttributes = "";

		// terbayar
		$this->terbayar->ViewValue = $this->terbayar->CurrentValue;
		$this->terbayar->ViewCustomAttributes = "";

		// pasal23
		$this->pasal23->ViewValue = $this->pasal23->CurrentValue;
		$this->pasal23->ViewCustomAttributes = "";

		// no_kwitansi
		$this->no_kwitansi->ViewValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->ViewCustomAttributes = "";

		// jenis
		$this->jenis->ViewValue = $this->jenis->CurrentValue;
		$this->jenis->ViewCustomAttributes = "";

		// harga
		$this->harga->ViewValue = $this->harga->CurrentValue;
		$this->harga->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewCustomAttributes = "";

		// satuan
		$this->satuan->ViewValue = $this->satuan->CurrentValue;
		$this->satuan->ViewCustomAttributes = "";

		// jumlah
		$this->jumlah->ViewValue = $this->jumlah->CurrentValue;
		$this->jumlah->ViewCustomAttributes = "";

		// keterangan1
		$this->keterangan1->ViewValue = $this->keterangan1->CurrentValue;
		$this->keterangan1->ViewCustomAttributes = "";

		// tgl_pelaksanaan
		$this->tgl_pelaksanaan->ViewValue = $this->tgl_pelaksanaan->CurrentValue;
		$this->tgl_pelaksanaan->ViewCustomAttributes = "";

		// nama
		$this->nama->LinkCustomAttributes = "";
		$this->nama->HrefValue = "";
		$this->nama->TooltipValue = "";

		// alamat
		$this->alamat->LinkCustomAttributes = "";
		$this->alamat->HrefValue = "";
		$this->alamat->TooltipValue = "";

		// kota
		$this->kota->LinkCustomAttributes = "";
		$this->kota->HrefValue = "";
		$this->kota->TooltipValue = "";

		// kodepos
		$this->kodepos->LinkCustomAttributes = "";
		$this->kodepos->HrefValue = "";
		$this->kodepos->TooltipValue = "";

		// npwp
		$this->npwp->LinkCustomAttributes = "";
		$this->npwp->HrefValue = "";
		$this->npwp->TooltipValue = "";

		// invoice_id
		$this->invoice_id->LinkCustomAttributes = "";
		$this->invoice_id->HrefValue = "";
		$this->invoice_id->TooltipValue = "";

		// nomor
		$this->nomor->LinkCustomAttributes = "";
		$this->nomor->HrefValue = "";
		$this->nomor->TooltipValue = "";

		// tanggal
		$this->tanggal->LinkCustomAttributes = "";
		$this->tanggal->HrefValue = "";
		$this->tanggal->TooltipValue = "";

		// no_order
		$this->no_order->LinkCustomAttributes = "";
		$this->no_order->HrefValue = "";
		$this->no_order->TooltipValue = "";

		// no_referensi
		$this->no_referensi->LinkCustomAttributes = "";
		$this->no_referensi->HrefValue = "";
		$this->no_referensi->TooltipValue = "";

		// kegiatan
		$this->kegiatan->LinkCustomAttributes = "";
		$this->kegiatan->HrefValue = "";
		$this->kegiatan->TooltipValue = "";

		// no_sertifikat
		$this->no_sertifikat->LinkCustomAttributes = "";
		$this->no_sertifikat->HrefValue = "";
		$this->no_sertifikat->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

		// ppn
		$this->ppn->LinkCustomAttributes = "";
		$this->ppn->HrefValue = "";
		$this->ppn->TooltipValue = "";

		// total_ppn
		$this->total_ppn->LinkCustomAttributes = "";
		$this->total_ppn->HrefValue = "";
		$this->total_ppn->TooltipValue = "";

		// terbilang
		$this->terbilang->LinkCustomAttributes = "";
		$this->terbilang->HrefValue = "";
		$this->terbilang->TooltipValue = "";

		// terbayar
		$this->terbayar->LinkCustomAttributes = "";
		$this->terbayar->HrefValue = "";
		$this->terbayar->TooltipValue = "";

		// pasal23
		$this->pasal23->LinkCustomAttributes = "";
		$this->pasal23->HrefValue = "";
		$this->pasal23->TooltipValue = "";

		// no_kwitansi
		$this->no_kwitansi->LinkCustomAttributes = "";
		$this->no_kwitansi->HrefValue = "";
		$this->no_kwitansi->TooltipValue = "";

		// jenis
		$this->jenis->LinkCustomAttributes = "";
		$this->jenis->HrefValue = "";
		$this->jenis->TooltipValue = "";

		// harga
		$this->harga->LinkCustomAttributes = "";
		$this->harga->HrefValue = "";
		$this->harga->TooltipValue = "";

		// qty
		$this->qty->LinkCustomAttributes = "";
		$this->qty->HrefValue = "";
		$this->qty->TooltipValue = "";

		// satuan
		$this->satuan->LinkCustomAttributes = "";
		$this->satuan->HrefValue = "";
		$this->satuan->TooltipValue = "";

		// jumlah
		$this->jumlah->LinkCustomAttributes = "";
		$this->jumlah->HrefValue = "";
		$this->jumlah->TooltipValue = "";

		// keterangan1
		$this->keterangan1->LinkCustomAttributes = "";
		$this->keterangan1->HrefValue = "";
		$this->keterangan1->TooltipValue = "";

		// tgl_pelaksanaan
		$this->tgl_pelaksanaan->LinkCustomAttributes = "";
		$this->tgl_pelaksanaan->HrefValue = "";
		$this->tgl_pelaksanaan->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// nama
		$this->nama->EditAttrs["class"] = "form-control";
		$this->nama->EditCustomAttributes = "";
		$this->nama->EditValue = $this->nama->CurrentValue;
		$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

		// alamat
		$this->alamat->EditAttrs["class"] = "form-control";
		$this->alamat->EditCustomAttributes = "";
		$this->alamat->EditValue = $this->alamat->CurrentValue;
		$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

		// kota
		$this->kota->EditAttrs["class"] = "form-control";
		$this->kota->EditCustomAttributes = "";
		$this->kota->EditValue = $this->kota->CurrentValue;
		$this->kota->PlaceHolder = ew_RemoveHtml($this->kota->FldCaption());

		// kodepos
		$this->kodepos->EditAttrs["class"] = "form-control";
		$this->kodepos->EditCustomAttributes = "";
		$this->kodepos->EditValue = $this->kodepos->CurrentValue;
		$this->kodepos->PlaceHolder = ew_RemoveHtml($this->kodepos->FldCaption());

		// npwp
		$this->npwp->EditAttrs["class"] = "form-control";
		$this->npwp->EditCustomAttributes = "";
		$this->npwp->EditValue = $this->npwp->CurrentValue;
		$this->npwp->PlaceHolder = ew_RemoveHtml($this->npwp->FldCaption());

		// invoice_id
		$this->invoice_id->EditAttrs["class"] = "form-control";
		$this->invoice_id->EditCustomAttributes = "";
		$this->invoice_id->EditValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// nomor
		$this->nomor->EditAttrs["class"] = "form-control";
		$this->nomor->EditCustomAttributes = "";
		$this->nomor->EditValue = $this->nomor->CurrentValue;
		$this->nomor->PlaceHolder = ew_RemoveHtml($this->nomor->FldCaption());

		// tanggal
		$this->tanggal->EditAttrs["class"] = "form-control";
		$this->tanggal->EditCustomAttributes = "";
		$this->tanggal->EditValue = ew_FormatDateTime($this->tanggal->CurrentValue, 8);
		$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

		// no_order
		$this->no_order->EditAttrs["class"] = "form-control";
		$this->no_order->EditCustomAttributes = "";
		$this->no_order->EditValue = $this->no_order->CurrentValue;
		$this->no_order->PlaceHolder = ew_RemoveHtml($this->no_order->FldCaption());

		// no_referensi
		$this->no_referensi->EditAttrs["class"] = "form-control";
		$this->no_referensi->EditCustomAttributes = "";
		$this->no_referensi->EditValue = $this->no_referensi->CurrentValue;
		$this->no_referensi->PlaceHolder = ew_RemoveHtml($this->no_referensi->FldCaption());

		// kegiatan
		$this->kegiatan->EditAttrs["class"] = "form-control";
		$this->kegiatan->EditCustomAttributes = "";
		$this->kegiatan->EditValue = $this->kegiatan->CurrentValue;
		$this->kegiatan->PlaceHolder = ew_RemoveHtml($this->kegiatan->FldCaption());

		// no_sertifikat
		$this->no_sertifikat->EditAttrs["class"] = "form-control";
		$this->no_sertifikat->EditCustomAttributes = "";
		$this->no_sertifikat->EditValue = $this->no_sertifikat->CurrentValue;
		$this->no_sertifikat->PlaceHolder = ew_RemoveHtml($this->no_sertifikat->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = $this->total->CurrentValue;
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

		// ppn
		$this->ppn->EditAttrs["class"] = "form-control";
		$this->ppn->EditCustomAttributes = "";
		$this->ppn->EditValue = $this->ppn->CurrentValue;
		$this->ppn->PlaceHolder = ew_RemoveHtml($this->ppn->FldCaption());

		// total_ppn
		$this->total_ppn->EditAttrs["class"] = "form-control";
		$this->total_ppn->EditCustomAttributes = "";
		$this->total_ppn->EditValue = $this->total_ppn->CurrentValue;
		$this->total_ppn->PlaceHolder = ew_RemoveHtml($this->total_ppn->FldCaption());
		if (strval($this->total_ppn->EditValue) <> "" && is_numeric($this->total_ppn->EditValue)) $this->total_ppn->EditValue = ew_FormatNumber($this->total_ppn->EditValue, -2, -1, -2, 0);

		// terbilang
		$this->terbilang->EditAttrs["class"] = "form-control";
		$this->terbilang->EditCustomAttributes = "";
		$this->terbilang->EditValue = $this->terbilang->CurrentValue;
		$this->terbilang->PlaceHolder = ew_RemoveHtml($this->terbilang->FldCaption());

		// terbayar
		$this->terbayar->EditAttrs["class"] = "form-control";
		$this->terbayar->EditCustomAttributes = "";
		$this->terbayar->EditValue = $this->terbayar->CurrentValue;
		$this->terbayar->PlaceHolder = ew_RemoveHtml($this->terbayar->FldCaption());

		// pasal23
		$this->pasal23->EditAttrs["class"] = "form-control";
		$this->pasal23->EditCustomAttributes = "";
		$this->pasal23->EditValue = $this->pasal23->CurrentValue;
		$this->pasal23->PlaceHolder = ew_RemoveHtml($this->pasal23->FldCaption());

		// no_kwitansi
		$this->no_kwitansi->EditAttrs["class"] = "form-control";
		$this->no_kwitansi->EditCustomAttributes = "";
		$this->no_kwitansi->EditValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->PlaceHolder = ew_RemoveHtml($this->no_kwitansi->FldCaption());

		// jenis
		$this->jenis->EditAttrs["class"] = "form-control";
		$this->jenis->EditCustomAttributes = "";
		$this->jenis->EditValue = $this->jenis->CurrentValue;
		$this->jenis->PlaceHolder = ew_RemoveHtml($this->jenis->FldCaption());

		// harga
		$this->harga->EditAttrs["class"] = "form-control";
		$this->harga->EditCustomAttributes = "";
		$this->harga->EditValue = $this->harga->CurrentValue;
		$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
		if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -1, -2, 0);

		// qty
		$this->qty->EditAttrs["class"] = "form-control";
		$this->qty->EditCustomAttributes = "";
		$this->qty->EditValue = $this->qty->CurrentValue;
		$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
		if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -1, -2, 0);

		// satuan
		$this->satuan->EditAttrs["class"] = "form-control";
		$this->satuan->EditCustomAttributes = "";
		$this->satuan->EditValue = $this->satuan->CurrentValue;
		$this->satuan->PlaceHolder = ew_RemoveHtml($this->satuan->FldCaption());

		// jumlah
		$this->jumlah->EditAttrs["class"] = "form-control";
		$this->jumlah->EditCustomAttributes = "";
		$this->jumlah->EditValue = $this->jumlah->CurrentValue;
		$this->jumlah->PlaceHolder = ew_RemoveHtml($this->jumlah->FldCaption());
		if (strval($this->jumlah->EditValue) <> "" && is_numeric($this->jumlah->EditValue)) $this->jumlah->EditValue = ew_FormatNumber($this->jumlah->EditValue, -2, -1, -2, 0);

		// keterangan1
		$this->keterangan1->EditAttrs["class"] = "form-control";
		$this->keterangan1->EditCustomAttributes = "";
		$this->keterangan1->EditValue = $this->keterangan1->CurrentValue;
		$this->keterangan1->PlaceHolder = ew_RemoveHtml($this->keterangan1->FldCaption());

		// tgl_pelaksanaan
		$this->tgl_pelaksanaan->EditAttrs["class"] = "form-control";
		$this->tgl_pelaksanaan->EditCustomAttributes = "";
		$this->tgl_pelaksanaan->EditValue = $this->tgl_pelaksanaan->CurrentValue;
		$this->tgl_pelaksanaan->PlaceHolder = ew_RemoveHtml($this->tgl_pelaksanaan->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->kota->Exportable) $Doc->ExportCaption($this->kota);
					if ($this->kodepos->Exportable) $Doc->ExportCaption($this->kodepos);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->invoice_id->Exportable) $Doc->ExportCaption($this->invoice_id);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->no_order->Exportable) $Doc->ExportCaption($this->no_order);
					if ($this->no_referensi->Exportable) $Doc->ExportCaption($this->no_referensi);
					if ($this->kegiatan->Exportable) $Doc->ExportCaption($this->kegiatan);
					if ($this->no_sertifikat->Exportable) $Doc->ExportCaption($this->no_sertifikat);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->ppn->Exportable) $Doc->ExportCaption($this->ppn);
					if ($this->total_ppn->Exportable) $Doc->ExportCaption($this->total_ppn);
					if ($this->terbilang->Exportable) $Doc->ExportCaption($this->terbilang);
					if ($this->terbayar->Exportable) $Doc->ExportCaption($this->terbayar);
					if ($this->pasal23->Exportable) $Doc->ExportCaption($this->pasal23);
					if ($this->no_kwitansi->Exportable) $Doc->ExportCaption($this->no_kwitansi);
					if ($this->jenis->Exportable) $Doc->ExportCaption($this->jenis);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan->Exportable) $Doc->ExportCaption($this->satuan);
					if ($this->jumlah->Exportable) $Doc->ExportCaption($this->jumlah);
					if ($this->keterangan1->Exportable) $Doc->ExportCaption($this->keterangan1);
					if ($this->tgl_pelaksanaan->Exportable) $Doc->ExportCaption($this->tgl_pelaksanaan);
				} else {
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->kota->Exportable) $Doc->ExportCaption($this->kota);
					if ($this->kodepos->Exportable) $Doc->ExportCaption($this->kodepos);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->invoice_id->Exportable) $Doc->ExportCaption($this->invoice_id);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->no_order->Exportable) $Doc->ExportCaption($this->no_order);
					if ($this->no_referensi->Exportable) $Doc->ExportCaption($this->no_referensi);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->ppn->Exportable) $Doc->ExportCaption($this->ppn);
					if ($this->total_ppn->Exportable) $Doc->ExportCaption($this->total_ppn);
					if ($this->terbayar->Exportable) $Doc->ExportCaption($this->terbayar);
					if ($this->pasal23->Exportable) $Doc->ExportCaption($this->pasal23);
					if ($this->no_kwitansi->Exportable) $Doc->ExportCaption($this->no_kwitansi);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan->Exportable) $Doc->ExportCaption($this->satuan);
					if ($this->jumlah->Exportable) $Doc->ExportCaption($this->jumlah);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->kota->Exportable) $Doc->ExportField($this->kota);
						if ($this->kodepos->Exportable) $Doc->ExportField($this->kodepos);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->invoice_id->Exportable) $Doc->ExportField($this->invoice_id);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->no_order->Exportable) $Doc->ExportField($this->no_order);
						if ($this->no_referensi->Exportable) $Doc->ExportField($this->no_referensi);
						if ($this->kegiatan->Exportable) $Doc->ExportField($this->kegiatan);
						if ($this->no_sertifikat->Exportable) $Doc->ExportField($this->no_sertifikat);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->ppn->Exportable) $Doc->ExportField($this->ppn);
						if ($this->total_ppn->Exportable) $Doc->ExportField($this->total_ppn);
						if ($this->terbilang->Exportable) $Doc->ExportField($this->terbilang);
						if ($this->terbayar->Exportable) $Doc->ExportField($this->terbayar);
						if ($this->pasal23->Exportable) $Doc->ExportField($this->pasal23);
						if ($this->no_kwitansi->Exportable) $Doc->ExportField($this->no_kwitansi);
						if ($this->jenis->Exportable) $Doc->ExportField($this->jenis);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan->Exportable) $Doc->ExportField($this->satuan);
						if ($this->jumlah->Exportable) $Doc->ExportField($this->jumlah);
						if ($this->keterangan1->Exportable) $Doc->ExportField($this->keterangan1);
						if ($this->tgl_pelaksanaan->Exportable) $Doc->ExportField($this->tgl_pelaksanaan);
					} else {
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->kota->Exportable) $Doc->ExportField($this->kota);
						if ($this->kodepos->Exportable) $Doc->ExportField($this->kodepos);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->invoice_id->Exportable) $Doc->ExportField($this->invoice_id);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->no_order->Exportable) $Doc->ExportField($this->no_order);
						if ($this->no_referensi->Exportable) $Doc->ExportField($this->no_referensi);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->ppn->Exportable) $Doc->ExportField($this->ppn);
						if ($this->total_ppn->Exportable) $Doc->ExportField($this->total_ppn);
						if ($this->terbayar->Exportable) $Doc->ExportField($this->terbayar);
						if ($this->pasal23->Exportable) $Doc->ExportField($this->pasal23);
						if ($this->no_kwitansi->Exportable) $Doc->ExportField($this->no_kwitansi);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan->Exportable) $Doc->ExportField($this->satuan);
						if ($this->jumlah->Exportable) $Doc->ExportField($this->jumlah);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
