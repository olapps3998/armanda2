<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_invoiceinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "t_invoice_feegridcls.php" ?>
<?php include_once "t_invoice_pelaksanaangridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_invoice_view = NULL; // Initialize page object first

class ct_invoice_view extends ct_invoice {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{02A4272B-E84A-463D-9ED2-75398DF0A44A}";

	// Table name
	var $TableName = 't_invoice';

	// Page object name
	var $PageObjName = 't_invoice_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = FALSE;
	var $AuditTrailOnDelete = FALSE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
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
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_invoice)
		if (!isset($GLOBALS["t_invoice"]) || get_class($GLOBALS["t_invoice"]) == "ct_invoice") {
			$GLOBALS["t_invoice"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_invoice"];
		}
		$KeyUrl = "";
		if (@$_GET["invoice_id"] <> "") {
			$this->RecKey["invoice_id"] = $_GET["invoice_id"];
			$KeyUrl .= "&amp;invoice_id=" . urlencode($this->RecKey["invoice_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_invoice', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_invoicelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["invoice_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["invoice_id"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->customer_id->SetVisibility();
		$this->nomor->SetVisibility();
		$this->tanggal->SetVisibility();
		$this->no_order->SetVisibility();
		$this->no_referensi->SetVisibility();
		$this->kegiatan->SetVisibility();
		$this->no_sertifikat->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->total->SetVisibility();
		$this->ppn->SetVisibility();
		$this->total_ppn->SetVisibility();
		$this->terbilang->SetVisibility();
		$this->terbayar->SetVisibility();
		$this->tgl_bayar->SetVisibility();
		$this->pasal23->SetVisibility();
		$this->no_kwitansi->SetVisibility();
		$this->periode->SetVisibility();

		// Set up detail page object
		$this->SetupDetailPages();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_invoice;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_invoice);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;
	var $DetailPages; // Detail pages object

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["invoice_id"] <> "") {
				$this->invoice_id->setQueryStringValue($_GET["invoice_id"]);
				$this->RecKey["invoice_id"] = $this->invoice_id->QueryStringValue;
			} elseif (@$_POST["invoice_id"] <> "") {
				$this->invoice_id->setFormValue($_POST["invoice_id"]);
				$this->RecKey["invoice_id"] = $this->invoice_id->FormValue;
			} else {
				$sReturnUrl = "t_invoicelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "t_invoicelist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "t_invoicelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_t_invoice_fee"
		$item = &$option->Add("detail_t_invoice_fee");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_invoice_fee", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_invoice_feelist.php?" . EW_TABLE_SHOW_MASTER . "=t_invoice&fk_invoice_id=" . urlencode(strval($this->invoice_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_invoice_fee_grid"] && $GLOBALS["t_invoice_fee_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_invoice_fee')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_fee")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_invoice_fee";
		}
		if ($GLOBALS["t_invoice_fee_grid"] && $GLOBALS["t_invoice_fee_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_invoice_fee')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_fee")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_invoice_fee";
		}
		if ($GLOBALS["t_invoice_fee_grid"] && $GLOBALS["t_invoice_fee_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_invoice_fee')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_fee")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "t_invoice_fee";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_invoice_fee');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_invoice_fee";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_invoice_pelaksanaan"
		$item = &$option->Add("detail_t_invoice_pelaksanaan");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_invoice_pelaksanaan", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_invoice_pelaksanaanlist.php?" . EW_TABLE_SHOW_MASTER . "=t_invoice&fk_invoice_id=" . urlencode(strval($this->invoice_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_invoice_pelaksanaan_grid"] && $GLOBALS["t_invoice_pelaksanaan_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_invoice_pelaksanaan')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_pelaksanaan")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_invoice_pelaksanaan";
		}
		if ($GLOBALS["t_invoice_pelaksanaan_grid"] && $GLOBALS["t_invoice_pelaksanaan_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_invoice_pelaksanaan')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_pelaksanaan")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_invoice_pelaksanaan";
		}
		if ($GLOBALS["t_invoice_pelaksanaan_grid"] && $GLOBALS["t_invoice_pelaksanaan_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_invoice_pelaksanaan')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_invoice_pelaksanaan")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "t_invoice_pelaksanaan";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_invoice_pelaksanaan');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_invoice_pelaksanaan";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
		$this->invoice_id->setDbValue($rs->fields('invoice_id'));
		$this->customer_id->setDbValue($rs->fields('customer_id'));
		if (array_key_exists('EV__customer_id', $rs->fields)) {
			$this->customer_id->VirtualValue = $rs->fields('EV__customer_id'); // Set up virtual field value
		} else {
			$this->customer_id->VirtualValue = ""; // Clear value
		}
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
		$this->tgl_bayar->setDbValue($rs->fields('tgl_bayar'));
		$this->pasal23->setDbValue($rs->fields('pasal23'));
		$this->no_kwitansi->setDbValue($rs->fields('no_kwitansi'));
		$this->periode->setDbValue($rs->fields('periode'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->invoice_id->DbValue = $row['invoice_id'];
		$this->customer_id->DbValue = $row['customer_id'];
		$this->nomor->DbValue = $row['nomor'];
		$this->tanggal->DbValue = $row['tanggal'];
		$this->no_order->DbValue = $row['no_order'];
		$this->no_referensi->DbValue = $row['no_referensi'];
		$this->kegiatan->DbValue = $row['kegiatan'];
		$this->no_sertifikat->DbValue = $row['no_sertifikat'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->total->DbValue = $row['total'];
		$this->ppn->DbValue = $row['ppn'];
		$this->total_ppn->DbValue = $row['total_ppn'];
		$this->terbilang->DbValue = $row['terbilang'];
		$this->terbayar->DbValue = $row['terbayar'];
		$this->tgl_bayar->DbValue = $row['tgl_bayar'];
		$this->pasal23->DbValue = $row['pasal23'];
		$this->no_kwitansi->DbValue = $row['no_kwitansi'];
		$this->periode->DbValue = $row['periode'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Convert decimal values if posted back
		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->total_ppn->FormValue == $this->total_ppn->CurrentValue && is_numeric(ew_StrToFloat($this->total_ppn->CurrentValue)))
			$this->total_ppn->CurrentValue = ew_StrToFloat($this->total_ppn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// invoice_id
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// invoice_id
		$this->invoice_id->ViewValue = $this->invoice_id->CurrentValue;
		$this->invoice_id->ViewCustomAttributes = "";

		// customer_id
		if ($this->customer_id->VirtualValue <> "") {
			$this->customer_id->ViewValue = $this->customer_id->VirtualValue;
		} else {
		if (strval($this->customer_id->CurrentValue) <> "") {
			$sFilterWrk = "`customer_id`" . ew_SearchString("=", $this->customer_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `customer_id`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_customer`";
		$sWhereWrk = "";
		$this->customer_id->LookupFilters = array("dx1" => '`nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->customer_id->ViewValue = $this->customer_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->customer_id->ViewValue = $this->customer_id->CurrentValue;
			}
		} else {
			$this->customer_id->ViewValue = NULL;
		}
		}
		$this->customer_id->ViewCustomAttributes = "";

		// nomor
		$this->nomor->ViewValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// tanggal
		$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
		$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 7);
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
		$this->total->ViewValue = ew_FormatNumber($this->total->ViewValue, 0, -2, -2, -1);
		$this->total->CellCssStyle .= "text-align: right;";
		$this->total->ViewCustomAttributes = "";

		// ppn
		$this->ppn->ViewValue = $this->ppn->CurrentValue;
		$this->ppn->ViewValue = ew_FormatNumber($this->ppn->ViewValue, 0, -2, -2, -1);
		$this->ppn->CellCssStyle .= "text-align: right;";
		$this->ppn->ViewCustomAttributes = "";

		// total_ppn
		$this->total_ppn->ViewValue = $this->total_ppn->CurrentValue;
		$this->total_ppn->ViewValue = ew_FormatNumber($this->total_ppn->ViewValue, 0, -2, -2, -1);
		$this->total_ppn->CellCssStyle .= "text-align: right;";
		$this->total_ppn->ViewCustomAttributes = "";

		// terbilang
		$this->terbilang->ViewValue = $this->terbilang->CurrentValue;
		$this->terbilang->ViewCustomAttributes = "";

		// terbayar
		if (strval($this->terbayar->CurrentValue) <> "") {
			$this->terbayar->ViewValue = $this->terbayar->OptionCaption($this->terbayar->CurrentValue);
		} else {
			$this->terbayar->ViewValue = NULL;
		}
		$this->terbayar->ViewCustomAttributes = "";

		// tgl_bayar
		$this->tgl_bayar->ViewValue = $this->tgl_bayar->CurrentValue;
		$this->tgl_bayar->ViewValue = ew_FormatDateTime($this->tgl_bayar->ViewValue, 7);
		$this->tgl_bayar->ViewCustomAttributes = "";

		// pasal23
		if (strval($this->pasal23->CurrentValue) <> "") {
			$this->pasal23->ViewValue = $this->pasal23->OptionCaption($this->pasal23->CurrentValue);
		} else {
			$this->pasal23->ViewValue = NULL;
		}
		$this->pasal23->ViewCustomAttributes = "";

		// no_kwitansi
		$this->no_kwitansi->ViewValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewValue = ew_FormatDateTime($this->periode->ViewValue, 7);
		$this->periode->ViewCustomAttributes = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";
			$this->customer_id->TooltipValue = "";

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

			// tgl_bayar
			$this->tgl_bayar->LinkCustomAttributes = "";
			$this->tgl_bayar->HrefValue = "";
			$this->tgl_bayar->TooltipValue = "";

			// pasal23
			$this->pasal23->LinkCustomAttributes = "";
			$this->pasal23->HrefValue = "";
			$this->pasal23->TooltipValue = "";

			// no_kwitansi
			$this->no_kwitansi->LinkCustomAttributes = "";
			$this->no_kwitansi->HrefValue = "";
			$this->no_kwitansi->TooltipValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";
			$this->periode->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_t_invoice\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_invoice',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ft_invoiceview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");

		// Export detail records (t_invoice_fee)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_invoice_fee", explode(",", $this->getCurrentDetailTable()))) {
			global $t_invoice_fee;
			if (!isset($t_invoice_fee)) $t_invoice_fee = new ct_invoice_fee;
			$rsdetail = $t_invoice_fee->LoadRs($t_invoice_fee->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_invoice_fee->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (t_invoice_pelaksanaan)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_invoice_pelaksanaan", explode(",", $this->getCurrentDetailTable()))) {
			global $t_invoice_pelaksanaan;
			if (!isset($t_invoice_pelaksanaan)) $t_invoice_pelaksanaan = new ct_invoice_pelaksanaan;
			$rsdetail = $t_invoice_pelaksanaan->LoadRs($t_invoice_pelaksanaan->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_invoice_pelaksanaan->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("t_invoice_fee", $DetailTblVar)) {
				if (!isset($GLOBALS["t_invoice_fee_grid"]))
					$GLOBALS["t_invoice_fee_grid"] = new ct_invoice_fee_grid;
				if ($GLOBALS["t_invoice_fee_grid"]->DetailView) {
					$GLOBALS["t_invoice_fee_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_invoice_fee_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_invoice_fee_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_invoice_fee_grid"]->invoice_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_invoice_fee_grid"]->invoice_id->CurrentValue = $this->invoice_id->CurrentValue;
					$GLOBALS["t_invoice_fee_grid"]->invoice_id->setSessionValue($GLOBALS["t_invoice_fee_grid"]->invoice_id->CurrentValue);
				}
			}
			if (in_array("t_invoice_pelaksanaan", $DetailTblVar)) {
				if (!isset($GLOBALS["t_invoice_pelaksanaan_grid"]))
					$GLOBALS["t_invoice_pelaksanaan_grid"] = new ct_invoice_pelaksanaan_grid;
				if ($GLOBALS["t_invoice_pelaksanaan_grid"]->DetailView) {
					$GLOBALS["t_invoice_pelaksanaan_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_invoice_pelaksanaan_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_invoice_pelaksanaan_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_invoice_pelaksanaan_grid"]->invoice_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_invoice_pelaksanaan_grid"]->invoice_id->CurrentValue = $this->invoice_id->CurrentValue;
					$GLOBALS["t_invoice_pelaksanaan_grid"]->invoice_id->setSessionValue($GLOBALS["t_invoice_pelaksanaan_grid"]->invoice_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_invoicelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add('t_invoice_fee');
		$pages->Add('t_invoice_pelaksanaan');
		$this->DetailPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_invoice';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_invoice_view)) $t_invoice_view = new ct_invoice_view();

// Page init
$t_invoice_view->Page_Init();

// Page main
$t_invoice_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_invoice_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_invoice->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ft_invoiceview = new ew_Form("ft_invoiceview", "view");

// Form_CustomValidate event
ft_invoiceview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_invoiceview.ValidateRequired = true;
<?php } else { ?>
ft_invoiceview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_invoiceview.Lists["x_customer_id"] = {"LinkField":"x_customer_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_customer"};
ft_invoiceview.Lists["x_terbayar"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_invoiceview.Lists["x_terbayar"].Options = <?php echo json_encode($t_invoice->terbayar->Options()) ?>;
ft_invoiceview.Lists["x_pasal23"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_invoiceview.Lists["x_pasal23"].Options = <?php echo json_encode($t_invoice->pasal23->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_invoice->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$t_invoice_view->IsModal) { ?>
<?php if ($t_invoice->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $t_invoice_view->ExportOptions->Render("body") ?>
<?php
	foreach ($t_invoice_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$t_invoice_view->IsModal) { ?>
<?php if ($t_invoice->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_invoice_view->ShowPageHeader(); ?>
<?php
$t_invoice_view->ShowMessage();
?>
<form name="ft_invoiceview" id="ft_invoiceview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_invoice_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_invoice_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_invoice">
<?php if ($t_invoice_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($t_invoice->customer_id->Visible) { // customer_id ?>
	<tr id="r_customer_id">
		<td><span id="elh_t_invoice_customer_id"><?php echo $t_invoice->customer_id->FldCaption() ?></span></td>
		<td data-name="customer_id"<?php echo $t_invoice->customer_id->CellAttributes() ?>>
<span id="el_t_invoice_customer_id">
<span<?php echo $t_invoice->customer_id->ViewAttributes() ?>>
<?php echo $t_invoice->customer_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->nomor->Visible) { // nomor ?>
	<tr id="r_nomor">
		<td><span id="elh_t_invoice_nomor"><?php echo $t_invoice->nomor->FldCaption() ?></span></td>
		<td data-name="nomor"<?php echo $t_invoice->nomor->CellAttributes() ?>>
<span id="el_t_invoice_nomor">
<span<?php echo $t_invoice->nomor->ViewAttributes() ?>>
<?php echo $t_invoice->nomor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_t_invoice_tanggal"><?php echo $t_invoice->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $t_invoice->tanggal->CellAttributes() ?>>
<span id="el_t_invoice_tanggal">
<span<?php echo $t_invoice->tanggal->ViewAttributes() ?>>
<?php echo $t_invoice->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->no_order->Visible) { // no_order ?>
	<tr id="r_no_order">
		<td><span id="elh_t_invoice_no_order"><?php echo $t_invoice->no_order->FldCaption() ?></span></td>
		<td data-name="no_order"<?php echo $t_invoice->no_order->CellAttributes() ?>>
<span id="el_t_invoice_no_order">
<span<?php echo $t_invoice->no_order->ViewAttributes() ?>>
<?php echo $t_invoice->no_order->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->no_referensi->Visible) { // no_referensi ?>
	<tr id="r_no_referensi">
		<td><span id="elh_t_invoice_no_referensi"><?php echo $t_invoice->no_referensi->FldCaption() ?></span></td>
		<td data-name="no_referensi"<?php echo $t_invoice->no_referensi->CellAttributes() ?>>
<span id="el_t_invoice_no_referensi">
<span<?php echo $t_invoice->no_referensi->ViewAttributes() ?>>
<?php echo $t_invoice->no_referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->kegiatan->Visible) { // kegiatan ?>
	<tr id="r_kegiatan">
		<td><span id="elh_t_invoice_kegiatan"><?php echo $t_invoice->kegiatan->FldCaption() ?></span></td>
		<td data-name="kegiatan"<?php echo $t_invoice->kegiatan->CellAttributes() ?>>
<span id="el_t_invoice_kegiatan">
<span<?php echo $t_invoice->kegiatan->ViewAttributes() ?>>
<?php echo $t_invoice->kegiatan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->no_sertifikat->Visible) { // no_sertifikat ?>
	<tr id="r_no_sertifikat">
		<td><span id="elh_t_invoice_no_sertifikat"><?php echo $t_invoice->no_sertifikat->FldCaption() ?></span></td>
		<td data-name="no_sertifikat"<?php echo $t_invoice->no_sertifikat->CellAttributes() ?>>
<span id="el_t_invoice_no_sertifikat">
<span<?php echo $t_invoice->no_sertifikat->ViewAttributes() ?>>
<?php echo $t_invoice->no_sertifikat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_t_invoice_keterangan"><?php echo $t_invoice->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $t_invoice->keterangan->CellAttributes() ?>>
<span id="el_t_invoice_keterangan">
<span<?php echo $t_invoice->keterangan->ViewAttributes() ?>>
<?php echo $t_invoice->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->total->Visible) { // total ?>
	<tr id="r_total">
		<td><span id="elh_t_invoice_total"><?php echo $t_invoice->total->FldCaption() ?></span></td>
		<td data-name="total"<?php echo $t_invoice->total->CellAttributes() ?>>
<span id="el_t_invoice_total">
<span<?php echo $t_invoice->total->ViewAttributes() ?>>
<?php echo $t_invoice->total->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->ppn->Visible) { // ppn ?>
	<tr id="r_ppn">
		<td><span id="elh_t_invoice_ppn"><?php echo $t_invoice->ppn->FldCaption() ?></span></td>
		<td data-name="ppn"<?php echo $t_invoice->ppn->CellAttributes() ?>>
<span id="el_t_invoice_ppn">
<span<?php echo $t_invoice->ppn->ViewAttributes() ?>>
<?php echo $t_invoice->ppn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->total_ppn->Visible) { // total_ppn ?>
	<tr id="r_total_ppn">
		<td><span id="elh_t_invoice_total_ppn"><?php echo $t_invoice->total_ppn->FldCaption() ?></span></td>
		<td data-name="total_ppn"<?php echo $t_invoice->total_ppn->CellAttributes() ?>>
<span id="el_t_invoice_total_ppn">
<span<?php echo $t_invoice->total_ppn->ViewAttributes() ?>>
<?php echo $t_invoice->total_ppn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->terbilang->Visible) { // terbilang ?>
	<tr id="r_terbilang">
		<td><span id="elh_t_invoice_terbilang"><?php echo $t_invoice->terbilang->FldCaption() ?></span></td>
		<td data-name="terbilang"<?php echo $t_invoice->terbilang->CellAttributes() ?>>
<span id="el_t_invoice_terbilang">
<span<?php echo $t_invoice->terbilang->ViewAttributes() ?>>
<?php echo $t_invoice->terbilang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->terbayar->Visible) { // terbayar ?>
	<tr id="r_terbayar">
		<td><span id="elh_t_invoice_terbayar"><?php echo $t_invoice->terbayar->FldCaption() ?></span></td>
		<td data-name="terbayar"<?php echo $t_invoice->terbayar->CellAttributes() ?>>
<span id="el_t_invoice_terbayar">
<span<?php echo $t_invoice->terbayar->ViewAttributes() ?>>
<?php echo $t_invoice->terbayar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->tgl_bayar->Visible) { // tgl_bayar ?>
	<tr id="r_tgl_bayar">
		<td><span id="elh_t_invoice_tgl_bayar"><?php echo $t_invoice->tgl_bayar->FldCaption() ?></span></td>
		<td data-name="tgl_bayar"<?php echo $t_invoice->tgl_bayar->CellAttributes() ?>>
<span id="el_t_invoice_tgl_bayar">
<span<?php echo $t_invoice->tgl_bayar->ViewAttributes() ?>>
<?php echo $t_invoice->tgl_bayar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->pasal23->Visible) { // pasal23 ?>
	<tr id="r_pasal23">
		<td><span id="elh_t_invoice_pasal23"><?php echo $t_invoice->pasal23->FldCaption() ?></span></td>
		<td data-name="pasal23"<?php echo $t_invoice->pasal23->CellAttributes() ?>>
<span id="el_t_invoice_pasal23">
<span<?php echo $t_invoice->pasal23->ViewAttributes() ?>>
<?php echo $t_invoice->pasal23->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->no_kwitansi->Visible) { // no_kwitansi ?>
	<tr id="r_no_kwitansi">
		<td><span id="elh_t_invoice_no_kwitansi"><?php echo $t_invoice->no_kwitansi->FldCaption() ?></span></td>
		<td data-name="no_kwitansi"<?php echo $t_invoice->no_kwitansi->CellAttributes() ?>>
<span id="el_t_invoice_no_kwitansi">
<span<?php echo $t_invoice->no_kwitansi->ViewAttributes() ?>>
<?php echo $t_invoice->no_kwitansi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_invoice->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_t_invoice_periode"><?php echo $t_invoice->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $t_invoice->periode->CellAttributes() ?>>
<span id="el_t_invoice_periode">
<span<?php echo $t_invoice->periode->ViewAttributes() ?>>
<?php echo $t_invoice->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($t_invoice->getCurrentDetailTable() <> "") { ?>
<?php
	$t_invoice_view->DetailPages->ValidKeys = explode(",", $t_invoice->getCurrentDetailTable());
	$FirstActiveDetailTable = $t_invoice_view->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="t_invoice_view_details">
	<ul class="nav<?php echo $t_invoice_view->DetailPages->NavStyle() ?>">
<?php
	if (in_array("t_invoice_fee", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_fee->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_fee") {
			$FirstActiveDetailTable = "t_invoice_fee";
		}
?>
		<li<?php echo $t_invoice_view->DetailPages->TabStyle("t_invoice_fee") ?>><a href="#tab_t_invoice_fee" data-toggle="tab"><?php echo $Language->TablePhrase("t_invoice_fee", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_invoice_pelaksanaan", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_pelaksanaan->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_pelaksanaan") {
			$FirstActiveDetailTable = "t_invoice_pelaksanaan";
		}
?>
		<li<?php echo $t_invoice_view->DetailPages->TabStyle("t_invoice_pelaksanaan") ?>><a href="#tab_t_invoice_pelaksanaan" data-toggle="tab"><?php echo $Language->TablePhrase("t_invoice_pelaksanaan", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("t_invoice_fee", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_fee->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_fee") {
			$FirstActiveDetailTable = "t_invoice_fee";
		}
?>
		<div class="tab-pane<?php echo $t_invoice_view->DetailPages->PageStyle("t_invoice_fee") ?>" id="tab_t_invoice_fee">
<?php include_once "t_invoice_feegrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_invoice_pelaksanaan", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_pelaksanaan->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_pelaksanaan") {
			$FirstActiveDetailTable = "t_invoice_pelaksanaan";
		}
?>
		<div class="tab-pane<?php echo $t_invoice_view->DetailPages->PageStyle("t_invoice_pelaksanaan") ?>" id="tab_t_invoice_pelaksanaan">
<?php include_once "t_invoice_pelaksanaangrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($t_invoice->Export == "") { ?>
<script type="text/javascript">
ft_invoiceview.Init();
</script>
<?php } ?>
<?php
$t_invoice_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_invoice->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_invoice_view->Page_Terminate();
?>
