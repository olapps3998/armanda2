<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(12, "mmci_Laporan", $ReportLanguage->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(11, "mmi_r_rekap_invoice_all", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("11", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "r_rekap_invoice_allsmry.php", 12, "", IsLoggedIn(), FALSE);
if (IsLoggedIn()) {
	$RootMenu->AddMenuItem(-1, "mmi_logout", $ReportLanguage->Phrase("Logout"), "rlogout.php", -1, "", TRUE);
} elseif (substr(ewr_ScriptName(), 0 - strlen("rlogin.php")) <> "rlogin.php") {
	$RootMenu->AddMenuItem(-1, "mmi_login", $ReportLanguage->Phrase("Login"), "rlogin.php", -1, "", TRUE);
}
$RootMenu->Render();
?>
<!-- End Main Menu -->
