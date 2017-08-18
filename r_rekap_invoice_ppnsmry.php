<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_rekap_invoice_ppnsmryinfo.php" ?>
<?php

//
// Page class
//

$r_rekap_invoice_ppn_summary = NULL; // Initialize page object first

class crr_rekap_invoice_ppn_summary extends crr_rekap_invoice_ppn {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{A9671DEB-57B5-48F0-BE68-784D09E2FE7C}";

	// Page object name
	var $PageObjName = 'r_rekap_invoice_ppn_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;
		global $UserTable, $UserTableConn;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (r_rekap_invoice_ppn)
		if (!isset($GLOBALS["r_rekap_invoice_ppn"])) {
			$GLOBALS["r_rekap_invoice_ppn"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_rekap_invoice_ppn"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'r_rekap_invoice_ppn', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new crt_user();
			$UserTableConn = ReportConn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fr_rekap_invoice_ppnsummary";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Security
		$Security = new crAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_rekap_invoice_ppn');
		$Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("login.php"));
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = TRUE;
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_rekap_invoice_ppn\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_rekap_invoice_ppn',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_rekap_invoice_ppnsummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_rekap_invoice_ppnsummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_rekap_invoice_ppnsummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_ConvertFullUrl($saveToFile) : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		 // Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Set field visibility for detail fields
		$this->nama->SetVisibility();
		$this->no_kwitansi->SetVisibility();
		$this->nomor->SetVisibility();
		$this->no_referensi->SetVisibility();
		$this->nilai_ppn->SetVisibility();
		$this->total_ppn->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 7;
		$nGrps = 4;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
			if ($Security->CanList()) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
			} else {
				$this->setWarningMessage($ReportLanguage->Phrase("NoPermission"));
			}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Get summary count
	function GetSummaryCount($lvl, $curValue = TRUE) {
		$cnt = 0;
		foreach ($this->DetailRows as $row) {
			$wrkperiode_short = $row["periode_short"];
			$wrktanggal = $row["tanggal"];
			$wrktanggal_short = $row["tanggal_short"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->periode_short->CurrentValue : $this->periode_short->OldValue;
				$grpval = $curValue ? $this->periode_short->GroupValue() : $this->periode_short->GroupOldValue();
				if (is_null($val) && !is_null($wrkperiode_short) || !is_null($val) && is_null($wrkperiode_short) ||
					$grpval <> $this->periode_short->getGroupValueBase($wrkperiode_short))
				continue;
			}
			if ($lvl >= 2) {
				$val = $curValue ? $this->tanggal->CurrentValue : $this->tanggal->OldValue;
				$grpval = $curValue ? $this->tanggal->GroupValue() : $this->tanggal->GroupOldValue();
				if (is_null($val) && !is_null($wrktanggal) || !is_null($val) && is_null($wrktanggal) ||
					$grpval <> $this->tanggal->getGroupValueBase($wrktanggal))
				continue;
			}
			if ($lvl >= 3) {
				$val = $curValue ? $this->tanggal_short->CurrentValue : $this->tanggal_short->OldValue;
				$grpval = $curValue ? $this->tanggal_short->GroupValue() : $this->tanggal_short->GroupOldValue();
				if (is_null($val) && !is_null($wrktanggal_short) || !is_null($val) && is_null($wrktanggal_short) ||
					$grpval <> $this->tanggal_short->getGroupValueBase($wrktanggal_short))
				continue;
			}
			$cnt++;
		}
		return $cnt;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->periode_short->CurrentValue) && !is_null($this->periode_short->OldValue)) ||
					(!is_null($this->periode_short->CurrentValue) && is_null($this->periode_short->OldValue)) ||
					($this->periode_short->GroupValue() <> $this->periode_short->GroupOldValue());
			case 2:
				return (is_null($this->tanggal->CurrentValue) && !is_null($this->tanggal->OldValue)) ||
					(!is_null($this->tanggal->CurrentValue) && is_null($this->tanggal->OldValue)) ||
					($this->tanggal->GroupValue() <> $this->tanggal->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($this->tanggal_short->CurrentValue) && !is_null($this->tanggal_short->OldValue)) ||
					(!is_null($this->tanggal_short->CurrentValue) && is_null($this->tanggal_short->OldValue)) ||
					($this->tanggal_short->GroupValue() <> $this->tanggal_short->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		$conn = &$this->Connection();
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->periode_short->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->periode_short->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->periode_short->setDbValue("");
		}
	}

	// Get detail recordset
	function GetDetailRs($wrksql) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->Execute($wrksql);
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL") {
			$this->DetailRows = ($rswrk) ? $rswrk->GetRows() : array();
		} else { // Cannot MoveFirst, use another recordset
			$rstmp = $conn->Execute($wrksql);
			$this->DetailRows = ($rstmp) ? $rstmp->GetRows() : array();
			$rstmp->Close();
		}
		$conn->raiseErrorFn = "";
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
			$rs->MoveFirst(); // Move first
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['periode_short'] = ewr_Conv($rs->fields('periode_short'), 200);
				$this->FirstRowData['tanggal_short'] = ewr_Conv($rs->fields('tanggal_short'), 200);
				$this->FirstRowData['periode'] = ewr_Conv($rs->fields('periode'), 133);
				$this->FirstRowData['tanggal'] = ewr_Conv($rs->fields('tanggal'), 133);
				$this->FirstRowData['nama'] = ewr_Conv($rs->fields('nama'), 200);
				$this->FirstRowData['no_kwitansi'] = ewr_Conv($rs->fields('no_kwitansi'), 200);
				$this->FirstRowData['nomor'] = ewr_Conv($rs->fields('nomor'), 200);
				$this->FirstRowData['no_referensi'] = ewr_Conv($rs->fields('no_referensi'), 200);
				$this->FirstRowData['nilai_ppn'] = ewr_Conv($rs->fields('nilai_ppn'), 5);
				$this->FirstRowData['total_ppn'] = ewr_Conv($rs->fields('total_ppn'), 4);
				$this->FirstRowData['invoice_id'] = ewr_Conv($rs->fields('invoice_id'), 3);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1) {
				if (is_array($this->periode_short->GroupDbValues))
					$this->periode_short->setDbValue(@$this->periode_short->GroupDbValues[$rs->fields('periode_short')]);
				else
					$this->periode_short->setDbValue(ewr_GroupValue($this->periode_short, $rs->fields('periode_short')));
			}
			$this->tanggal_short->setDbValue($rs->fields('tanggal_short'));
			$this->periode->setDbValue($rs->fields('periode'));
			$this->tanggal->setDbValue($rs->fields('tanggal'));
			$this->nama->setDbValue($rs->fields('nama'));
			$this->no_kwitansi->setDbValue($rs->fields('no_kwitansi'));
			$this->nomor->setDbValue($rs->fields('nomor'));
			$this->no_referensi->setDbValue($rs->fields('no_referensi'));
			$this->nilai_ppn->setDbValue($rs->fields('nilai_ppn'));
			$this->total_ppn->setDbValue($rs->fields('total_ppn'));
			$this->invoice_id->setDbValue($rs->fields('invoice_id'));
			$this->Val[1] = $this->nama->CurrentValue;
			$this->Val[2] = $this->no_kwitansi->CurrentValue;
			$this->Val[3] = $this->nomor->CurrentValue;
			$this->Val[4] = $this->no_referensi->CurrentValue;
			$this->Val[5] = $this->nilai_ppn->CurrentValue;
			$this->Val[6] = $this->total_ppn->CurrentValue;
		} else {
			$this->periode_short->setDbValue("");
			$this->tanggal_short->setDbValue("");
			$this->periode->setDbValue("");
			$this->tanggal->setDbValue("");
			$this->nama->setDbValue("");
			$this->no_kwitansi->setDbValue("");
			$this->nomor->setDbValue("");
			$this->no_referensi->setDbValue("");
			$this->nilai_ppn->setDbValue("");
			$this->total_ppn->setDbValue("");
			$this->invoice_id->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();

		// Set up tanggal GroupDbValues
		$sSql = ewr_BuildReportSql($this->tanggal->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tanggal->SqlOrderBy, "", "");
		$rswrk = $conn->Execute($sSql);
		while ($rswrk && !$rswrk->EOF) {
			$this->tanggal->setDbValue($rswrk->fields[0]);
			if (!is_null($this->tanggal->CurrentValue) && $this->tanggal->CurrentValue <> "") {
				$grpval = $rswrk->fields('ew_report_groupvalue');
				$this->tanggal->GroupDbValues[$this->tanggal->CurrentValue] = $grpval;
			}
			$rswrk->MoveNext();
		}
		if ($rswrk)
			$rswrk->Close();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandSmry[5] = $rsagg->fields("sum_nilai_ppn");
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandSmry[6] = $rsagg->fields("sum_total_ppn");
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel); // Set up row class
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->periode_short->GroupOldValue(); // Set up group attribute
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->tanggal->GroupOldValue(); // Set up group attribute 2
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->tanggal_short->GroupOldValue(); // Set up group attribute 3

			// periode_short
			$this->periode_short->GroupViewValue = $this->periode_short->GroupOldValue();
			$this->periode_short->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->periode_short->CellAttrs["style"] = "width: 100px;";
			$this->periode_short->GroupViewValue = ewr_DisplayGroupValue($this->periode_short, $this->periode_short->GroupViewValue);
			$this->periode_short->GroupSummaryOldValue = $this->periode_short->GroupSummaryValue;
			$this->periode_short->GroupSummaryValue = $this->periode_short->GroupViewValue;
			$this->periode_short->GroupSummaryViewValue = ($this->periode_short->GroupSummaryOldValue <> $this->periode_short->GroupSummaryValue) ? $this->periode_short->GroupSummaryValue : "&nbsp;";

			// tanggal
			$this->tanggal->GroupViewValue = $this->tanggal->GroupOldValue();
			$this->tanggal->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->tanggal->GroupViewValue = ewr_DisplayGroupValue($this->tanggal, $this->tanggal->GroupViewValue);
			$this->tanggal->GroupSummaryOldValue = $this->tanggal->GroupSummaryValue;
			$this->tanggal->GroupSummaryValue = $this->tanggal->GroupViewValue;
			$this->tanggal->GroupSummaryViewValue = ($this->tanggal->GroupSummaryOldValue <> $this->tanggal->GroupSummaryValue) ? $this->tanggal->GroupSummaryValue : "&nbsp;";

			// tanggal_short
			$this->tanggal_short->GroupViewValue = $this->tanggal_short->GroupOldValue();
			$this->tanggal_short->CellAttrs["class"] = ($this->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$this->tanggal_short->CellAttrs["style"] = "width: 100px;";
			$this->tanggal_short->GroupViewValue = ewr_DisplayGroupValue($this->tanggal_short, $this->tanggal_short->GroupViewValue);
			$this->tanggal_short->GroupSummaryOldValue = $this->tanggal_short->GroupSummaryValue;
			$this->tanggal_short->GroupSummaryValue = $this->tanggal_short->GroupViewValue;
			$this->tanggal_short->GroupSummaryViewValue = ($this->tanggal_short->GroupSummaryOldValue <> $this->tanggal_short->GroupSummaryValue) ? $this->tanggal_short->GroupSummaryValue : "&nbsp;";

			// nilai_ppn
			$this->nilai_ppn->SumViewValue = $this->nilai_ppn->SumValue;
			$this->nilai_ppn->SumViewValue = ewr_FormatNumber($this->nilai_ppn->SumViewValue, 0, -2, -2, -1);
			$this->nilai_ppn->CellAttrs["style"] = "text-align:right;";
			$this->nilai_ppn->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// total_ppn
			$this->total_ppn->SumViewValue = $this->total_ppn->SumValue;
			$this->total_ppn->SumViewValue = ewr_FormatNumber($this->total_ppn->SumViewValue, 0, -2, -2, -1);
			$this->total_ppn->CellAttrs["style"] = "text-align:right;";
			$this->total_ppn->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// periode_short
			$this->periode_short->HrefValue = "";

			// tanggal
			$this->tanggal->HrefValue = "";

			// tanggal_short
			$this->tanggal_short->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// no_kwitansi
			$this->no_kwitansi->HrefValue = "";

			// nomor
			$this->nomor->HrefValue = "";

			// no_referensi
			$this->no_referensi->HrefValue = "";

			// nilai_ppn
			$this->nilai_ppn->HrefValue = "";

			// total_ppn
			$this->total_ppn->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->periode_short->GroupValue(); // Set up group attribute
			if ($this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->tanggal->GroupValue(); // Set up group attribute 2
			if ($this->RowGroupLevel >= 3) $this->RowAttrs["data-group-3"] = $this->tanggal_short->GroupValue(); // Set up group attribute 3
			} else {
			$this->RowAttrs["data-group"] = $this->periode_short->GroupValue(); // Set up group attribute
			$this->RowAttrs["data-group-2"] = $this->tanggal->GroupValue(); // Set up group attribute 2
			$this->RowAttrs["data-group-3"] = $this->tanggal_short->GroupValue(); // Set up group attribute 3
			}

			// periode_short
			$this->periode_short->GroupViewValue = $this->periode_short->GroupValue();
			$this->periode_short->CellAttrs["class"] = "ewRptGrpField1";
			$this->periode_short->CellAttrs["style"] = "width: 100px;";
			$this->periode_short->GroupViewValue = ewr_DisplayGroupValue($this->periode_short, $this->periode_short->GroupViewValue);
			if ($this->periode_short->GroupValue() == $this->periode_short->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->periode_short->GroupViewValue = "&nbsp;";

			// tanggal
			$this->tanggal->GroupViewValue = $this->tanggal->GroupValue();
			$this->tanggal->CellAttrs["class"] = "ewRptGrpField2";
			$this->tanggal->GroupViewValue = ewr_DisplayGroupValue($this->tanggal, $this->tanggal->GroupViewValue);
			if ($this->tanggal->GroupValue() == $this->tanggal->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->tanggal->GroupViewValue = "&nbsp;";

			// tanggal_short
			$this->tanggal_short->GroupViewValue = $this->tanggal_short->GroupValue();
			$this->tanggal_short->CellAttrs["class"] = "ewRptGrpField3";
			$this->tanggal_short->CellAttrs["style"] = "width: 100px;";
			$this->tanggal_short->GroupViewValue = ewr_DisplayGroupValue($this->tanggal_short, $this->tanggal_short->GroupViewValue);
			if ($this->tanggal_short->GroupValue() == $this->tanggal_short->GroupOldValue() && !$this->ChkLvlBreak(3))
				$this->tanggal_short->GroupViewValue = "&nbsp;";

			// nama
			$this->nama->ViewValue = $this->nama->CurrentValue;
			$this->nama->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->nama->CellAttrs["style"] = "width: 200px;";

			// no_kwitansi
			$this->no_kwitansi->ViewValue = $this->no_kwitansi->CurrentValue;
			$this->no_kwitansi->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->no_kwitansi->CellAttrs["style"] = "width: 100px;";

			// nomor
			$this->nomor->ViewValue = $this->nomor->CurrentValue;
			$this->nomor->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->nomor->CellAttrs["style"] = "width: 200px;";

			// no_referensi
			$this->no_referensi->ViewValue = $this->no_referensi->CurrentValue;
			$this->no_referensi->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->no_referensi->CellAttrs["style"] = "width: 200px;";

			// nilai_ppn
			$this->nilai_ppn->ViewValue = $this->nilai_ppn->CurrentValue;
			$this->nilai_ppn->ViewValue = ewr_FormatNumber($this->nilai_ppn->ViewValue, 0, -2, -2, -1);
			$this->nilai_ppn->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->nilai_ppn->CellAttrs["style"] = "text-align:right;";

			// total_ppn
			$this->total_ppn->ViewValue = $this->total_ppn->CurrentValue;
			$this->total_ppn->ViewValue = ewr_FormatNumber($this->total_ppn->ViewValue, 0, -2, -2, -1);
			$this->total_ppn->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->total_ppn->CellAttrs["style"] = "text-align:right;";

			// periode_short
			$this->periode_short->HrefValue = "";

			// tanggal
			$this->tanggal->HrefValue = "";

			// tanggal_short
			$this->tanggal_short->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// no_kwitansi
			$this->no_kwitansi->HrefValue = "";

			// nomor
			$this->nomor->HrefValue = "";

			// no_referensi
			$this->no_referensi->HrefValue = "";

			// nilai_ppn
			$this->nilai_ppn->HrefValue = "";

			// total_ppn
			$this->total_ppn->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// periode_short
			$CurrentValue = $this->periode_short->GroupViewValue;
			$ViewValue = &$this->periode_short->GroupViewValue;
			$ViewAttrs = &$this->periode_short->ViewAttrs;
			$CellAttrs = &$this->periode_short->CellAttrs;
			$HrefValue = &$this->periode_short->HrefValue;
			$LinkAttrs = &$this->periode_short->LinkAttrs;
			$this->Cell_Rendered($this->periode_short, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tanggal
			$CurrentValue = $this->tanggal->GroupViewValue;
			$ViewValue = &$this->tanggal->GroupViewValue;
			$ViewAttrs = &$this->tanggal->ViewAttrs;
			$CellAttrs = &$this->tanggal->CellAttrs;
			$HrefValue = &$this->tanggal->HrefValue;
			$LinkAttrs = &$this->tanggal->LinkAttrs;
			$this->Cell_Rendered($this->tanggal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tanggal_short
			$CurrentValue = $this->tanggal_short->GroupViewValue;
			$ViewValue = &$this->tanggal_short->GroupViewValue;
			$ViewAttrs = &$this->tanggal_short->ViewAttrs;
			$CellAttrs = &$this->tanggal_short->CellAttrs;
			$HrefValue = &$this->tanggal_short->HrefValue;
			$LinkAttrs = &$this->tanggal_short->LinkAttrs;
			$this->Cell_Rendered($this->tanggal_short, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nilai_ppn
			$CurrentValue = $this->nilai_ppn->SumValue;
			$ViewValue = &$this->nilai_ppn->SumViewValue;
			$ViewAttrs = &$this->nilai_ppn->ViewAttrs;
			$CellAttrs = &$this->nilai_ppn->CellAttrs;
			$HrefValue = &$this->nilai_ppn->HrefValue;
			$LinkAttrs = &$this->nilai_ppn->LinkAttrs;
			$this->Cell_Rendered($this->nilai_ppn, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// total_ppn
			$CurrentValue = $this->total_ppn->SumValue;
			$ViewValue = &$this->total_ppn->SumViewValue;
			$ViewAttrs = &$this->total_ppn->ViewAttrs;
			$CellAttrs = &$this->total_ppn->CellAttrs;
			$HrefValue = &$this->total_ppn->HrefValue;
			$LinkAttrs = &$this->total_ppn->LinkAttrs;
			$this->Cell_Rendered($this->total_ppn, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// periode_short
			$CurrentValue = $this->periode_short->GroupValue();
			$ViewValue = &$this->periode_short->GroupViewValue;
			$ViewAttrs = &$this->periode_short->ViewAttrs;
			$CellAttrs = &$this->periode_short->CellAttrs;
			$HrefValue = &$this->periode_short->HrefValue;
			$LinkAttrs = &$this->periode_short->LinkAttrs;
			$this->Cell_Rendered($this->periode_short, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tanggal
			$CurrentValue = $this->tanggal->GroupValue();
			$ViewValue = &$this->tanggal->GroupViewValue;
			$ViewAttrs = &$this->tanggal->ViewAttrs;
			$CellAttrs = &$this->tanggal->CellAttrs;
			$HrefValue = &$this->tanggal->HrefValue;
			$LinkAttrs = &$this->tanggal->LinkAttrs;
			$this->Cell_Rendered($this->tanggal, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tanggal_short
			$CurrentValue = $this->tanggal_short->GroupValue();
			$ViewValue = &$this->tanggal_short->GroupViewValue;
			$ViewAttrs = &$this->tanggal_short->ViewAttrs;
			$CellAttrs = &$this->tanggal_short->CellAttrs;
			$HrefValue = &$this->tanggal_short->HrefValue;
			$LinkAttrs = &$this->tanggal_short->LinkAttrs;
			$this->Cell_Rendered($this->tanggal_short, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nama
			$CurrentValue = $this->nama->CurrentValue;
			$ViewValue = &$this->nama->ViewValue;
			$ViewAttrs = &$this->nama->ViewAttrs;
			$CellAttrs = &$this->nama->CellAttrs;
			$HrefValue = &$this->nama->HrefValue;
			$LinkAttrs = &$this->nama->LinkAttrs;
			$this->Cell_Rendered($this->nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// no_kwitansi
			$CurrentValue = $this->no_kwitansi->CurrentValue;
			$ViewValue = &$this->no_kwitansi->ViewValue;
			$ViewAttrs = &$this->no_kwitansi->ViewAttrs;
			$CellAttrs = &$this->no_kwitansi->CellAttrs;
			$HrefValue = &$this->no_kwitansi->HrefValue;
			$LinkAttrs = &$this->no_kwitansi->LinkAttrs;
			$this->Cell_Rendered($this->no_kwitansi, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nomor
			$CurrentValue = $this->nomor->CurrentValue;
			$ViewValue = &$this->nomor->ViewValue;
			$ViewAttrs = &$this->nomor->ViewAttrs;
			$CellAttrs = &$this->nomor->CellAttrs;
			$HrefValue = &$this->nomor->HrefValue;
			$LinkAttrs = &$this->nomor->LinkAttrs;
			$this->Cell_Rendered($this->nomor, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// no_referensi
			$CurrentValue = $this->no_referensi->CurrentValue;
			$ViewValue = &$this->no_referensi->ViewValue;
			$ViewAttrs = &$this->no_referensi->ViewAttrs;
			$CellAttrs = &$this->no_referensi->CellAttrs;
			$HrefValue = &$this->no_referensi->HrefValue;
			$LinkAttrs = &$this->no_referensi->LinkAttrs;
			$this->Cell_Rendered($this->no_referensi, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nilai_ppn
			$CurrentValue = $this->nilai_ppn->CurrentValue;
			$ViewValue = &$this->nilai_ppn->ViewValue;
			$ViewAttrs = &$this->nilai_ppn->ViewAttrs;
			$CellAttrs = &$this->nilai_ppn->CellAttrs;
			$HrefValue = &$this->nilai_ppn->HrefValue;
			$LinkAttrs = &$this->nilai_ppn->LinkAttrs;
			$this->Cell_Rendered($this->nilai_ppn, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// total_ppn
			$CurrentValue = $this->total_ppn->CurrentValue;
			$ViewValue = &$this->total_ppn->ViewValue;
			$ViewAttrs = &$this->total_ppn->ViewAttrs;
			$CellAttrs = &$this->total_ppn->CellAttrs;
			$HrefValue = &$this->total_ppn->HrefValue;
			$LinkAttrs = &$this->total_ppn->LinkAttrs;
			$this->Cell_Rendered($this->total_ppn, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->periode_short->Visible) $this->GrpColumnCount += 1;
		if ($this->tanggal->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->tanggal_short->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->nama->Visible) $this->DtlColumnCount += 1;
		if ($this->no_kwitansi->Visible) $this->DtlColumnCount += 1;
		if ($this->nomor->Visible) $this->DtlColumnCount += 1;
		if ($this->no_referensi->Visible) $this->DtlColumnCount += 1;
		if ($this->nilai_ppn->Visible) $this->DtlColumnCount += 1;
		if ($this->total_ppn->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $gsFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->periode_short->DropDownValue, $this->periode_short->SearchOperator, 'periode_short'); // Field periode_short

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field periode_short
			if ($this->GetDropDownValue($this->periode_short)) {
				$bSetupFilter = TRUE;
			} elseif ($this->periode_short->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_r_rekap_invoice_ppn_periode_short'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->periode_short); // Field periode_short
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->periode_short, $sFilter, $this->periode_short->SearchOperator, FALSE, TRUE); // Field periode_short

		// Save parms to session
		$this->SetSessionDropDownValue($this->periode_short->DropDownValue, $this->periode_short->SearchOperator, 'periode_short'); // Field periode_short

		// Setup filter
		if ($bSetupFilter) {
		}

		// Field periode_short
		ewr_LoadDropDownList($this->periode_short->DropDownList, $this->periode_short->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : (is_array($fld->DropDownValue)) ? $fld->DropDownValue : explode(",", $fld->DropDownValue);
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@") $this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@") $this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if ($FldVal == EWR_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif ($FldVal == EWR_NOT_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif ($FldVal == EWR_EMPTY_VALUE) {
			$sWrk = $FldExpression . " = ''";
		} elseif ($FldVal == EWR_ALL_VALUE) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "") {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID,0,2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			if (is_array($fld->DropDownValue)) $fld->DropDownValue = implode(",", $fld->DropDownValue);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewr_StripSlashes(@$_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewr_StripSlashes(@$_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewr_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_r_rekap_invoice_ppn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_rekap_invoice_ppn_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_r_rekap_invoice_ppn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_rekap_invoice_ppn_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_r_rekap_invoice_ppn_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_r_rekap_invoice_ppn_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_r_rekap_invoice_ppn_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_r_rekap_invoice_ppn_' . $parm] = $sv;
		$_SESSION['so_r_rekap_invoice_ppn_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_r_rekap_invoice_ppn_' . $parm] = $sv1;
		$_SESSION['so_r_rekap_invoice_ppn_' . $parm] = $so1;
		$_SESSION['sc_r_rekap_invoice_ppn_' . $parm] = $sc;
		$_SESSION['sv2_r_rekap_invoice_ppn_' . $parm] = $sv2;
		$_SESSION['so2_r_rekap_invoice_ppn_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<p>&nbsp;</p>" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_r_rekap_invoice_ppn_$parm"] = "";
		$_SESSION["rf_r_rekap_invoice_ppn_$parm"] = "";
		$_SESSION["rt_r_rekap_invoice_ppn_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_r_rekap_invoice_ppn_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_r_rekap_invoice_ppn_$parm"];
		$fld->RangeTo = @$_SESSION["rt_r_rekap_invoice_ppn_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field periode_short
		$this->periode_short->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->periode_short->DropDownValue = $this->periode_short->DefaultDropDownValue;
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/
		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check periode_short extended filter
		if ($this->NonTextFilterApplied($this->periode_short))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field periode_short
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->periode_short, $sExtWrk, $this->periode_short->SearchOperator);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->periode_short->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info ewDisplayTable\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field periode_short
		$sWrk = "";
		$sWrk = ($this->periode_short->DropDownValue <> EWR_INIT_VALUE) ? $this->periode_short->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_periode_short\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ewr_StripSlashes(@$_POST["filter"]), TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field periode_short
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_periode_short", $filter)) {
			$sWrk = $filter["sv_periode_short"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_periode_short"], "periode_short");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "periode_short");
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "`no_kwitansi` ASC";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : ewr_StripSlashes(@$_GET["order"]);
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : ewr_StripSlashes(@$_GET["ordertype"]);

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->periode_short->setSort("");
			$this->tanggal->setSort("");
			$this->tanggal_short->setSort("");
			$this->nama->setSort("");
			$this->no_kwitansi->setSort("");
			$this->nomor->setSort("");
			$this->no_referensi->setSort("");
			$this->nilai_ppn->setSort("");
			$this->total_ppn->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->periode_short, $bCtrl); // periode_short
			$this->UpdateSort($this->tanggal, $bCtrl); // tanggal
			$this->UpdateSort($this->tanggal_short, $bCtrl); // tanggal_short
			$this->UpdateSort($this->nama, $bCtrl); // nama
			$this->UpdateSort($this->no_kwitansi, $bCtrl); // no_kwitansi
			$this->UpdateSort($this->nomor, $bCtrl); // nomor
			$this->UpdateSort($this->no_referensi, $bCtrl); // no_referensi
			$this->UpdateSort($this->nilai_ppn, $bCtrl); // nilai_ppn
			$this->UpdateSort($this->total_ppn, $bCtrl); // total_ppn
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}

		// Set up default sort
		if ($this->getOrderBy() == "") {
			$this->setOrderBy("`no_kwitansi` ASC");
			$this->no_kwitansi->setSort("ASC");
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $options = array()) {
		global $gTmpImages, $ReportLanguage;
		$bGenRequest = @$options["reporttype"] == "email";
		$sFailRespPfx = $bGenRequest ? "" : "<p class=\"text-error\">";
		$sSuccessRespPfx = $bGenRequest ? "" : "<p class=\"text-success\">";
		$sRespPfx = $bGenRequest ? "" : "</p>";
		$sContentType = (@$options["contenttype"] <> "") ? $options["contenttype"] : @$_POST["contenttype"];
		$sSender = (@$options["sender"] <> "") ? $options["sender"] : @$_POST["sender"];
		$sRecipient = (@$options["recipient"] <> "") ? $options["recipient"] : @$_POST["recipient"];
		$sCc = (@$options["cc"] <> "") ? $options["cc"] : @$_POST["cc"];
		$sBcc = (@$options["bcc"] <> "") ? $options["bcc"] : @$_POST["bcc"];

		// Subject
		$sEmailSubject = (@$options["subject"] <> "") ? $options["subject"] : ewr_StripSlashes(@$_POST["subject"]);

		// Message
		$sEmailMessage = (@$options["message"] <> "") ? $options["message"] : ewr_StripSlashes(@$_POST["message"]);

		// Check sender
		if ($sSender == "")
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterSenderEmail") . $sRespPfx;
		if (!ewr_CheckEmail($sSender))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperSenderEmail") . $sRespPfx;

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperRecipientEmail") . $sRespPfx;

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperCcEmail") . $sRespPfx;

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperBccEmail") . $sRespPfx;

		// Check email sent count
		$emailcount = $bGenRequest ? 0 : ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return $sFailRespPfx . $ReportLanguage->Phrase("ExceedMaxEmailExport") . $sRespPfx;
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_AdjustEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return $sSuccessRespPfx . $ReportLanguage->Phrase("SendEmailSuccess") . $sRespPfx; // Set up success message
		} else {

			// Sent email failure
			return $sFailRespPfx . $Email->SendErrDescription . $sRespPfx;
		}
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
		return $saveToFile;
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
		$this->tanggal->Visible = false;
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($r_rekap_invoice_ppn_summary)) $r_rekap_invoice_ppn_summary = new crr_rekap_invoice_ppn_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_rekap_invoice_ppn_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var r_rekap_invoice_ppn_summary = new ewr_Page("r_rekap_invoice_ppn_summary");

// Page properties
r_rekap_invoice_ppn_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = r_rekap_invoice_ppn_summary.PageID;

// Extend page with Chart_Rendering function
r_rekap_invoice_ppn_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_rekap_invoice_ppn_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fr_rekap_invoice_ppnsummary = new ewr_Form("fr_rekap_invoice_ppnsummary");

// Validate method
fr_rekap_invoice_ppnsummary.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fr_rekap_invoice_ppnsummary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fr_rekap_invoice_ppnsummary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fr_rekap_invoice_ppnsummary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fr_rekap_invoice_ppnsummary.Lists["sv_periode_short"] = {"LinkField":"sv_periode_short","Ajax":true,"DisplayFields":["sv_periode_short","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if ($Page->Export == "" && (!$Page->DrillDown || !$Page->DrillDownInPanel)) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "") { ?>
</div>
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- Left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fr_rekap_invoice_ppnsummary" id="fr_rekap_invoice_ppnsummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fr_rekap_invoice_ppnsummary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_periode_short" class="ewCell form-group">
	<label for="sv_periode_short" class="ewSearchCaption ewLabel"><?php echo $Page->periode_short->FldCaption() ?></label>
	<span class="ewSearchField">
<?php $Page->periode_short->EditAttrs["onchange"] = "ewrForms(this).Submit(); " . @$Page->periode_short->EditAttrs["onchange"]; ?>
<?php ewr_PrependClass($Page->periode_short->EditAttrs["class"], "form-control"); ?>
<select data-table="r_rekap_invoice_ppn" data-field="x_periode_short" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->periode_short->DisplayValueSeparator) ? json_encode($Page->periode_short->DisplayValueSeparator) : $Page->periode_short->DisplayValueSeparator) ?>" id="sv_periode_short" name="sv_periode_short"<?php echo $Page->periode_short->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->periode_short->AdvancedFilters) ? count($Page->periode_short->AdvancedFilters) : 0;
	$cntd = is_array($Page->periode_short->DropDownList) ? count($Page->periode_short->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->periode_short->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->periode_short->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->periode_short->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->periode_short->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_periode_short" id="s_sv_periode_short" value="<?php echo $Page->periode_short->LookupFilterQuery() ?>"></span>
</div>
</div>
</div>
</form>
<script type="text/javascript">
fr_rekap_invoice_ppnsummary.Init();
fr_rekap_invoice_ppnsummary.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_rekap_invoice_ppnsmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_r_rekap_invoice_ppn"><?php echo $Page->PageBreakContent ?></span>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->periode_short->Visible) { ?>
	<?php if ($Page->periode_short->ShowGroupHeaderAsRow) { ?>
	<td data-field="periode_short">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="periode_short"><div class="r_rekap_invoice_ppn_periode_short" style="width: 100px;"><span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="periode_short">
<?php if ($Page->SortUrl($Page->periode_short) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_periode_short" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_periode_short" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->periode_short) ?>',2);" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->periode_short->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->periode_short->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
	<?php if ($Page->tanggal->ShowGroupHeaderAsRow) { ?>
	<td data-field="tanggal">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tanggal"><div class="r_rekap_invoice_ppn_tanggal"><span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tanggal">
<?php if ($Page->SortUrl($Page->tanggal) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_tanggal">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_tanggal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tanggal) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
	<?php if ($Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
	<td data-field="tanggal_short">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tanggal_short"><div class="r_rekap_invoice_ppn_tanggal_short" style="width: 100px;"><span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tanggal_short">
<?php if ($Page->SortUrl($Page->tanggal_short) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_tanggal_short" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_tanggal_short" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tanggal_short) ?>',2);" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tanggal_short->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tanggal_short->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nama"><div class="r_rekap_invoice_ppn_nama" style="width: 200px;"><span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nama">
<?php if ($Page->SortUrl($Page->nama) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_nama" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nama) ?>',2);" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="no_kwitansi"><div class="r_rekap_invoice_ppn_no_kwitansi" style="width: 100px;"><span class="ewTableHeaderCaption"><?php echo $Page->no_kwitansi->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="no_kwitansi">
<?php if ($Page->SortUrl($Page->no_kwitansi) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_no_kwitansi" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_kwitansi->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_no_kwitansi" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->no_kwitansi) ?>',2);" style="width: 100px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_kwitansi->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->no_kwitansi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->no_kwitansi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nomor"><div class="r_rekap_invoice_ppn_nomor" style="width: 200px;"><span class="ewTableHeaderCaption"><?php echo $Page->nomor->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nomor">
<?php if ($Page->SortUrl($Page->nomor) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_nomor" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nomor->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_nomor" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nomor) ?>',2);" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nomor->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nomor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nomor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="no_referensi"><div class="r_rekap_invoice_ppn_no_referensi" style="width: 200px;"><span class="ewTableHeaderCaption"><?php echo $Page->no_referensi->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="no_referensi">
<?php if ($Page->SortUrl($Page->no_referensi) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_no_referensi" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_referensi->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_no_referensi" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->no_referensi) ?>',2);" style="width: 200px;">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_referensi->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->no_referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->no_referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nilai_ppn"><div class="r_rekap_invoice_ppn_nilai_ppn" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->nilai_ppn->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nilai_ppn">
<?php if ($Page->SortUrl($Page->nilai_ppn) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_nilai_ppn" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nilai_ppn->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_nilai_ppn" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nilai_ppn) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->nilai_ppn->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nilai_ppn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nilai_ppn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="total_ppn"><div class="r_rekap_invoice_ppn_total_ppn" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->total_ppn->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="total_ppn">
<?php if ($Page->SortUrl($Page->total_ppn) == "") { ?>
		<div class="ewTableHeaderBtn r_rekap_invoice_ppn_total_ppn" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->total_ppn->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_rekap_invoice_ppn_total_ppn" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->total_ppn) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->total_ppn->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->total_ppn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->total_ppn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->periode_short, $Page->getSqlFirstGroupField(), $Page->periode_short->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $Page->GetDetailRs($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = array(-1);
	$Page->GrpIdx[$Page->GrpCount][] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->periode_short->Visible && $Page->ChkLvlBreak(1) && $Page->periode_short->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->periode_short->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="periode_short" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->periode_short->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_periode_short"><span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->periode_short) == "") { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_periode_short">
			<span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_rekap_invoice_ppn_periode_short" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->periode_short) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->periode_short->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->periode_short->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->periode_short->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_periode_short"<?php echo $Page->periode_short->ViewAttributes() ?>><?php echo $Page->periode_short->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->periode_short->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->tanggal->Visible && $Page->ChkLvlBreak(2) && $Page->tanggal->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 2;
		$Page->tanggal->Count = $Page->GetSummaryCount(2);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="tanggal" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 2) ?>"<?php echo $Page->tanggal->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_tanggal"><span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->tanggal) == "") { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_tanggal">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_rekap_invoice_ppn_tanggal" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tanggal) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_tanggal"<?php echo $Page->tanggal->ViewAttributes() ?>><?php echo $Page->tanggal->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->tanggal_short->Visible && $Page->ChkLvlBreak(3) && $Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 3;
		$Page->tanggal_short->Count = $Page->GetSummaryCount(3);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
		<td data-field="tanggal_short"<?php echo $Page->tanggal_short->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="tanggal_short" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 3) ?>"<?php echo $Page->tanggal_short->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_tanggal_short"><span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->tanggal_short) == "") { ?>
		<span class="ewSummaryCaption r_rekap_invoice_ppn_tanggal_short">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_rekap_invoice_ppn_tanggal_short" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tanggal_short) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tanggal_short->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tanggal_short->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tanggal_short->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_tanggal_short"<?php echo $Page->tanggal_short->ViewAttributes() ?>><?php echo $Page->tanggal_short->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal_short->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
	<?php if ($Page->periode_short->ShowGroupHeaderAsRow) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_periode_short"<?php echo $Page->periode_short->ViewAttributes() ?>><?php echo $Page->periode_short->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
	<?php if ($Page->tanggal->ShowGroupHeaderAsRow) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_tanggal"<?php echo $Page->tanggal->ViewAttributes() ?>><?php echo $Page->tanggal->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
	<?php if ($Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
		<td data-field="tanggal_short"<?php echo $Page->tanggal_short->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="tanggal_short"<?php echo $Page->tanggal_short->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_tanggal_short"<?php echo $Page->tanggal_short->ViewAttributes() ?>><?php echo $Page->tanggal_short->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_nama"<?php echo $Page->nama->ViewAttributes() ?>><?php echo $Page->nama->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->no_kwitansi->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_no_kwitansi"<?php echo $Page->no_kwitansi->ViewAttributes() ?>><?php echo $Page->no_kwitansi->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->nomor->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_nomor"<?php echo $Page->nomor->ViewAttributes() ?>><?php echo $Page->nomor->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->no_referensi->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_no_referensi"<?php echo $Page->no_referensi->ViewAttributes() ?>><?php echo $Page->no_referensi->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->nilai_ppn->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_<?php echo $Page->RecCount ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
		if ($Page->ChkLvlBreak(3)) {
			$cnt = count(@$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]]);
			$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]][$cnt] = $Page->RecCount;
		}
		if ($Page->ChkLvlBreak(3) && $Page->tanggal_short->Visible) {
?>
<?php
			$Page->periode_short->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->tanggal->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->tanggal_short->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->nilai_ppn->Count = $Page->Cnt[3][5];
			$Page->nilai_ppn->SumValue = $Page->Smry[3][5]; // Load SUM
			$Page->total_ppn->Count = $Page->Cnt[3][6];
			$Page->total_ppn->SumValue = $Page->Smry[3][6]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 3;
			$Page->RenderRow();
?>
<?php if ($Page->tanggal_short->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>
	<?php if ($Page->periode_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->periode_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes() ?>>
	<?php if ($Page->tanggal->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
		<td data-field="tanggal_short"<?php echo $Page->tanggal_short->CellAttributes() ?>>
	<?php if ($Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->tanggal_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->tanggal_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->tanggal_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->tanggal_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->tanggal_short->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->tanggal_short->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->SubGrpColumnCount + $Page->DtlColumnCount - 1 > 0) { ?>
		<td colspan="<?php echo ($Page->SubGrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->total_ppn->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->tanggal_short->GroupViewValue, $Page->tanggal_short->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[3][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 2) ?>"<?php echo $Page->tanggal_short->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->tanggal_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->tanggal_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->tanggal_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->tanggal_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->GrpCounter[1] ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 3 summary
			$Page->ResetLevelSummary(3);
		} // End show footer check
		if ($Page->ChkLvlBreak(3)) {
			$Page->GrpCounter[1]++;
		}
?>
<?php
		if ($Page->ChkLvlBreak(2) && $Page->tanggal->Visible) {
?>
<?php
			$Page->periode_short->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->tanggal->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->tanggal_short->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->nilai_ppn->Count = $Page->Cnt[2][5];
			$Page->nilai_ppn->SumValue = $Page->Smry[2][5]; // Load SUM
			$Page->total_ppn->Count = $Page->Cnt[2][6];
			$Page->total_ppn->SumValue = $Page->Smry[2][6]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 2;
			$Page->RenderRow();
?>
<?php if ($Page->tanggal->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>
	<?php if ($Page->periode_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->periode_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->tanggal->CellAttributes() ?>>
	<?php if ($Page->tanggal->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
		<td data-field="tanggal_short"<?php echo $Page->tanggal->CellAttributes() ?>>
	<?php if ($Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->tanggal->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->tanggal->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->tanggal->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->tanggal->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->tanggal->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->tanggal->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->SubGrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->SubGrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->total_ppn->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->tanggal->GroupViewValue, $Page->tanggal->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[2][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 1) ?>"<?php echo $Page->tanggal->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->tanggal->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 2 summary
			$Page->ResetLevelSummary(2);
		} // End show footer check
		if ($Page->ChkLvlBreak(2)) {
			$Page->GrpCounter[0]++;
			if (!$rs->EOF)
				$Page->GrpIdx[$Page->GrpCount][$Page->GrpCounter[0]] = array(-1);
			$Page->GrpCounter[1] = 1;
		}
?>
<?php
	} // End detail records loop
?>
<?php
		if ($Page->periode_short->Visible) {
?>
<?php
			$Page->periode_short->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->tanggal->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->tanggal_short->Count = $Page->GetSummaryCount(3, FALSE);
			$Page->nilai_ppn->Count = $Page->Cnt[1][5];
			$Page->nilai_ppn->SumValue = $Page->Smry[1][5]; // Load SUM
			$Page->total_ppn->Count = $Page->Cnt[1][6];
			$Page->total_ppn->SumValue = $Page->Smry[1][6]; // Load SUM
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 1;
			$Page->RenderRow();
?>
<?php if ($Page->periode_short->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->periode_short->Visible) { ?>
		<td data-field="periode_short"<?php echo $Page->periode_short->CellAttributes() ?>>
	<?php if ($Page->periode_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->periode_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { ?>
		<td data-field="tanggal"<?php echo $Page->periode_short->CellAttributes() ?>>
	<?php if ($Page->tanggal->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tanggal_short->Visible) { ?>
		<td data-field="tanggal_short"<?php echo $Page->periode_short->CellAttributes() ?>>
	<?php if ($Page->tanggal_short->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 3) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->tanggal_short->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->periode_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->periode_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->periode_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->periode_short->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->periode_short->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->periode_short->CellAttributes() ?>><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><span data-class="tpgs<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->total_ppn->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->periode_short->GroupViewValue, $Page->periode_short->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[1][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount - 0) ?>"<?php echo $Page->periode_short->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->periode_short->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpgs<?php echo $Page->GrpCount ?>_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 1 summary
			$Page->ResetLevelSummary(1);
		} // End show footer check
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;
	$Page->GrpCounter[1] = 1;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php if (($Page->StopGrp - $Page->StartGrp + 1) <> $Page->TotalGrps) { ?>
<?php
	$Page->nilai_ppn->Count = $Page->Cnt[0][5];
	$Page->nilai_ppn->SumValue = $Page->Smry[0][5]; // Load SUM
	$Page->total_ppn->Count = $Page->Cnt[0][6];
	$Page->total_ppn->SumValue = $Page->Smry[0][6]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_PAGE;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptPageSummary";
	$Page->RenderRow();
?>
<?php if ($Page->periode_short->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptPageSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->Cnt[0][0],0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->no_kwitansi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->nomor->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->no_referensi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->nilai_ppn->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpps_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptPageSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[0][0],0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->no_kwitansi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->nomor->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->no_referensi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->nilai_ppn->CellAttributes() ?>>
<span data-class="tpps_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpps_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php } ?>
<?php
	$Page->nilai_ppn->Count = $Page->GrandCnt[5];
	$Page->nilai_ppn->SumValue = $Page->GrandSmry[5]; // Load SUM
	$Page->total_ppn->Count = $Page->GrandCnt[6];
	$Page->total_ppn->SumValue = $Page->GrandSmry[6]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->periode_short->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->no_kwitansi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->nomor->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->no_referensi->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->nilai_ppn->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum") ?>=<span data-class="tpts_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->GrpColumnCount > 0) { ?>
		<td colspan="<?php echo $Page->GrpColumnCount ?>" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->nama->Visible) { ?>
		<td data-field="nama"<?php echo $Page->nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_kwitansi->Visible) { ?>
		<td data-field="no_kwitansi"<?php echo $Page->no_kwitansi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nomor->Visible) { ?>
		<td data-field="nomor"<?php echo $Page->nomor->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->no_referensi->Visible) { ?>
		<td data-field="no_referensi"<?php echo $Page->no_referensi->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->nilai_ppn->Visible) { ?>
		<td data-field="nilai_ppn"<?php echo $Page->nilai_ppn->CellAttributes() ?>>
<span data-class="tpts_r_rekap_invoice_ppn_nilai_ppn"<?php echo $Page->nilai_ppn->ViewAttributes() ?>><?php echo $Page->nilai_ppn->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->total_ppn->Visible) { ?>
		<td data-field="total_ppn"<?php echo $Page->total_ppn->CellAttributes() ?>>
<span data-class="tpts_r_rekap_invoice_ppn_total_ppn"<?php echo $Page->total_ppn->ViewAttributes() ?>><?php echo $Page->total_ppn->SumViewValue ?></span></td>
<?php } ?>
	</tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_rekap_invoice_ppnsmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container - report (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
<?php } ?>
	<!-- Right slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
<?php } ?>
	<!-- Bottom slot -->
<?php if ($Page->Export == "") { ?>
	</div>
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
<?php } ?>
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$(".ewSummaryCount").hide();
</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
