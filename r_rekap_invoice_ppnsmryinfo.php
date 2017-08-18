<?php

// Global variable for table object
$r_rekap_invoice_ppn = NULL;

//
// Table class for r_rekap_invoice_ppn
//
class crr_rekap_invoice_ppn extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $periode_short;
	var $tanggal_short;
	var $periode;
	var $tanggal;
	var $nama;
	var $no_kwitansi;
	var $nomor;
	var $no_referensi;
	var $nilai_ppn;
	var $total_ppn;
	var $invoice_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_rekap_invoice_ppn';
		$this->TableName = 'r_rekap_invoice_ppn';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// periode_short
		$this->periode_short = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_periode_short', 'periode_short', '`periode_short`', 200, EWR_DATATYPE_STRING, -1);
		$this->periode_short->Sortable = TRUE; // Allow sort
		$this->periode_short->GroupingFieldId = 1;
		$this->periode_short->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->periode_short->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['periode_short'] = &$this->periode_short;
		$this->periode_short->DateFilter = "";
		$this->periode_short->SqlSelect = "";
		$this->periode_short->SqlOrderBy = "";
		$this->periode_short->FldGroupByType = "";
		$this->periode_short->FldGroupInt = "0";
		$this->periode_short->FldGroupSql = "";

		// tanggal_short
		$this->tanggal_short = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_tanggal_short', 'tanggal_short', '`tanggal_short`', 200, EWR_DATATYPE_STRING, -1);
		$this->tanggal_short->Sortable = TRUE; // Allow sort
		$this->tanggal_short->GroupingFieldId = 3;
		$this->tanggal_short->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->tanggal_short->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->fields['tanggal_short'] = &$this->tanggal_short;
		$this->tanggal_short->DateFilter = "";
		$this->tanggal_short->SqlSelect = "";
		$this->tanggal_short->SqlOrderBy = "";
		$this->tanggal_short->FldGroupByType = "";
		$this->tanggal_short->FldGroupInt = "0";
		$this->tanggal_short->FldGroupSql = "";

		// periode
		$this->periode = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_periode', 'periode', '`periode`', 133, EWR_DATATYPE_DATE, 7);
		$this->periode->Sortable = TRUE; // Allow sort
		$this->periode->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectField");
		$this->fields['periode'] = &$this->periode;
		$this->periode->DateFilter = "";
		$this->periode->SqlSelect = "SELECT DISTINCT `periode`, `periode` AS `DispFld` FROM " . $this->getSqlFrom();
		$this->periode->SqlOrderBy = "`periode`";

		// tanggal
		$this->tanggal = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_tanggal', 'tanggal', '`tanggal`', 133, EWR_DATATYPE_DATE, -1);
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->GroupingFieldId = 2;
		$this->tanggal->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->tanggal->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_FORMAT"], $ReportLanguage->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;
		$this->tanggal->DateFilter = "";
		$this->tanggal->SqlSelect = "SELECT DISTINCT `tanggal`, CONCAT(CAST(YEAR(`tanggal`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`tanggal`),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(`tanggal`),2,'0') AS CHAR(2))) AS `ew_report_groupvalue` FROM " . $this->getSqlFrom();
		$this->tanggal->SqlOrderBy = "CONCAT(CAST(YEAR(`tanggal`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`tanggal`),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(`tanggal`),2,'0') AS CHAR(2)))";
		$this->tanggal->FldGroupByType = "d";
		$this->tanggal->FldGroupInt = "0";
		$this->tanggal->FldGroupSql = "CONCAT(CAST(YEAR(%s) AS CHAR(4)), '|', CAST(LPAD(MONTH(%s),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(%s),2,'0') AS CHAR(2)))";

		// nama
		$this->nama = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_nama', 'nama', '`nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;
		$this->nama->DateFilter = "";
		$this->nama->SqlSelect = "";
		$this->nama->SqlOrderBy = "";

		// no_kwitansi
		$this->no_kwitansi = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_no_kwitansi', 'no_kwitansi', '`no_kwitansi`', 200, EWR_DATATYPE_STRING, -1);
		$this->no_kwitansi->Sortable = TRUE; // Allow sort
		$this->fields['no_kwitansi'] = &$this->no_kwitansi;
		$this->no_kwitansi->DateFilter = "";
		$this->no_kwitansi->SqlSelect = "";
		$this->no_kwitansi->SqlOrderBy = "";

		// nomor
		$this->nomor = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_nomor', 'nomor', '`nomor`', 200, EWR_DATATYPE_STRING, -1);
		$this->nomor->Sortable = TRUE; // Allow sort
		$this->fields['nomor'] = &$this->nomor;
		$this->nomor->DateFilter = "";
		$this->nomor->SqlSelect = "";
		$this->nomor->SqlOrderBy = "";

		// no_referensi
		$this->no_referensi = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_no_referensi', 'no_referensi', '`no_referensi`', 200, EWR_DATATYPE_STRING, -1);
		$this->no_referensi->Sortable = TRUE; // Allow sort
		$this->fields['no_referensi'] = &$this->no_referensi;
		$this->no_referensi->DateFilter = "";
		$this->no_referensi->SqlSelect = "";
		$this->no_referensi->SqlOrderBy = "";

		// nilai_ppn
		$this->nilai_ppn = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_nilai_ppn', 'nilai_ppn', '`nilai_ppn`', 5, EWR_DATATYPE_NUMBER, -1);
		$this->nilai_ppn->Sortable = TRUE; // Allow sort
		$this->nilai_ppn->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['nilai_ppn'] = &$this->nilai_ppn;
		$this->nilai_ppn->DateFilter = "";
		$this->nilai_ppn->SqlSelect = "";
		$this->nilai_ppn->SqlOrderBy = "";

		// total_ppn
		$this->total_ppn = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_total_ppn', 'total_ppn', '`total_ppn`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->total_ppn->Sortable = TRUE; // Allow sort
		$this->total_ppn->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['total_ppn'] = &$this->total_ppn;
		$this->total_ppn->DateFilter = "";
		$this->total_ppn->SqlSelect = "";
		$this->total_ppn->SqlOrderBy = "";

		// invoice_id
		$this->invoice_id = new crField('r_rekap_invoice_ppn', 'r_rekap_invoice_ppn', 'x_invoice_id', 'invoice_id', '`invoice_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->invoice_id->Sortable = TRUE; // Allow sort
		$this->invoice_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['invoice_id'] = &$this->invoice_id;
		$this->invoice_id->DateFilter = "";
		$this->invoice_id->SqlSelect = "";
		$this->invoice_id->SqlOrderBy = "";
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ofld->GroupingFieldId == 0) {
				if ($ctrl) {
					$sOrderBy = $this->getDetailOrderBy();
					if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
						$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
					} else {
						if ($sOrderBy <> "") $sOrderBy .= ", ";
						$sOrderBy .= $sSortField . " " . $sThisSort;
					}
					$this->setDetailOrderBy($sOrderBy); // Save to Session
				} else {
					$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
				}
			}
		} else {
			if ($ofld->GroupingFieldId == 0 && !$ctrl) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_rekap_invoice_ppn`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`periode_short` ASC, CONCAT(CAST(YEAR(`tanggal`) AS CHAR(4)), '|', CAST(LPAD(MONTH(`tanggal`),2,'0') AS CHAR(2)), '|', CAST(LPAD(DAY(`tanggal`),2,'0') AS CHAR(2))) ASC, `tanggal_short` ASC";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`periode_short`";
	}

	function SqlFirstGroupField() { // For backward compatibility
		return $this->getSqlFirstGroupField();
	}

	function setSqlFirstGroupField($v) {
		$this->_SqlFirstGroupField = $v;
	}

	// Select Group
	var $_SqlSelectGroup = "";

	function getSqlSelectGroup() {
		return ($this->_SqlSelectGroup <> "") ? $this->_SqlSelectGroup : "SELECT DISTINCT " . $this->getSqlFirstGroupField() . " FROM " . $this->getSqlFrom();
	}

	function SqlSelectGroup() { // For backward compatibility
		return $this->getSqlSelectGroup();
	}

	function setSqlSelectGroup($v) {
		$this->_SqlSelectGroup = $v;
	}

	// Order By Group
	var $_SqlOrderByGroup = "";

	function getSqlOrderByGroup() {
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`periode_short` ASC";
	}

	function SqlOrderByGroup() { // For backward compatibility
		return $this->getSqlOrderByGroup();
	}

	function setSqlOrderByGroup($v) {
		$this->_SqlOrderByGroup = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`nilai_ppn`) AS `sum_nilai_ppn`, SUM(`total_ppn`) AS `sum_total_ppn` FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {

			//$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort();
			return ewr_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		case "x_periode_short":
			$sSqlWrk = "";
		$sSqlWrk = "SELECT DISTINCT `periode_short`, `periode_short` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `v_rekap_invoice_ppn`";
		$sWhereWrk = "";
		$this->periode_short->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "DB", "f0" => '`periode_short` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
		$this->Lookup_Selecting($this->periode_short, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `periode_short` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

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

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
