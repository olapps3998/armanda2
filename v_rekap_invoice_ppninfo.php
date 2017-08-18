<?php

// Global variable for table object
$v_rekap_invoice_ppn = NULL;

//
// Table class for v_rekap_invoice_ppn
//
class cv_rekap_invoice_ppn extends cTable {
	var $tanggal;
	var $nama;
	var $no_kwitansi;
	var $nomor;
	var $no_referensi;
	var $nilai_ppn;
	var $total_ppn;
	var $invoice_id;
	var $periode;
	var $tanggal_short;
	var $periode_short;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'v_rekap_invoice_ppn';
		$this->TableName = 'v_rekap_invoice_ppn';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`v_rekap_invoice_ppn`";
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

		// tanggal
		$this->tanggal = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 133, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// nama
		$this->nama = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_nama', 'nama', '`nama`', '`nama`', 200, -1, FALSE, '`nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;

		// no_kwitansi
		$this->no_kwitansi = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_no_kwitansi', 'no_kwitansi', '`no_kwitansi`', '`no_kwitansi`', 200, -1, FALSE, '`no_kwitansi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_kwitansi->Sortable = TRUE; // Allow sort
		$this->fields['no_kwitansi'] = &$this->no_kwitansi;

		// nomor
		$this->nomor = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_nomor', 'nomor', '`nomor`', '`nomor`', 200, -1, FALSE, '`nomor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nomor->Sortable = TRUE; // Allow sort
		$this->fields['nomor'] = &$this->nomor;

		// no_referensi
		$this->no_referensi = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_no_referensi', 'no_referensi', '`no_referensi`', '`no_referensi`', 200, -1, FALSE, '`no_referensi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_referensi->Sortable = TRUE; // Allow sort
		$this->fields['no_referensi'] = &$this->no_referensi;

		// nilai_ppn
		$this->nilai_ppn = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_nilai_ppn', 'nilai_ppn', '`nilai_ppn`', '`nilai_ppn`', 5, -1, FALSE, '`nilai_ppn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nilai_ppn->Sortable = TRUE; // Allow sort
		$this->nilai_ppn->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['nilai_ppn'] = &$this->nilai_ppn;

		// total_ppn
		$this->total_ppn = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_total_ppn', 'total_ppn', '`total_ppn`', '`total_ppn`', 4, -1, FALSE, '`total_ppn`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_ppn->Sortable = TRUE; // Allow sort
		$this->total_ppn->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total_ppn'] = &$this->total_ppn;

		// invoice_id
		$this->invoice_id = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_invoice_id', 'invoice_id', '`invoice_id`', '`invoice_id`', 3, -1, FALSE, '`invoice_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->invoice_id->Sortable = TRUE; // Allow sort
		$this->invoice_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] = &$this->invoice_id;

		// periode
		$this->periode = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_periode', 'periode', '`periode`', ew_CastDateFieldForLike('`periode`', 0, "DB"), 133, 0, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->periode->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['periode'] = &$this->periode;

		// tanggal_short
		$this->tanggal_short = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_tanggal_short', 'tanggal_short', '`tanggal_short`', '`tanggal_short`', 200, -1, FALSE, '`tanggal_short`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal_short->Sortable = TRUE; // Allow sort
		$this->fields['tanggal_short'] = &$this->tanggal_short;

		// periode_short
		$this->periode_short = new cField('v_rekap_invoice_ppn', 'v_rekap_invoice_ppn', 'x_periode_short', 'periode_short', '`periode_short`', '`periode_short`', 200, -1, FALSE, '`periode_short`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode_short->Sortable = TRUE; // Allow sort
		$this->fields['periode_short'] = &$this->periode_short;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_rekap_invoice_ppn`";
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
			return "v_rekap_invoice_ppnlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_rekap_invoice_ppnlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("v_rekap_invoice_ppnview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("v_rekap_invoice_ppnview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "v_rekap_invoice_ppnadd.php?" . $this->UrlParm($parm);
		else
			$url = "v_rekap_invoice_ppnadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("v_rekap_invoice_ppnedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("v_rekap_invoice_ppnadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_rekap_invoice_ppndelete.php", $this->UrlParm());
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
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->nama->setDbValue($rs->fields('nama'));
		$this->no_kwitansi->setDbValue($rs->fields('no_kwitansi'));
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->no_referensi->setDbValue($rs->fields('no_referensi'));
		$this->nilai_ppn->setDbValue($rs->fields('nilai_ppn'));
		$this->total_ppn->setDbValue($rs->fields('total_ppn'));
		$this->invoice_id->setDbValue($rs->fields('invoice_id'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->tanggal_short->setDbValue($rs->fields('tanggal_short'));
		$this->periode_short->setDbValue($rs->fields('periode_short'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// tanggal
		// nama
		// no_kwitansi
		// nomor
		// no_referensi
		// nilai_ppn
		// total_ppn
		// invoice_id
		// periode
		// tanggal_short
		// periode_short
		// tanggal

		$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
		$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 0);
		$this->tanggal->ViewCustomAttributes = "";

		// nama
		$this->nama->ViewValue = $this->nama->CurrentValue;
		$this->nama->ViewCustomAttributes = "";

		// no_kwitansi
		$this->no_kwitansi->ViewValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->ViewCustomAttributes = "";

		// nomor
		$this->nomor->ViewValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// no_referensi
		$this->no_referensi->ViewValue = $this->no_referensi->CurrentValue;
		$this->no_referensi->ViewCustomAttributes = "";

		// nilai_ppn
		$this->nilai_ppn->ViewValue = $this->nilai_ppn->CurrentValue;
		$this->nilai_ppn->ViewCustomAttributes = "";

		// total_ppn
		$this->total_ppn->ViewValue = $this->total_ppn->CurrentValue;
		$this->total_ppn->ViewCustomAttributes = "";

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewValue = ew_FormatDateTime($this->periode->ViewValue, 0);
		$this->periode->ViewCustomAttributes = "";

		// tanggal_short
		$this->tanggal_short->ViewValue = $this->tanggal_short->CurrentValue;
		$this->tanggal_short->ViewCustomAttributes = "";

		// periode_short
		$this->periode_short->ViewValue = $this->periode_short->CurrentValue;
		$this->periode_short->ViewCustomAttributes = "";

		// tanggal
		$this->tanggal->LinkCustomAttributes = "";
		$this->tanggal->HrefValue = "";
		$this->tanggal->TooltipValue = "";

		// nama
		$this->nama->LinkCustomAttributes = "";
		$this->nama->HrefValue = "";
		$this->nama->TooltipValue = "";

		// no_kwitansi
		$this->no_kwitansi->LinkCustomAttributes = "";
		$this->no_kwitansi->HrefValue = "";
		$this->no_kwitansi->TooltipValue = "";

		// nomor
		$this->nomor->LinkCustomAttributes = "";
		$this->nomor->HrefValue = "";
		$this->nomor->TooltipValue = "";

		// no_referensi
		$this->no_referensi->LinkCustomAttributes = "";
		$this->no_referensi->HrefValue = "";
		$this->no_referensi->TooltipValue = "";

		// nilai_ppn
		$this->nilai_ppn->LinkCustomAttributes = "";
		$this->nilai_ppn->HrefValue = "";
		$this->nilai_ppn->TooltipValue = "";

		// total_ppn
		$this->total_ppn->LinkCustomAttributes = "";
		$this->total_ppn->HrefValue = "";
		$this->total_ppn->TooltipValue = "";

		// invoice_id
		$this->invoice_id->LinkCustomAttributes = "";
		$this->invoice_id->HrefValue = "";
		$this->invoice_id->TooltipValue = "";

		// periode
		$this->periode->LinkCustomAttributes = "";
		$this->periode->HrefValue = "";
		$this->periode->TooltipValue = "";

		// tanggal_short
		$this->tanggal_short->LinkCustomAttributes = "";
		$this->tanggal_short->HrefValue = "";
		$this->tanggal_short->TooltipValue = "";

		// periode_short
		$this->periode_short->LinkCustomAttributes = "";
		$this->periode_short->HrefValue = "";
		$this->periode_short->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// tanggal
		$this->tanggal->EditAttrs["class"] = "form-control";
		$this->tanggal->EditCustomAttributes = "";
		$this->tanggal->EditValue = ew_FormatDateTime($this->tanggal->CurrentValue, 8);
		$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

		// nama
		$this->nama->EditAttrs["class"] = "form-control";
		$this->nama->EditCustomAttributes = "";
		$this->nama->EditValue = $this->nama->CurrentValue;
		$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

		// no_kwitansi
		$this->no_kwitansi->EditAttrs["class"] = "form-control";
		$this->no_kwitansi->EditCustomAttributes = "";
		$this->no_kwitansi->EditValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->PlaceHolder = ew_RemoveHtml($this->no_kwitansi->FldCaption());

		// nomor
		$this->nomor->EditAttrs["class"] = "form-control";
		$this->nomor->EditCustomAttributes = "";
		$this->nomor->EditValue = $this->nomor->CurrentValue;
		$this->nomor->PlaceHolder = ew_RemoveHtml($this->nomor->FldCaption());

		// no_referensi
		$this->no_referensi->EditAttrs["class"] = "form-control";
		$this->no_referensi->EditCustomAttributes = "";
		$this->no_referensi->EditValue = $this->no_referensi->CurrentValue;
		$this->no_referensi->PlaceHolder = ew_RemoveHtml($this->no_referensi->FldCaption());

		// nilai_ppn
		$this->nilai_ppn->EditAttrs["class"] = "form-control";
		$this->nilai_ppn->EditCustomAttributes = "";
		$this->nilai_ppn->EditValue = $this->nilai_ppn->CurrentValue;
		$this->nilai_ppn->PlaceHolder = ew_RemoveHtml($this->nilai_ppn->FldCaption());
		if (strval($this->nilai_ppn->EditValue) <> "" && is_numeric($this->nilai_ppn->EditValue)) $this->nilai_ppn->EditValue = ew_FormatNumber($this->nilai_ppn->EditValue, -2, -1, -2, 0);

		// total_ppn
		$this->total_ppn->EditAttrs["class"] = "form-control";
		$this->total_ppn->EditCustomAttributes = "";
		$this->total_ppn->EditValue = $this->total_ppn->CurrentValue;
		$this->total_ppn->PlaceHolder = ew_RemoveHtml($this->total_ppn->FldCaption());
		if (strval($this->total_ppn->EditValue) <> "" && is_numeric($this->total_ppn->EditValue)) $this->total_ppn->EditValue = ew_FormatNumber($this->total_ppn->EditValue, -2, -1, -2, 0);

		// invoice_id
		$this->invoice_id->EditAttrs["class"] = "form-control";
		$this->invoice_id->EditCustomAttributes = "";
		$this->invoice_id->EditValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// periode
		$this->periode->EditAttrs["class"] = "form-control";
		$this->periode->EditCustomAttributes = "";
		$this->periode->EditValue = ew_FormatDateTime($this->periode->CurrentValue, 8);
		$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

		// tanggal_short
		$this->tanggal_short->EditAttrs["class"] = "form-control";
		$this->tanggal_short->EditCustomAttributes = "";
		$this->tanggal_short->EditValue = $this->tanggal_short->CurrentValue;
		$this->tanggal_short->PlaceHolder = ew_RemoveHtml($this->tanggal_short->FldCaption());

		// periode_short
		$this->periode_short->EditAttrs["class"] = "form-control";
		$this->periode_short->EditCustomAttributes = "";
		$this->periode_short->EditValue = $this->periode_short->CurrentValue;
		$this->periode_short->PlaceHolder = ew_RemoveHtml($this->periode_short->FldCaption());

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
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->no_kwitansi->Exportable) $Doc->ExportCaption($this->no_kwitansi);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->no_referensi->Exportable) $Doc->ExportCaption($this->no_referensi);
					if ($this->nilai_ppn->Exportable) $Doc->ExportCaption($this->nilai_ppn);
					if ($this->total_ppn->Exportable) $Doc->ExportCaption($this->total_ppn);
					if ($this->invoice_id->Exportable) $Doc->ExportCaption($this->invoice_id);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->tanggal_short->Exportable) $Doc->ExportCaption($this->tanggal_short);
					if ($this->periode_short->Exportable) $Doc->ExportCaption($this->periode_short);
				} else {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->no_kwitansi->Exportable) $Doc->ExportCaption($this->no_kwitansi);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->no_referensi->Exportable) $Doc->ExportCaption($this->no_referensi);
					if ($this->nilai_ppn->Exportable) $Doc->ExportCaption($this->nilai_ppn);
					if ($this->total_ppn->Exportable) $Doc->ExportCaption($this->total_ppn);
					if ($this->invoice_id->Exportable) $Doc->ExportCaption($this->invoice_id);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->tanggal_short->Exportable) $Doc->ExportCaption($this->tanggal_short);
					if ($this->periode_short->Exportable) $Doc->ExportCaption($this->periode_short);
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
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->no_kwitansi->Exportable) $Doc->ExportField($this->no_kwitansi);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->no_referensi->Exportable) $Doc->ExportField($this->no_referensi);
						if ($this->nilai_ppn->Exportable) $Doc->ExportField($this->nilai_ppn);
						if ($this->total_ppn->Exportable) $Doc->ExportField($this->total_ppn);
						if ($this->invoice_id->Exportable) $Doc->ExportField($this->invoice_id);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->tanggal_short->Exportable) $Doc->ExportField($this->tanggal_short);
						if ($this->periode_short->Exportable) $Doc->ExportField($this->periode_short);
					} else {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->no_kwitansi->Exportable) $Doc->ExportField($this->no_kwitansi);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->no_referensi->Exportable) $Doc->ExportField($this->no_referensi);
						if ($this->nilai_ppn->Exportable) $Doc->ExportField($this->nilai_ppn);
						if ($this->total_ppn->Exportable) $Doc->ExportField($this->total_ppn);
						if ($this->invoice_id->Exportable) $Doc->ExportField($this->invoice_id);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->tanggal_short->Exportable) $Doc->ExportField($this->tanggal_short);
						if ($this->periode_short->Exportable) $Doc->ExportField($this->periode_short);
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
