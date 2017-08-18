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

$t_invoice_edit = NULL; // Initialize page object first

class ct_invoice_edit extends ct_invoice {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{02A4272B-E84A-463D-9ED2-75398DF0A44A}";

	// Table name
	var $TableName = 't_invoice';

	// Page object name
	var $PageObjName = 't_invoice_edit';

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
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = TRUE;
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

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->customer_id->SetVisibility();
		$this->nomor->SetVisibility();
		$this->tanggal->SetVisibility();
		$this->no_order->SetVisibility();
		$this->no_referensi->SetVisibility();
		$this->kegiatan->SetVisibility();
		$this->no_sertifikat->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->ppn->SetVisibility();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 't_invoice_fee'
			if (@$_POST["grid"] == "ft_invoice_feegrid") {
				if (!isset($GLOBALS["t_invoice_fee_grid"])) $GLOBALS["t_invoice_fee_grid"] = new ct_invoice_fee_grid;
				$GLOBALS["t_invoice_fee_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_invoice_pelaksanaan'
			if (@$_POST["grid"] == "ft_invoice_pelaksanaangrid") {
				if (!isset($GLOBALS["t_invoice_pelaksanaan_grid"])) $GLOBALS["t_invoice_pelaksanaan_grid"] = new ct_invoice_pelaksanaan_grid;
				$GLOBALS["t_invoice_pelaksanaan_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DetailPages; // Detail pages object

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["invoice_id"] <> "") {
			$this->invoice_id->setQueryStringValue($_GET["invoice_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->invoice_id->CurrentValue == "") {
			$this->Page_Terminate("t_invoicelist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_invoicelist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_invoicelist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->customer_id->FldIsDetailKey) {
			$this->customer_id->setFormValue($objForm->GetValue("x_customer_id"));
		}
		if (!$this->nomor->FldIsDetailKey) {
			$this->nomor->setFormValue($objForm->GetValue("x_nomor"));
		}
		if (!$this->tanggal->FldIsDetailKey) {
			$this->tanggal->setFormValue($objForm->GetValue("x_tanggal"));
			$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 7);
		}
		if (!$this->no_order->FldIsDetailKey) {
			$this->no_order->setFormValue($objForm->GetValue("x_no_order"));
		}
		if (!$this->no_referensi->FldIsDetailKey) {
			$this->no_referensi->setFormValue($objForm->GetValue("x_no_referensi"));
		}
		if (!$this->kegiatan->FldIsDetailKey) {
			$this->kegiatan->setFormValue($objForm->GetValue("x_kegiatan"));
		}
		if (!$this->no_sertifikat->FldIsDetailKey) {
			$this->no_sertifikat->setFormValue($objForm->GetValue("x_no_sertifikat"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->ppn->FldIsDetailKey) {
			$this->ppn->setFormValue($objForm->GetValue("x_ppn"));
		}
		if (!$this->terbayar->FldIsDetailKey) {
			$this->terbayar->setFormValue($objForm->GetValue("x_terbayar"));
		}
		if (!$this->tgl_bayar->FldIsDetailKey) {
			$this->tgl_bayar->setFormValue($objForm->GetValue("x_tgl_bayar"));
			$this->tgl_bayar->CurrentValue = ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 7);
		}
		if (!$this->pasal23->FldIsDetailKey) {
			$this->pasal23->setFormValue($objForm->GetValue("x_pasal23"));
		}
		if (!$this->no_kwitansi->FldIsDetailKey) {
			$this->no_kwitansi->setFormValue($objForm->GetValue("x_no_kwitansi"));
		}
		if (!$this->periode->FldIsDetailKey) {
			$this->periode->setFormValue($objForm->GetValue("x_periode"));
			$this->periode->CurrentValue = ew_UnFormatDateTime($this->periode->CurrentValue, 7);
		}
		if (!$this->invoice_id->FldIsDetailKey)
			$this->invoice_id->setFormValue($objForm->GetValue("x_invoice_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->invoice_id->CurrentValue = $this->invoice_id->FormValue;
		$this->customer_id->CurrentValue = $this->customer_id->FormValue;
		$this->nomor->CurrentValue = $this->nomor->FormValue;
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 7);
		$this->no_order->CurrentValue = $this->no_order->FormValue;
		$this->no_referensi->CurrentValue = $this->no_referensi->FormValue;
		$this->kegiatan->CurrentValue = $this->kegiatan->FormValue;
		$this->no_sertifikat->CurrentValue = $this->no_sertifikat->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->ppn->CurrentValue = $this->ppn->FormValue;
		$this->terbayar->CurrentValue = $this->terbayar->FormValue;
		$this->tgl_bayar->CurrentValue = $this->tgl_bayar->FormValue;
		$this->tgl_bayar->CurrentValue = ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 7);
		$this->pasal23->CurrentValue = $this->pasal23->FormValue;
		$this->no_kwitansi->CurrentValue = $this->no_kwitansi->FormValue;
		$this->periode->CurrentValue = $this->periode->FormValue;
		$this->periode->CurrentValue = ew_UnFormatDateTime($this->periode->CurrentValue, 7);
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

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";
			$this->ppn->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// customer_id
			$this->customer_id->EditCustomAttributes = "";
			if (trim(strval($this->customer_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`customer_id`" . ew_SearchString("=", $this->customer_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `customer_id`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_customer`";
			$sWhereWrk = "";
			$this->customer_id->LookupFilters = array("dx1" => '`nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->customer_id->ViewValue = $this->customer_id->DisplayValue($arwrk);
			} else {
				$this->customer_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->customer_id->EditValue = $arwrk;

			// nomor
			$this->nomor->EditAttrs["class"] = "form-control";
			$this->nomor->EditCustomAttributes = "";
			$this->nomor->EditValue = ew_HtmlEncode($this->nomor->CurrentValue);
			$this->nomor->PlaceHolder = ew_RemoveHtml($this->nomor->FldCaption());

			// tanggal
			$this->tanggal->EditAttrs["class"] = "form-control";
			$this->tanggal->EditCustomAttributes = "";
			$this->tanggal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal->CurrentValue, 7));
			$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

			// no_order
			$this->no_order->EditAttrs["class"] = "form-control";
			$this->no_order->EditCustomAttributes = "";
			$this->no_order->EditValue = ew_HtmlEncode($this->no_order->CurrentValue);
			$this->no_order->PlaceHolder = ew_RemoveHtml($this->no_order->FldCaption());

			// no_referensi
			$this->no_referensi->EditAttrs["class"] = "form-control";
			$this->no_referensi->EditCustomAttributes = "";
			$this->no_referensi->EditValue = ew_HtmlEncode($this->no_referensi->CurrentValue);
			$this->no_referensi->PlaceHolder = ew_RemoveHtml($this->no_referensi->FldCaption());

			// kegiatan
			$this->kegiatan->EditAttrs["class"] = "form-control";
			$this->kegiatan->EditCustomAttributes = "";
			$this->kegiatan->EditValue = ew_HtmlEncode($this->kegiatan->CurrentValue);
			$this->kegiatan->PlaceHolder = ew_RemoveHtml($this->kegiatan->FldCaption());

			// no_sertifikat
			$this->no_sertifikat->EditAttrs["class"] = "form-control";
			$this->no_sertifikat->EditCustomAttributes = "";
			$this->no_sertifikat->EditValue = ew_HtmlEncode($this->no_sertifikat->CurrentValue);
			$this->no_sertifikat->PlaceHolder = ew_RemoveHtml($this->no_sertifikat->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// ppn
			$this->ppn->EditAttrs["class"] = "form-control";
			$this->ppn->EditCustomAttributes = "";
			$this->ppn->EditValue = ew_HtmlEncode($this->ppn->CurrentValue);
			$this->ppn->PlaceHolder = ew_RemoveHtml($this->ppn->FldCaption());

			// terbayar
			$this->terbayar->EditCustomAttributes = "";
			$this->terbayar->EditValue = $this->terbayar->Options(FALSE);

			// tgl_bayar
			$this->tgl_bayar->EditAttrs["class"] = "form-control";
			$this->tgl_bayar->EditCustomAttributes = "";
			$this->tgl_bayar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_bayar->CurrentValue, 7));
			$this->tgl_bayar->PlaceHolder = ew_RemoveHtml($this->tgl_bayar->FldCaption());

			// pasal23
			$this->pasal23->EditCustomAttributes = "";
			$this->pasal23->EditValue = $this->pasal23->Options(FALSE);

			// no_kwitansi
			$this->no_kwitansi->EditAttrs["class"] = "form-control";
			$this->no_kwitansi->EditCustomAttributes = "";
			$this->no_kwitansi->EditValue = ew_HtmlEncode($this->no_kwitansi->CurrentValue);
			$this->no_kwitansi->PlaceHolder = ew_RemoveHtml($this->no_kwitansi->FldCaption());

			// periode
			$this->periode->EditAttrs["class"] = "form-control";
			$this->periode->EditCustomAttributes = "";
			$this->periode->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->periode->CurrentValue, 7));
			$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

			// Edit refer script
			// customer_id

			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";

			// nomor
			$this->nomor->LinkCustomAttributes = "";
			$this->nomor->HrefValue = "";

			// tanggal
			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";

			// no_order
			$this->no_order->LinkCustomAttributes = "";
			$this->no_order->HrefValue = "";

			// no_referensi
			$this->no_referensi->LinkCustomAttributes = "";
			$this->no_referensi->HrefValue = "";

			// kegiatan
			$this->kegiatan->LinkCustomAttributes = "";
			$this->kegiatan->HrefValue = "";

			// no_sertifikat
			$this->no_sertifikat->LinkCustomAttributes = "";
			$this->no_sertifikat->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// ppn
			$this->ppn->LinkCustomAttributes = "";
			$this->ppn->HrefValue = "";

			// terbayar
			$this->terbayar->LinkCustomAttributes = "";
			$this->terbayar->HrefValue = "";

			// tgl_bayar
			$this->tgl_bayar->LinkCustomAttributes = "";
			$this->tgl_bayar->HrefValue = "";

			// pasal23
			$this->pasal23->LinkCustomAttributes = "";
			$this->pasal23->HrefValue = "";

			// no_kwitansi
			$this->no_kwitansi->LinkCustomAttributes = "";
			$this->no_kwitansi->HrefValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->customer_id->FldIsDetailKey && !is_null($this->customer_id->FormValue) && $this->customer_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->customer_id->FldCaption(), $this->customer_id->ReqErrMsg));
		}
		if (!$this->nomor->FldIsDetailKey && !is_null($this->nomor->FormValue) && $this->nomor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nomor->FldCaption(), $this->nomor->ReqErrMsg));
		}
		if (!$this->tanggal->FldIsDetailKey && !is_null($this->tanggal->FormValue) && $this->tanggal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggal->FldCaption(), $this->tanggal->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->tanggal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ppn->FormValue)) {
			ew_AddMessage($gsFormError, $this->ppn->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->tgl_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_bayar->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->periode->FormValue)) {
			ew_AddMessage($gsFormError, $this->periode->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_invoice_fee", $DetailTblVar) && $GLOBALS["t_invoice_fee"]->DetailEdit) {
			if (!isset($GLOBALS["t_invoice_fee_grid"])) $GLOBALS["t_invoice_fee_grid"] = new ct_invoice_fee_grid(); // get detail page object
			$GLOBALS["t_invoice_fee_grid"]->ValidateGridForm();
		}
		if (in_array("t_invoice_pelaksanaan", $DetailTblVar) && $GLOBALS["t_invoice_pelaksanaan"]->DetailEdit) {
			if (!isset($GLOBALS["t_invoice_pelaksanaan_grid"])) $GLOBALS["t_invoice_pelaksanaan_grid"] = new ct_invoice_pelaksanaan_grid(); // get detail page object
			$GLOBALS["t_invoice_pelaksanaan_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// customer_id
			$this->customer_id->SetDbValueDef($rsnew, $this->customer_id->CurrentValue, 0, $this->customer_id->ReadOnly);

			// nomor
			$this->nomor->SetDbValueDef($rsnew, $this->nomor->CurrentValue, "", $this->nomor->ReadOnly);

			// tanggal
			$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 7), ew_CurrentDate(), $this->tanggal->ReadOnly);

			// no_order
			$this->no_order->SetDbValueDef($rsnew, $this->no_order->CurrentValue, NULL, $this->no_order->ReadOnly);

			// no_referensi
			$this->no_referensi->SetDbValueDef($rsnew, $this->no_referensi->CurrentValue, NULL, $this->no_referensi->ReadOnly);

			// kegiatan
			$this->kegiatan->SetDbValueDef($rsnew, $this->kegiatan->CurrentValue, NULL, $this->kegiatan->ReadOnly);

			// no_sertifikat
			$this->no_sertifikat->SetDbValueDef($rsnew, $this->no_sertifikat->CurrentValue, NULL, $this->no_sertifikat->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, $this->keterangan->ReadOnly);

			// ppn
			$this->ppn->SetDbValueDef($rsnew, $this->ppn->CurrentValue, NULL, $this->ppn->ReadOnly);

			// terbayar
			$this->terbayar->SetDbValueDef($rsnew, $this->terbayar->CurrentValue, NULL, $this->terbayar->ReadOnly);

			// tgl_bayar
			$this->tgl_bayar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_bayar->CurrentValue, 7), NULL, $this->tgl_bayar->ReadOnly);

			// pasal23
			$this->pasal23->SetDbValueDef($rsnew, $this->pasal23->CurrentValue, NULL, $this->pasal23->ReadOnly);

			// no_kwitansi
			$this->no_kwitansi->SetDbValueDef($rsnew, $this->no_kwitansi->CurrentValue, NULL, $this->no_kwitansi->ReadOnly);

			// periode
			$this->periode->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->periode->CurrentValue, 7), NULL, $this->periode->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("t_invoice_fee", $DetailTblVar) && $GLOBALS["t_invoice_fee"]->DetailEdit) {
						if (!isset($GLOBALS["t_invoice_fee_grid"])) $GLOBALS["t_invoice_fee_grid"] = new ct_invoice_fee_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_invoice_fee"); // Load user level of detail table
						$EditRow = $GLOBALS["t_invoice_fee_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_invoice_pelaksanaan", $DetailTblVar) && $GLOBALS["t_invoice_pelaksanaan"]->DetailEdit) {
						if (!isset($GLOBALS["t_invoice_pelaksanaan_grid"])) $GLOBALS["t_invoice_pelaksanaan_grid"] = new ct_invoice_pelaksanaan_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_invoice_pelaksanaan"); // Load user level of detail table
						$EditRow = $GLOBALS["t_invoice_pelaksanaan_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
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
				if ($GLOBALS["t_invoice_fee_grid"]->DetailEdit) {
					$GLOBALS["t_invoice_fee_grid"]->CurrentMode = "edit";
					$GLOBALS["t_invoice_fee_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_invoice_pelaksanaan_grid"]->DetailEdit) {
					$GLOBALS["t_invoice_pelaksanaan_grid"]->CurrentMode = "edit";
					$GLOBALS["t_invoice_pelaksanaan_grid"]->CurrentAction = "gridedit";

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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
		case "x_customer_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `customer_id` AS `LinkFld`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_customer`";
			$sWhereWrk = "{filter}";
			$this->customer_id->LookupFilters = array("dx1" => '`nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`customer_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_invoice';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['invoice_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_invoice_edit)) $t_invoice_edit = new ct_invoice_edit();

// Page init
$t_invoice_edit->Page_Init();

// Page main
$t_invoice_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_invoice_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_invoiceedit = new ew_Form("ft_invoiceedit", "edit");

// Validate form
ft_invoiceedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_customer_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice->customer_id->FldCaption(), $t_invoice->customer_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice->nomor->FldCaption(), $t_invoice->nomor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_invoice->tanggal->FldCaption(), $t_invoice->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ppn");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice->ppn->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_bayar");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice->tgl_bayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_invoice->periode->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_invoiceedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_invoiceedit.ValidateRequired = true;
<?php } else { ?>
ft_invoiceedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_invoiceedit.Lists["x_customer_id"] = {"LinkField":"x_customer_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_customer"};
ft_invoiceedit.Lists["x_terbayar"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_invoiceedit.Lists["x_terbayar"].Options = <?php echo json_encode($t_invoice->terbayar->Options()) ?>;
ft_invoiceedit.Lists["x_pasal23"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_invoiceedit.Lists["x_pasal23"].Options = <?php echo json_encode($t_invoice->pasal23->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_invoice_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_invoice_edit->ShowPageHeader(); ?>
<?php
$t_invoice_edit->ShowMessage();
?>
<form name="ft_invoiceedit" id="ft_invoiceedit" class="<?php echo $t_invoice_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_invoice_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_invoice_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_invoice">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_invoice_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_invoice->customer_id->Visible) { // customer_id ?>
	<div id="r_customer_id" class="form-group">
		<label id="elh_t_invoice_customer_id" for="x_customer_id" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->customer_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->customer_id->CellAttributes() ?>>
<span id="el_t_invoice_customer_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_customer_id"><?php echo (strval($t_invoice->customer_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_invoice->customer_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_invoice->customer_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_customer_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_invoice" data-field="x_customer_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_invoice->customer_id->DisplayValueSeparatorAttribute() ?>" name="x_customer_id" id="x_customer_id" value="<?php echo $t_invoice->customer_id->CurrentValue ?>"<?php echo $t_invoice->customer_id->EditAttributes() ?>>
<?php if (AllowAdd(CurrentProjectID() . "t_customer")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_invoice->customer_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_customer_id',url:'t_customeraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_customer_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_invoice->customer_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_customer_id" id="s_x_customer_id" value="<?php echo $t_invoice->customer_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_invoice->customer_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->nomor->Visible) { // nomor ?>
	<div id="r_nomor" class="form-group">
		<label id="elh_t_invoice_nomor" for="x_nomor" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->nomor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->nomor->CellAttributes() ?>>
<span id="el_t_invoice_nomor">
<input type="text" data-table="t_invoice" data-field="x_nomor" name="x_nomor" id="x_nomor" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice->nomor->getPlaceHolder()) ?>" value="<?php echo $t_invoice->nomor->EditValue ?>"<?php echo $t_invoice->nomor->EditAttributes() ?>>
</span>
<?php echo $t_invoice->nomor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_t_invoice_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->tanggal->CellAttributes() ?>>
<span id="el_t_invoice_tanggal">
<input type="text" data-table="t_invoice" data-field="x_tanggal" data-format="7" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($t_invoice->tanggal->getPlaceHolder()) ?>" value="<?php echo $t_invoice->tanggal->EditValue ?>"<?php echo $t_invoice->tanggal->EditAttributes() ?>>
<?php if (!$t_invoice->tanggal->ReadOnly && !$t_invoice->tanggal->Disabled && !isset($t_invoice->tanggal->EditAttrs["readonly"]) && !isset($t_invoice->tanggal->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_invoiceedit", "x_tanggal", 7);
</script>
<?php } ?>
</span>
<?php echo $t_invoice->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->no_order->Visible) { // no_order ?>
	<div id="r_no_order" class="form-group">
		<label id="elh_t_invoice_no_order" for="x_no_order" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->no_order->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->no_order->CellAttributes() ?>>
<span id="el_t_invoice_no_order">
<input type="text" data-table="t_invoice" data-field="x_no_order" name="x_no_order" id="x_no_order" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice->no_order->getPlaceHolder()) ?>" value="<?php echo $t_invoice->no_order->EditValue ?>"<?php echo $t_invoice->no_order->EditAttributes() ?>>
</span>
<?php echo $t_invoice->no_order->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->no_referensi->Visible) { // no_referensi ?>
	<div id="r_no_referensi" class="form-group">
		<label id="elh_t_invoice_no_referensi" for="x_no_referensi" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->no_referensi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->no_referensi->CellAttributes() ?>>
<span id="el_t_invoice_no_referensi">
<input type="text" data-table="t_invoice" data-field="x_no_referensi" name="x_no_referensi" id="x_no_referensi" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice->no_referensi->getPlaceHolder()) ?>" value="<?php echo $t_invoice->no_referensi->EditValue ?>"<?php echo $t_invoice->no_referensi->EditAttributes() ?>>
</span>
<?php echo $t_invoice->no_referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->kegiatan->Visible) { // kegiatan ?>
	<div id="r_kegiatan" class="form-group">
		<label id="elh_t_invoice_kegiatan" for="x_kegiatan" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->kegiatan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->kegiatan->CellAttributes() ?>>
<span id="el_t_invoice_kegiatan">
<textarea data-table="t_invoice" data-field="x_kegiatan" name="x_kegiatan" id="x_kegiatan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice->kegiatan->getPlaceHolder()) ?>"<?php echo $t_invoice->kegiatan->EditAttributes() ?>><?php echo $t_invoice->kegiatan->EditValue ?></textarea>
</span>
<?php echo $t_invoice->kegiatan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->no_sertifikat->Visible) { // no_sertifikat ?>
	<div id="r_no_sertifikat" class="form-group">
		<label id="elh_t_invoice_no_sertifikat" for="x_no_sertifikat" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->no_sertifikat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->no_sertifikat->CellAttributes() ?>>
<span id="el_t_invoice_no_sertifikat">
<textarea data-table="t_invoice" data-field="x_no_sertifikat" name="x_no_sertifikat" id="x_no_sertifikat" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice->no_sertifikat->getPlaceHolder()) ?>"<?php echo $t_invoice->no_sertifikat->EditAttributes() ?>><?php echo $t_invoice->no_sertifikat->EditValue ?></textarea>
</span>
<?php echo $t_invoice->no_sertifikat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_t_invoice_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->keterangan->CellAttributes() ?>>
<span id="el_t_invoice_keterangan">
<textarea data-table="t_invoice" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_invoice->keterangan->getPlaceHolder()) ?>"<?php echo $t_invoice->keterangan->EditAttributes() ?>><?php echo $t_invoice->keterangan->EditValue ?></textarea>
</span>
<?php echo $t_invoice->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->ppn->Visible) { // ppn ?>
	<div id="r_ppn" class="form-group">
		<label id="elh_t_invoice_ppn" for="x_ppn" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->ppn->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->ppn->CellAttributes() ?>>
<span id="el_t_invoice_ppn">
<input type="text" data-table="t_invoice" data-field="x_ppn" name="x_ppn" id="x_ppn" size="30" placeholder="<?php echo ew_HtmlEncode($t_invoice->ppn->getPlaceHolder()) ?>" value="<?php echo $t_invoice->ppn->EditValue ?>"<?php echo $t_invoice->ppn->EditAttributes() ?>>
</span>
<?php echo $t_invoice->ppn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->terbayar->Visible) { // terbayar ?>
	<div id="r_terbayar" class="form-group">
		<label id="elh_t_invoice_terbayar" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->terbayar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->terbayar->CellAttributes() ?>>
<span id="el_t_invoice_terbayar">
<div id="tp_x_terbayar" class="ewTemplate"><input type="radio" data-table="t_invoice" data-field="x_terbayar" data-value-separator="<?php echo $t_invoice->terbayar->DisplayValueSeparatorAttribute() ?>" name="x_terbayar" id="x_terbayar" value="{value}"<?php echo $t_invoice->terbayar->EditAttributes() ?>></div>
<div id="dsl_x_terbayar" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_invoice->terbayar->RadioButtonListHtml(FALSE, "x_terbayar") ?>
</div></div>
</span>
<?php echo $t_invoice->terbayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->tgl_bayar->Visible) { // tgl_bayar ?>
	<div id="r_tgl_bayar" class="form-group">
		<label id="elh_t_invoice_tgl_bayar" for="x_tgl_bayar" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->tgl_bayar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->tgl_bayar->CellAttributes() ?>>
<span id="el_t_invoice_tgl_bayar">
<input type="text" data-table="t_invoice" data-field="x_tgl_bayar" data-format="7" name="x_tgl_bayar" id="x_tgl_bayar" placeholder="<?php echo ew_HtmlEncode($t_invoice->tgl_bayar->getPlaceHolder()) ?>" value="<?php echo $t_invoice->tgl_bayar->EditValue ?>"<?php echo $t_invoice->tgl_bayar->EditAttributes() ?>>
<?php if (!$t_invoice->tgl_bayar->ReadOnly && !$t_invoice->tgl_bayar->Disabled && !isset($t_invoice->tgl_bayar->EditAttrs["readonly"]) && !isset($t_invoice->tgl_bayar->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_invoiceedit", "x_tgl_bayar", 7);
</script>
<?php } ?>
</span>
<?php echo $t_invoice->tgl_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->pasal23->Visible) { // pasal23 ?>
	<div id="r_pasal23" class="form-group">
		<label id="elh_t_invoice_pasal23" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->pasal23->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->pasal23->CellAttributes() ?>>
<span id="el_t_invoice_pasal23">
<div id="tp_x_pasal23" class="ewTemplate"><input type="radio" data-table="t_invoice" data-field="x_pasal23" data-value-separator="<?php echo $t_invoice->pasal23->DisplayValueSeparatorAttribute() ?>" name="x_pasal23" id="x_pasal23" value="{value}"<?php echo $t_invoice->pasal23->EditAttributes() ?>></div>
<div id="dsl_x_pasal23" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_invoice->pasal23->RadioButtonListHtml(FALSE, "x_pasal23") ?>
</div></div>
</span>
<?php echo $t_invoice->pasal23->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->no_kwitansi->Visible) { // no_kwitansi ?>
	<div id="r_no_kwitansi" class="form-group">
		<label id="elh_t_invoice_no_kwitansi" for="x_no_kwitansi" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->no_kwitansi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->no_kwitansi->CellAttributes() ?>>
<span id="el_t_invoice_no_kwitansi">
<input type="text" data-table="t_invoice" data-field="x_no_kwitansi" name="x_no_kwitansi" id="x_no_kwitansi" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_invoice->no_kwitansi->getPlaceHolder()) ?>" value="<?php echo $t_invoice->no_kwitansi->EditValue ?>"<?php echo $t_invoice->no_kwitansi->EditAttributes() ?>>
</span>
<?php echo $t_invoice->no_kwitansi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_invoice->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_t_invoice_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $t_invoice->periode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_invoice->periode->CellAttributes() ?>>
<span id="el_t_invoice_periode">
<input type="text" data-table="t_invoice" data-field="x_periode" data-format="7" name="x_periode" id="x_periode" placeholder="<?php echo ew_HtmlEncode($t_invoice->periode->getPlaceHolder()) ?>" value="<?php echo $t_invoice->periode->EditValue ?>"<?php echo $t_invoice->periode->EditAttributes() ?>>
<?php if (!$t_invoice->periode->ReadOnly && !$t_invoice->periode->Disabled && !isset($t_invoice->periode->EditAttrs["readonly"]) && !isset($t_invoice->periode->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_invoiceedit", "x_periode", 7);
</script>
<?php } ?>
</span>
<?php echo $t_invoice->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_invoice" data-field="x_invoice_id" name="x_invoice_id" id="x_invoice_id" value="<?php echo ew_HtmlEncode($t_invoice->invoice_id->CurrentValue) ?>">
<?php if ($t_invoice->getCurrentDetailTable() <> "") { ?>
<?php
	$t_invoice_edit->DetailPages->ValidKeys = explode(",", $t_invoice->getCurrentDetailTable());
	$FirstActiveDetailTable = $t_invoice_edit->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="tabbable" id="t_invoice_edit_details">
	<ul class="nav<?php echo $t_invoice_edit->DetailPages->NavStyle() ?>">
<?php
	if (in_array("t_invoice_fee", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_fee->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_fee") {
			$FirstActiveDetailTable = "t_invoice_fee";
		}
?>
		<li<?php echo $t_invoice_edit->DetailPages->TabStyle("t_invoice_fee") ?>><a href="#tab_t_invoice_fee" data-toggle="tab"><?php echo $Language->TablePhrase("t_invoice_fee", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("t_invoice_pelaksanaan", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_pelaksanaan->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_pelaksanaan") {
			$FirstActiveDetailTable = "t_invoice_pelaksanaan";
		}
?>
		<li<?php echo $t_invoice_edit->DetailPages->TabStyle("t_invoice_pelaksanaan") ?>><a href="#tab_t_invoice_pelaksanaan" data-toggle="tab"><?php echo $Language->TablePhrase("t_invoice_pelaksanaan", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul>
	<div class="tab-content">
<?php
	if (in_array("t_invoice_fee", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_fee->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_fee") {
			$FirstActiveDetailTable = "t_invoice_fee";
		}
?>
		<div class="tab-pane<?php echo $t_invoice_edit->DetailPages->PageStyle("t_invoice_fee") ?>" id="tab_t_invoice_fee">
<?php include_once "t_invoice_feegrid.php" ?>
		</div>
<?php } ?>
<?php
	if (in_array("t_invoice_pelaksanaan", explode(",", $t_invoice->getCurrentDetailTable())) && $t_invoice_pelaksanaan->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_invoice_pelaksanaan") {
			$FirstActiveDetailTable = "t_invoice_pelaksanaan";
		}
?>
		<div class="tab-pane<?php echo $t_invoice_edit->DetailPages->PageStyle("t_invoice_pelaksanaan") ?>" id="tab_t_invoice_pelaksanaan">
<?php include_once "t_invoice_pelaksanaangrid.php" ?>
		</div>
<?php } ?>
	</div>
</div>
</div>
<?php } ?>
<?php if (!$t_invoice_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_invoice_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_invoiceedit.Init();
</script>
<?php
$t_invoice_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_invoice_edit->Page_Terminate();
?>
