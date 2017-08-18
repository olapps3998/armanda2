<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_invoice_feeinfo.php" ?>
<?php include_once "t_invoiceinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_invoice_fee_delete = NULL; // Initialize page object first

class ct_invoice_fee_delete extends ct_invoice_fee {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{02A4272B-E84A-463D-9ED2-75398DF0A44A}";

	// Table name
	var $TableName = 't_invoice_fee';

	// Page object name
	var $PageObjName = 't_invoice_fee_delete';

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
	var $AuditTrailOnEdit = FALSE;
	var $AuditTrailOnDelete = TRUE;
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

		// Table object (t_invoice_fee)
		if (!isset($GLOBALS["t_invoice_fee"]) || get_class($GLOBALS["t_invoice_fee"]) == "ct_invoice_fee") {
			$GLOBALS["t_invoice_fee"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_invoice_fee"];
		}

		// Table object (t_invoice)
		if (!isset($GLOBALS['t_invoice'])) $GLOBALS['t_invoice'] = new ct_invoice();

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_invoice_fee', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_invoice_feelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->fee_id->SetVisibility();
		$this->harga->SetVisibility();
		$this->qty->SetVisibility();
		$this->satuan->SetVisibility();
		$this->jumlah->SetVisibility();
		$this->keterangan->SetVisibility();

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
		global $EW_EXPORT, $t_invoice_fee;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_invoice_fee);
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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_invoice_feelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_invoice_fee class, t_invoice_feeinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t_invoice_feelist.php"); // Return to list
			}
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
		$this->invoice_detail_id->setDbValue($rs->fields('invoice_detail_id'));
		$this->invoice_id->setDbValue($rs->fields('invoice_id'));
		$this->fee_id->setDbValue($rs->fields('fee_id'));
		if (array_key_exists('EV__fee_id', $rs->fields)) {
			$this->fee_id->VirtualValue = $rs->fields('EV__fee_id'); // Set up virtual field value
		} else {
			$this->fee_id->VirtualValue = ""; // Clear value
		}
		$this->harga->setDbValue($rs->fields('harga'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan->setDbValue($rs->fields('satuan'));
		$this->jumlah->setDbValue($rs->fields('jumlah'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->invoice_detail_id->DbValue = $row['invoice_detail_id'];
		$this->invoice_id->DbValue = $row['invoice_id'];
		$this->fee_id->DbValue = $row['fee_id'];
		$this->harga->DbValue = $row['harga'];
		$this->qty->DbValue = $row['qty'];
		$this->satuan->DbValue = $row['satuan'];
		$this->jumlah->DbValue = $row['jumlah'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->harga->FormValue == $this->harga->CurrentValue && is_numeric(ew_StrToFloat($this->harga->CurrentValue)))
			$this->harga->CurrentValue = ew_StrToFloat($this->harga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qty->FormValue == $this->qty->CurrentValue && is_numeric(ew_StrToFloat($this->qty->CurrentValue)))
			$this->qty->CurrentValue = ew_StrToFloat($this->qty->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jumlah->FormValue == $this->jumlah->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah->CurrentValue)))
			$this->jumlah->CurrentValue = ew_StrToFloat($this->jumlah->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// invoice_detail_id
		// invoice_id
		// fee_id
		// harga
		// qty
		// satuan
		// jumlah
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['invoice_detail_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_invoice") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_invoice_id"] <> "") {
					$GLOBALS["t_invoice"]->invoice_id->setQueryStringValue($_GET["fk_invoice_id"]);
					$this->invoice_id->setQueryStringValue($GLOBALS["t_invoice"]->invoice_id->QueryStringValue);
					$this->invoice_id->setSessionValue($this->invoice_id->QueryStringValue);
					if (!is_numeric($GLOBALS["t_invoice"]->invoice_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_invoice") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_invoice_id"] <> "") {
					$GLOBALS["t_invoice"]->invoice_id->setFormValue($_POST["fk_invoice_id"]);
					$this->invoice_id->setFormValue($GLOBALS["t_invoice"]->invoice_id->FormValue);
					$this->invoice_id->setSessionValue($this->invoice_id->FormValue);
					if (!is_numeric($GLOBALS["t_invoice"]->invoice_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "t_invoice") {
				if ($this->invoice_id->CurrentValue == "") $this->invoice_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_invoice_feelist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
		$table = 't_invoice_fee';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 't_invoice_fee';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['invoice_detail_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_invoice_fee_delete)) $t_invoice_fee_delete = new ct_invoice_fee_delete();

// Page init
$t_invoice_fee_delete->Page_Init();

// Page main
$t_invoice_fee_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_invoice_fee_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_invoice_feedelete = new ew_Form("ft_invoice_feedelete", "delete");

// Form_CustomValidate event
ft_invoice_feedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_invoice_feedelete.ValidateRequired = true;
<?php } else { ?>
ft_invoice_feedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_invoice_feedelete.Lists["x_fee_id"] = {"LinkField":"x_fee_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_fee"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $t_invoice_fee_delete->ShowPageHeader(); ?>
<?php
$t_invoice_fee_delete->ShowMessage();
?>
<form name="ft_invoice_feedelete" id="ft_invoice_feedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_invoice_fee_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_invoice_fee_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_invoice_fee">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_invoice_fee_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_invoice_fee->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_invoice_fee->fee_id->Visible) { // fee_id ?>
		<th><span id="elh_t_invoice_fee_fee_id" class="t_invoice_fee_fee_id"><?php echo $t_invoice_fee->fee_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_invoice_fee->harga->Visible) { // harga ?>
		<th><span id="elh_t_invoice_fee_harga" class="t_invoice_fee_harga"><?php echo $t_invoice_fee->harga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_invoice_fee->qty->Visible) { // qty ?>
		<th><span id="elh_t_invoice_fee_qty" class="t_invoice_fee_qty"><?php echo $t_invoice_fee->qty->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_invoice_fee->satuan->Visible) { // satuan ?>
		<th><span id="elh_t_invoice_fee_satuan" class="t_invoice_fee_satuan"><?php echo $t_invoice_fee->satuan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_invoice_fee->jumlah->Visible) { // jumlah ?>
		<th><span id="elh_t_invoice_fee_jumlah" class="t_invoice_fee_jumlah"><?php echo $t_invoice_fee->jumlah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_invoice_fee->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_t_invoice_fee_keterangan" class="t_invoice_fee_keterangan"><?php echo $t_invoice_fee->keterangan->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_invoice_fee_delete->RecCnt = 0;
$i = 0;
while (!$t_invoice_fee_delete->Recordset->EOF) {
	$t_invoice_fee_delete->RecCnt++;
	$t_invoice_fee_delete->RowCnt++;

	// Set row properties
	$t_invoice_fee->ResetAttrs();
	$t_invoice_fee->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_invoice_fee_delete->LoadRowValues($t_invoice_fee_delete->Recordset);

	// Render row
	$t_invoice_fee_delete->RenderRow();
?>
	<tr<?php echo $t_invoice_fee->RowAttributes() ?>>
<?php if ($t_invoice_fee->fee_id->Visible) { // fee_id ?>
		<td<?php echo $t_invoice_fee->fee_id->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_fee_id" class="t_invoice_fee_fee_id">
<span<?php echo $t_invoice_fee->fee_id->ViewAttributes() ?>>
<?php echo $t_invoice_fee->fee_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_invoice_fee->harga->Visible) { // harga ?>
		<td<?php echo $t_invoice_fee->harga->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_harga" class="t_invoice_fee_harga">
<span<?php echo $t_invoice_fee->harga->ViewAttributes() ?>>
<?php echo $t_invoice_fee->harga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_invoice_fee->qty->Visible) { // qty ?>
		<td<?php echo $t_invoice_fee->qty->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_qty" class="t_invoice_fee_qty">
<span<?php echo $t_invoice_fee->qty->ViewAttributes() ?>>
<?php echo $t_invoice_fee->qty->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_invoice_fee->satuan->Visible) { // satuan ?>
		<td<?php echo $t_invoice_fee->satuan->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_satuan" class="t_invoice_fee_satuan">
<span<?php echo $t_invoice_fee->satuan->ViewAttributes() ?>>
<?php echo $t_invoice_fee->satuan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_invoice_fee->jumlah->Visible) { // jumlah ?>
		<td<?php echo $t_invoice_fee->jumlah->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_jumlah" class="t_invoice_fee_jumlah">
<span<?php echo $t_invoice_fee->jumlah->ViewAttributes() ?>>
<?php echo $t_invoice_fee->jumlah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_invoice_fee->keterangan->Visible) { // keterangan ?>
		<td<?php echo $t_invoice_fee->keterangan->CellAttributes() ?>>
<span id="el<?php echo $t_invoice_fee_delete->RowCnt ?>_t_invoice_fee_keterangan" class="t_invoice_fee_keterangan">
<span<?php echo $t_invoice_fee->keterangan->ViewAttributes() ?>>
<?php echo $t_invoice_fee->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_invoice_fee_delete->Recordset->MoveNext();
}
$t_invoice_fee_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_invoice_fee_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_invoice_feedelete.Init();
</script>
<?php
$t_invoice_fee_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_invoice_fee_delete->Page_Terminate();
?>
