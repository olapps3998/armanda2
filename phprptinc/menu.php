<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new crMenu(EWR_MENUBAR_ID); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(12, "mci_Laporan", $ReportLanguage->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mi_r_rekap_invoice_all", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("11", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "r_rekap_invoice_allsmry.php", 12, "", IsLoggedIn(), FALSE);
if (IsLoggedIn()) {
	$RootMenu->AddMenuItem(-1, "mi_logout", $ReportLanguage->Phrase("Logout"), "rlogout.php", -1, "", TRUE);
} elseif (substr(ewr_ScriptName(), 0 - strlen("rlogin.php")) <> "rlogin.php") {
	$RootMenu->AddMenuItem(-1, "mi_login", $ReportLanguage->Phrase("Login"), "rlogin.php", -1, "", TRUE);
}
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
