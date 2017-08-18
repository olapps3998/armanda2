<?php

// Global variable for table object
$t_invoice_fee = NULL;

//
// Table class for t_invoice_fee
//
class ct_invoice_fee extends cTable {
	var $invoice_detail_id;
	var $invoice_id;
	var $fee_id;
	var $harga;
	var $qty;
	var $satuan;
	var $jumlah;
	var $keterangan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_invoice_fee';
		$this->TableName = 't_invoice_fee';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_invoice_fee`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// invoice_detail_id
		$this->invoice_detail_id = new cField('t_invoice_fee', 't_invoice_fee', 'x_invoice_detail_id', 'invoice_detail_id', '`invoice_detail_id`', '`invoice_detail_id`', 3, -1, FALSE, '`invoice_detail_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->invoice_detail_id->Sortable = TRUE; // Allow sort
		$this->invoice_detail_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoice_detail_id'] = &$this->invoice_detail_id;

		// invoice_id
		$this->invoice_id = new cField('t_invoice_fee', 't_invoice_fee', 'x_invoice_id', 'invoice_id', '`invoice_id`', '`invoice_id`', 3, -1, FALSE, '`invoice_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->invoice_id->Sortable = TRUE; // Allow sort
		$this->invoice_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] = &$this->invoice_id;

		// fee_id
		$this->fee_id = new cField('t_invoice_fee', 't_invoice_fee', 'x_fee_id', 'fee_id', '`fee_id`', '`fee_id`', 3, -1, FALSE, '`EV__fee_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->fee_id->Sortable = TRUE; // Allow sort
		$this->fee_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->fee_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fee_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['fee_id'] = &$this->fee_id;

		// harga
		$this->harga = new cField('t_invoice_fee', 't_invoice_fee', 'x_harga', 'harga', '`harga`', '`harga`', 4, -1, FALSE, '`harga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->harga->Sortable = TRUE; // Allow sort
		$this->harga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['harga'] = &$this->harga;

		// qty
		$this->qty = new cField('t_invoice_fee', 't_invoice_fee', 'x_qty', 'qty', '`qty`', '`qty`', 131, -1, FALSE, '`qty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->qty->Sortable = TRUE; // Allow sort
		$this->qty->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qty'] = &$this->qty;

		// satuan
		$this->satuan = new cField('t_invoice_fee', 't_invoice_fee', 'x_satuan', 'satuan', '`satuan`', '`satuan`', 200, -1, FALSE, '`satuan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->satuan->Sortable = TRUE; // Allow sort
		$this->fields['satuan'] = &$this->satuan;

		// jumlah
		$this->jumlah = new cField('t_invoice_fee', 't_invoice_fee', 'x_jumlah', 'jumlah', '`jumlah`', '`jumlah`', 4, -1, FALSE, '`jumlah`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jumlah->Sortable = TRUE; // Allow sort
		$this->jumlah->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jumlah'] = &$this->jumlah;

		// keterangan
		$this->keterangan = new cField('t_invoice_fee', 't_invoice_fee', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 201, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "t_invoice") {
			if ($this->invoice_id->getSessionValue() <> "")
				$sMasterFilter .= "`invoice_id`=" . ew_QuotedValue($this->invoice_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "t_invoice") {
			if ($this->invoice_id->getSessionValue() <> "")
				$sDetailFilter .= "`invoice_id`=" . ew_QuotedValue($this->invoice_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_t_invoice() {
		return "`invoice_id`=@invoice_id@";
	}

	// Detail filter
	function SqlDetailFilter_t_invoice() {
		return "`invoice_id`=@invoice_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_invoice_fee`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `jenis` FROM `t_fee` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`fee_id` = `t_invoice_fee`.`fee_id` LIMIT 1) AS `EV__fee_id` FROM `t_invoice_fee`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->fee_id->AdvancedSearch->SearchValue <> "" ||
			$this->fee_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->fee_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->fee_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			if (array_key_exists('invoice_detail_id', $rs))
				ew_AddFilter($where, ew_QuotedName('invoice_detail_id', $this->DBID) . '=' . ew_QuotedValue($rs['invoice_detail_id'], $this->invoice_detail_id->FldDataType, $this->DBID));
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
		return "`invoice_detail_id` = @invoice_detail_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->invoice_detail_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@invoice_detail_id@", ew_AdjustSql($this->invoice_detail_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "t_invoice_feelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_invoice_feelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_invoice_feeview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_invoice_feeview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_invoice_feeadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_invoice_feeadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_invoice_feeedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_invoice_feeadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_invoice_feedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "t_invoice" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_invoice_id=" . urlencode($this->invoice_id->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "invoice_detail_id:" . ew_VarToJson($this->invoice_detail_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->invoice_detail_id->CurrentValue)) {
			$sUrl .= "invoice_detail_id=" . urlencode($this->invoice_detail_id->CurrentValue);
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
			if ($isPost && isset($_POST["invoice_detail_id"]))
				$arKeys[] = ew_StripSlashes($_POST["invoice_detail_id"]);
			elseif (isset($_GET["invoice_detail_id"]))
				$arKeys[] = ew_StripSlashes($_GET["invoice_detail_id"]);
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
			$this->invoice_detail_id->CurrentValue = $key;
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
		$this->invoice_detail_id->setDbValue($rs->fields('invoice_detail_id'));
		$this->invoice_id->setDbValue($rs->fields('invoice_id'));
		$this->fee_id->setDbValue($rs->fields('fee_id'));
		$this->harga->setDbValue($rs->fields('harga'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan->setDbValue($rs->fields('satuan'));
		$this->jumlah->setDbValue($rs->fields('jumlah'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// invoice_detail_id
		// invoice_id
		// fee_id
		// harga
		// qty
		// satuan
		// jumlah
		// keterangan
		// invoice_detail_id

		$this->invoice_detail_id->ViewValue = $this->invoice_detail_id->CurrentValue;
		$this->invoice_detail_id->ViewCustomAttributes = "";

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// fee_id
		if ($this->fee_id->VirtualValue <> "") {
			$this->fee_id->ViewValue = $this->fee_id->VirtualValue;
		} else {
		if (strval($this->fee_id->CurrentValue) <> "") {
			$sFilterWrk = "`fee_id`" . ew_SearchString("=", $this->fee_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `fee_id`, `jenis` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_fee`";
		$sWhereWrk = "";
		$this->fee_id->LookupFilters = array("dx1" => '`jenis`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fee_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->fee_id->ViewValue = $this->fee_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fee_id->ViewValue = $this->fee_id->CurrentValue;
			}
		} else {
			$this->fee_id->ViewValue = NULL;
		}
		}
		$this->fee_id->ViewCustomAttributes = "";

		// harga
		$this->harga->ViewValue = $this->harga->CurrentValue;
		$this->harga->ViewValue = ew_FormatNumber($this->harga->ViewValue, 0, -2, -2, -1);
		$this->harga->CellCssStyle .= "text-align: right;";
		$this->harga->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewValue = ew_FormatNumber($this->qty->ViewValue, 4, -2, -2, -1);
		$this->qty->CellCssStyle .= "text-align: right;";
		$this->qty->ViewCustomAttributes = "";

		// satuan
		$this->satuan->ViewValue = $this->satuan->CurrentValue;
		$this->satuan->ViewCustomAttributes = "";

		// jumlah
		$this->jumlah->ViewValue = $this->jumlah->CurrentValue;
		$this->jumlah->ViewValue = ew_FormatNumber($this->jumlah->ViewValue, 0, -2, -2, -1);
		$this->jumlah->CellCssStyle .= "text-align: right;";
		$this->jumlah->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// invoice_detail_id
		$this->invoice_detail_id->LinkCustomAttributes = "";
		$this->invoice_detail_id->HrefValue = "";
		$this->invoice_detail_id->TooltipValue = "";

		// invoice_id
		$this->invoice_id->LinkCustomAttributes = "";
		$this->invoice_id->HrefValue = "";
		$this->invoice_id->TooltipValue = "";

		// fee_id
		$this->fee_id->LinkCustomAttributes = "";
		$this->fee_id->HrefValue = "";
		$this->fee_id->TooltipValue = "";

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

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// invoice_detail_id
		$this->invoice_detail_id->EditAttrs["class"] = "form-control";
		$this->invoice_detail_id->EditCustomAttributes = "";
		$this->invoice_detail_id->EditValue = $this->invoice_detail_id->CurrentValue;
		$this->invoice_detail_id->ViewCustomAttributes = "";

		// invoice_id
		$this->invoice_id->EditAttrs["class"] = "form-control";
		$this->invoice_id->EditCustomAttributes = "";
		if ($this->invoice_id->getSessionValue() <> "") {
			$this->invoice_id->CurrentValue = $this->invoice_id->getSessionValue();
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";
		} else {
		$this->invoice_id->EditValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->PlaceHolder = ew_RemoveHtml($this->invoice_id->FldCaption());
		}

		// fee_id
		$this->fee_id->EditAttrs["class"] = "form-control";
		$this->fee_id->EditCustomAttributes = "";

		// harga
		$this->harga->EditAttrs["class"] = "form-control";
		$this->harga->EditCustomAttributes = "";
		$this->harga->EditValue = $this->harga->CurrentValue;
		$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
		if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -1);

		// qty
		$this->qty->EditAttrs["class"] = "form-control";
		$this->qty->EditCustomAttributes = "";
		$this->qty->EditValue = $this->qty->CurrentValue;
		$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
		if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -1);

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
		if (strval($this->jumlah->EditValue) <> "" && is_numeric($this->jumlah->EditValue)) $this->jumlah->EditValue = ew_FormatNumber($this->jumlah->EditValue, -2, -2, -2, -1);

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

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
					if ($this->fee_id->Exportable) $Doc->ExportCaption($this->fee_id);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan->Exportable) $Doc->ExportCaption($this->satuan);
					if ($this->jumlah->Exportable) $Doc->ExportCaption($this->jumlah);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
				} else {
					if ($this->invoice_detail_id->Exportable) $Doc->ExportCaption($this->invoice_detail_id);
					if ($this->invoice_id->Exportable) $Doc->ExportCaption($this->invoice_id);
					if ($this->fee_id->Exportable) $Doc->ExportCaption($this->fee_id);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan->Exportable) $Doc->ExportCaption($this->satuan);
					if ($this->jumlah->Exportable) $Doc->ExportCaption($this->jumlah);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
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
						if ($this->fee_id->Exportable) $Doc->ExportField($this->fee_id);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan->Exportable) $Doc->ExportField($this->satuan);
						if ($this->jumlah->Exportable) $Doc->ExportField($this->jumlah);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
					} else {
						if ($this->invoice_detail_id->Exportable) $Doc->ExportField($this->invoice_detail_id);
						if ($this->invoice_id->Exportable) $Doc->ExportField($this->invoice_id);
						if ($this->fee_id->Exportable) $Doc->ExportField($this->fee_id);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan->Exportable) $Doc->ExportField($this->satuan);
						if ($this->jumlah->Exportable) $Doc->ExportField($this->jumlah);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
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
		$rsnew["jumlah"] = floatval($rsnew["harga"]) * intval($rsnew["qty"]);

		// To cancel, set return value to FALSE
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		$tot_det = ew_ExecuteScalar("SELECT SUM(jumlah) FROM t_invoice_fee WHERE invoice_id = ".$rsnew["invoice_id"]."");
		$nilai_ppn = ew_ExecuteScalar("
			select ppn from t_invoice where invoice_id = ".$rsnew["invoice_id"]."
			");
		$total_ppn = $tot_det;
		if ($nilai_ppn != 0) {
			$total_ppn = $tot_det + ($tot_det * ($nilai_ppn/100));
		}
		$terbilang = Terbilang($total_ppn);
		ew_Execute("
			UPDATE t_invoice SET
				total = ".$tot_det.",
				total_ppn = ".$total_ppn.",
				terbilang = '".$terbilang."'
			WHERE
				invoice_id = ".$rsnew["invoice_id"]."");
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		$rsnew["jumlah"] = $rsnew["harga"] * $rsnew["qty"];

		// To cancel, set return value to FALSE
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		$tot_det = ew_ExecuteScalar("SELECT SUM(jumlah) FROM t_invoice_fee WHERE invoice_id = ".$rsold["invoice_id"]."");
		$nilai_ppn = ew_ExecuteScalar("
			select ppn from t_invoice where invoice_id = ".$rsold["invoice_id"]."
			");
		$total_ppn = $tot_det;
		if ($nilai_ppn != 0) {
			$total_ppn = $tot_det + ($tot_det * ($nilai_ppn/100));
		}
		$terbilang = Terbilang($total_ppn);
		ew_Execute("
			UPDATE t_invoice SET
				total = ".$tot_det.",
				total_ppn = ".$total_ppn.",
				terbilang = '".$terbilang."'
			WHERE
				invoice_id = ".$rsold["invoice_id"]."");
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
		$rec_cnt_det = ew_ExecuteScalar("SELECT COUNT(jumlah) FROM t_invoice_fee WHERE invoice_id = ".$rs["invoice_id"]."");
		if ($rec_cnt_det > 0) {
			$tot_det = ew_ExecuteScalar("SELECT SUM(jumlah) FROM t_invoice_fee WHERE invoice_id = ".$rs["invoice_id"]."");

			//ew_Execute("UPDATE tb_invoice SET total = ".$tot_det." WHERE id = ".$rs["invoice_id"]."");
			$nilai_ppn = ew_ExecuteScalar("
				select ppn from t_invoice where invoice_id = ".$rs["invoice_id"]."
				");
			$total_ppn = $tot_det;
			if ($nilai_ppn != 0) {
				$total_ppn = $tot_det + ($tot_det * ($nilai_ppn/100));
			}
			$terbilang = Terbilang($total_ppn);
			ew_Execute("
				UPDATE t_invoice SET
					total = ".$tot_det.",
					total_ppn = ".$total_ppn.",
					terbilang = '".$terbilang."'
				WHERE
					invoice_id = ".$rs["invoice_id"]."");
		}
		else {

			//ew_Execute("UPDATE tb_invoice SET total = 0 WHERE id = ".$rs["invoice_id"]."");
			ew_Execute("
				UPDATE t_invoice SET
					total = 0,
					total_ppn = 0,
					terbilang = '-'
				WHERE
					invoice_id = ".$rs["invoice_id"]."");
		}
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

		$this->jumlah->ReadOnly = true;
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
