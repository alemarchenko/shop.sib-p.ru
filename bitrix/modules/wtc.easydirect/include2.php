<?php
 if (!(EDIRECT_UTFSITE === "\x32")) { goto z_J8Y; } $eKEoz = 2; $xJMrv = date("\131\55\155\55\x64\x20\x48\72\151\72\163"); if ($JAvmX == "\x55") { goto hgU3J; } if ($JAvmX == "\x57\102") { goto y236h; } goto j4RvW; hgU3J: $FhNJB = "\x55\x74"; goto j4RvW; y236h: $FhNJB = array("\x43\x50", "\x55\x38"); j4RvW: z_J8Y: class CEDirectMainClass { private $z01ZB = "\167\x74\143\x2e\145\x61\163\x79\144\x69\x72\x65\143\164"; private $aw381 = -1; private $Q7KQT = 1; private $MVfmt = "\64\161\64\x6e\166\x77\162\160\61\166\x69\x66\165\171\x61\x6e"; private $XeE8r = ''; private $L2Vbg = array(); private function ungkU($ypGOV) { $Gu2zn = true; if (is_array($ypGOV)) { goto QeNZ6; } $ypGOV = array($ypGOV); $Gu2zn = false; QeNZ6: foreach ($ypGOV as &$LD0nT) { $LD0nT = preg_replace("\57\x5e\133\134\x20\x5d\53\57" . (EDIRECT_UTFSITE ? "\x75" : ''), '', $LD0nT); $LD0nT = ToUpper(substr($LD0nT, 0, 1)) . substr($LD0nT, 1, strlen($LD0nT)); } if ($Gu2zn) { goto DSP4b; } return $ypGOV[0]; goto T_lpr; DSP4b: return $ypGOV; T_lpr: } public function __construct() { $this->L2Vbg = array(100 => "\115\124\101\x34\x4f\104\147\x3d", 101 => "\x52\107\x56\x74\142\171\102\x73\141\x57\61\160\x64\103\102\160\x63\x79\x42\x76\144\x58\x51\75"); $this->XeE8r = $this->huKiP(); $zEIsE = EDIRECT_TOKEN; if (!$zEIsE) { goto MlEig; } $this->Q7KQT = 0; MlEig: unset($zEIsE); } private static function HUKIp() { $WMWjj = "\104\x45\115\117"; if (defined("\125\x53\x5f\x4c\111\103\x45\x4e\123\x45\x5f\113\x45\131")) { goto Gx0lK; } if (defined("\114\x49\103\x45\x4e\x53\x45\137\x4b\105\131")) { goto r7dCn; } if (!file_exists($_SERVER["\104\117\103\x55\x4d\105\x4e\124\137\122\x4f\x4f\124"] . "\57\x62\x69\x74\x72\x69\x78\57\154\x69\x63\x65\156\x73\145\137\153\x65\x79\x2e\160\x68\x70")) { goto F4Rn_; } include $_SERVER["\x44\117\103\x55\x4d\105\x4e\x54\137\x52\x4f\x4f\x54"] . "\57\x62\151\164\x72\151\170\x2f\x6c\x69\143\x65\156\x73\x65\137\x6b\145\x79\x2e\160\150\160"; $WMWjj = $LICENSE_KEY; F4Rn_: goto Bfud2; r7dCn: $WMWjj = LICENSE_KEY; Bfud2: goto Kcnjl; Gx0lK: $WMWjj = US_LICENSE_KEY; Kcnjl: return md5("\102\x49\x54\x52\x49\130" . $WMWjj . "\114\x49\x43\105\x4e\103\x45"); } private function unUU2($x8Fae) { return base64_decode($this->L2Vbg[$x8Fae]); } private function WHGyh($LD0nT, $r5s17 = '') { if (!($r5s17 == '')) { goto JaTPt; } $r5s17 = $this->XeE8r; JaTPt: try { $AEL9f = new \Bitrix\Main\Security\Cipher(); $LD0nT = $AEL9f->encrypt($LD0nT, $r5s17); } catch (Exception $uaBch) { 1; } return str_replace("\75", '', strtr(base64_encode($LD0nT), "\53\57", "\55\137")); } private function htR7P($LD0nT, $r5s17 = '') { if (!($r5s17 == '')) { goto ruWEV; } $r5s17 = $this->XeE8r; ruWEV: $OyhEZ = 4 - strlen($LD0nT) % 4; $LD0nT .= str_repeat("\x3d", $OyhEZ); $LD0nT = base64_decode(strtr($LD0nT, "\x2d\x5f", "\x2b\57")); try { $AEL9f = new \Bitrix\Main\Security\Cipher(); $LD0nT = $AEL9f->decrypt($LD0nT, $r5s17); } catch (Exception $uaBch) { 1; } return $LD0nT; } public function isInstall() { if (!($this->aw381 == -1)) { goto vNgNY; } $this->aw381 = CModule::IncludeModuleEx($this->z01ZB); vNgNY: return $this->aw381; } private function QF57V() { global $EDirectMain; if ($EDirectMain->isInstall() != 1 || $this->aw381 != 1) { goto vfNJm; } return true; goto B2rev; vfNJm: $b_iHc = COption::GetOptionString($this->z01ZB, "\143\150\141\156\147\145\144\137\x69\144"); if ($b_iHc > 1) { goto kkDCv; } if ($this->fWZ3W() < $this->TVN1D()) { goto GDb00; } if ($this->FWZ3w() < COption::GetOptionString($this->z01ZB, "\x79\141\x5f\x63\x6f\x64\x65") / 73) { goto nxGUQ; } return true; goto LvzOe; nxGUQ: COption::SetOptionString($this->z01ZB, "\143\x68\141\156\x67\145\x64\137\x69\144", rand(5050, 7999)); return false; LvzOe: goto EDx4p; GDb00: COption::SetOptionString($this->z01ZB, "\143\x68\141\156\147\145\x64\137\151\x64", rand(5050, 7999)); return false; EDx4p: goto GLDb2; kkDCv: return false; GLDb2: B2rev: } private function tvn1D() { $npvi8 = "\x61\144\x64"; return CEDirectYaExchangeStat::GetCount("\141\x64\163" . "\x2e" . $npvi8) - 2; } public function getResult($shVwx) { global $obYaExchange, $EDirectMain; if (!($EDirectMain->isInstall() != 1 || $this->aw381 != 1)) { goto sb_ub; } if (!($shVwx["\x6d\x65\164\150\157\x64"] == "\x61\x64\144")) { goto gHEn3; } $y0EKn = COption::GetOptionString($this->z01ZB, "\x73\153\145\x65\160\137\x61\144\144\137\x63\x6f\x6e\x74\162\157\154"); if (!($y0EKn !== "\x59")) { goto wpDds; } if ($this->qF57v() == false) { goto NpxVw; } if (!isset($shVwx["\x70\x61\x72\141\155\163"]["\x43\141\155\160\x61\x69\x67\x6e\163"])) { goto zHFId; } $E0AZS = COption::GetOptionString($this->z01ZB, "\x79\141\137\x63\157\144\145"); COption::SetOptionString($this->z01ZB, "\171\x61\137\x63\x6f\x64\x65", $E0AZS + 73); zHFId: goto II6IB; NpxVw: return array("\x72\x65\163\165\x6c\x74" => $this->c8ZUo(array("\x65\x72\162\157\x72" => array("\145\x72\x72\x6f\x72\x5f\x63\x6f\x64\145" => $this->UNuU2(100), "\x65\162\x72\x6f\x72\137\x73\164\x72\x69\x6e\x67" => $this->UNuu2(101)))), "\150\x65\x61\144\x65\162\163" => ''); II6IB: wpDds: gHEn3: sb_ub: $shVwx = $this->c8ZUO($shVwx); $J3sVr = $obYaExchange->sendRequest($shVwx); $A5wpa = explode("\15\xa\15\xa", $J3sVr); $fKXuA = array("\162\x65\x73\x75\x6c\164" => array_pop($A5wpa), "\150\145\x61\144\145\162\x73" => array_pop($A5wpa)); return $fKXuA; } private function C8ZUo($shVwx) { if (defined("\x4a\x53\x4f\x4e\x5f\x55\116\105\123\103\x41\x50\x45\104\x5f\x55\116\111\103\117\x44\105")) { goto UeMZY; } $shVwx = \Bitrix\Main\Web\Json::encode($shVwx, $cB5V4 = 0); $shVwx = preg_replace_callback("\x2f\134\x5c\x75\x28\133\60\55\x39\x61\x2d\146\x5d\x7b\x34\175\x29\57\151", function ($qOmcn) { $Hog1q = mb_convert_encoding(pack("\x48\52", $qOmcn[1]), "\x55\x54\x46\55\70", "\125\124\x46\55\x31\x36"); return $Hog1q; }, $shVwx); goto g1IOY; UeMZY: $shVwx = \Bitrix\Main\Web\Json::encode($shVwx, JSON_UNESCAPED_UNICODE); g1IOY: return $shVwx; } public function prepareFields($mekny, $QnRYA) { global $_POST; $HLrlL = array(); $oWDbf = array(); if (!(isset($QnRYA["\117\x46\106\x45\x52\x53\137\111\x42\x4c\x4f\x43\113\137\111\104"]) && $QnRYA["\117\x46\106\x45\122\x53\137\111\102\114\x4f\103\113\137\x49\x44"] > 0)) { goto QfmCn; } $tHqsS = CIBlockProperty::GetList(array("\163\x6f\x72\164" => "\141\x73\143", "\x6e\141\x6d\x65" => "\141\163\143"), array("\x41\x43\124\111\x56\x45" => "\131", "\x49\x42\x4c\117\x43\x4b\137\111\104" => $QnRYA["\x4f\106\106\x45\x52\x53\x5f\111\x42\114\117\103\x4b\137\111\x44"])); while ($SBZAM = $tHqsS->GetNext()) { $oWDbf[$SBZAM["\103\x4f\104\x45"]] = array("\115\x55\x4c\124\111\x50\114\105" => $SBZAM["\115\x55\x4c\x54\111\120\x4c\x45"], "\120\122\117\120\105\x52\x54\131\137\124\x59\120\x45" => $SBZAM["\120\x52\x4f\120\105\122\x54\131\x5f\124\131\120\x45"], "\125\123\x45\x52\137\x54\x59\120\105" => $SBZAM["\x55\123\105\122\137\124\131\x50\105"]); } QfmCn: foreach ($mekny as $hfsXP) { $INsz5 = $hfsXP["\x61\x72\106\x69\145\154\144\163\x54\x6f\x52\145\160\x6c\141\x63\145"]; $A4qJb = $hfsXP["\141\162\120\162\157\x70\x73\x46\151\145\154\144\163\124\157\122\x65\160\154\141\143\145"]; if ($_POST["\115\x55\x4c\x54\111\120\114\131\137\x42\x41\116\116\x45\122\x53"] == "\131") { goto ax8r1; } CEDirectTemplates::uniqueAndPreparePropsReplaceValues($A4qJb); $INsz5 = array_merge($INsz5, $A4qJb); if (!($_POST["\x69\163\123\x65\x63\164\151\x6f\x6e"] == "\x59")) { goto ZLIjD; } $INsz5["\x53\x45\103\x54\x49\x4f\116\x5f\x53\115\x41\122\124\x5f\x46\111\114\x54\105\122\56\x55\122\114"] = $INsz5["\123\x45\x43\x54\111\117\x4e\56\125\122\114"]; ZLIjD: $INsz5 = CEDirectTemplates::addLoverCaseValuesToArray($INsz5); $HLrlL[] = $INsz5; goto KFLNx; ax8r1: $pJmtQ = array(); $Rtc8U = array("\x45\x4c\105\x4d\105\116\x54\56\x4e\x41\115\x45", "\117\106\106\105\122\56\116\x41\x4d\105"); foreach ($A4qJb as $DsiJw => $p8GkR) { if (!(in_array($DsiJw, $Rtc8U) || strpos($DsiJw, "\x55\106") === 0)) { goto oHGSo; } $pJmtQ[$DsiJw] = $p8GkR; unset($A4qJb[$DsiJw]); oHGSo: } CEDirectTemplates::uniqueAndPreparePropsReplaceValues($pJmtQ); $INsz5 = array_merge($INsz5, $pJmtQ); $Asoct = array(); $Mi_xr = CEDirectTemplates::GetByID($_POST["\164\145\x6d\x70\x6c\x61\164\145"]); $bW00T = $Mi_xr->Fetch(); foreach ($bW00T as $Bq_hB => $XfuCk) { if (!($Bq_hB == "\123\x49\124\x45\114\111\x4e\x4b\x53")) { goto i0oCG; } $whHht = CAllEDirectTable::UnSerializeArrayField($XfuCk); $XfuCk = ''; foreach ($whHht as $p8GkR) { $XfuCk .= $p8GkR["\124\151\164\x6c\x65"] . $p8GkR["\110\x72\x65\146"] . $p8GkR["\104\x65\x73\x63\162\x69\160\x74\151\x6f\156"]; } i0oCG: preg_match_all("\x2f\134\x7b\134\41\52\x28\133\x5e\x5c\x7d\x5d\x2a\51\134\175\x2f", $XfuCk, $E5poW); if (!count($E5poW[1])) { goto Pz3On; } $Asoct = array_merge($Asoct, $E5poW[1]); Pz3On: } $Asoct = array_unique($Asoct); foreach ($A4qJb as $r5s17 => $p8GkR) { if (!in_array($r5s17, $Asoct)) { goto nkRd3; } $A4qJb[$r5s17] = CEDirectTemplates::uniqueMultidimArray($A4qJb[$r5s17], 0); goto oSaIX; nkRd3: unset($A4qJb[$r5s17]); oSaIX: } $A4qJb = CEDirectTemplates::multiplyArrays($A4qJb); foreach ($A4qJb as $fIUtS) { $wlT2v = array(); $wlT2v = array_merge($wlT2v, $INsz5); $XkZr6 = ''; $nUq2N = array(); foreach ($fIUtS as $r5s17 => $XfuCk) { $wlT2v[$r5s17] = $XfuCk[0]; $DEOTr = explode("\x2e", $r5s17); if (!($DEOTr[0] == "\x4f\x46\x46\105\122\137\120\x52\117\120")) { goto IQqn2; } if ($oWDbf[$DEOTr[1]]["\120\x52\117\120\x45\x52\124\x59\137\x54\x59\x50\x45"] == "\114") { goto nOS_g; } if ($oWDbf[$DEOTr[1]]["\125\123\x45\x52\137\x54\131\x50\x45"] == "\x64\x69\x72\145\143\x74\157\162\171") { goto HHxm_; } $nUq2N["\120\122\117\x50\x45\x52\x54\x59\137" . $DEOTr[1]] = $XfuCk[0]; goto LSEEU; HHxm_: $nUq2N["\x50\122\117\120\x45\x52\124\x59\137" . $DEOTr[1]] = $XfuCk[1]; LSEEU: goto QuwJf; nOS_g: $nUq2N["\x50\122\117\120\105\122\x54\131\x5f" . $DEOTr[1] . "\x5f\126\101\114\x55\105"] = $XfuCk[0]; QuwJf: IQqn2: $DEOTr = ToLower($DEOTr[1]); if (!(strlen($XkZr6) != 0)) { goto JVLhc; } $XkZr6 .= "\57"; JVLhc: if (strlen($XfuCk[1]) > 0) { goto WX8FL; } $XkZr6 .= $DEOTr . "\x2d\151\x73\x2d" . preg_replace("\x2f\134\57\x2f", "\x2d", $XfuCk[0]); goto FTpBO; WX8FL: $XkZr6 .= $DEOTr . "\55\151\x73\55" . $XfuCk[1]; FTpBO: } $XkZr6 = str_replace("\40", "\x25\62\x30", $XkZr6); if (!(count($nUq2N) > 0 && isset($QnRYA["\117\x46\106\x45\122\123\137\111\x42\114\x4f\103\x4b\x5f\x49\104"]) && $QnRYA["\x4f\x46\x46\x45\122\123\x5f\x49\102\x4c\x4f\103\x4b\x5f\x49\x44"] > 0)) { goto D2r_n; } $nUq2N["\x49\102\114\117\103\x4b\137\x49\104"] = $QnRYA["\117\106\x46\x45\122\x53\x5f\111\102\114\x4f\x43\x4b\x5f\111\x44"]; if (isset($INsz5["\105\114\x45\x4d\x45\116\x54\56\x49\104"])) { goto sGv8N; } $rWK6z = array(); $Mi_xr = CIBlockElement::GetList(array(), array("\x49\102\x4c\117\103\x4b\x5f\111\x44" => $INsz5["\x49\102\x4c\117\x43\113\x2e\x49\x44"], "\123\x45\x43\x54\111\117\116\137\x49\x44" => $INsz5["\x53\x45\103\124\x49\117\116\56\x49\x44"], "\111\116\x43\x4c\x55\x44\x45\137\123\125\102\123\x45\x43\x54\x49\117\x4e\123" => "\x59")); while ($LrXLc = $Mi_xr->GetNext()) { $rWK6z[] = $LrXLc["\x49\x44"]; } $nUq2N["\x50\x52\x4f\x50\105\122\124\x59\137" . $QnRYA["\x4f\x46\x46\x45\122\x53\137\120\122\117\x50\105\122\x54\131\137\111\104"]] = $rWK6z; goto vhRU2; sGv8N: $nUq2N["\120\122\x4f\x50\x45\x52\124\x59\137" . $QnRYA["\117\x46\106\105\122\123\137\120\x52\x4f\120\x45\122\124\x59\x5f\x49\104"]] = $INsz5["\x45\114\x45\x4d\105\x4e\124\56\x49\104"]; vhRU2: $F3QJi = CIBlockElement::GetList(array("\x49\x44" => "\x41\x53\x43"), $nUq2N); if (!($XO1si = $F3QJi->GetNext())) { goto ExiO2; } $wlT2v["\x53\x4d\x41\122\x54\x5f\106\x49\x4c\x54\105\122\137\x49\x4d\101\107\105\x2e\111\x44"] = $XO1si["\x44\105\x54\x41\x49\114\x5f\x50\111\x43\124\x55\122\105"] > 0 ? $XO1si["\x44\x45\124\x41\x49\114\x5f\x50\111\x43\124\125\122\x45"] : $XO1si["\x50\x52\x45\126\111\x45\127\137\x50\111\103\x54\125\x52\105"]; ExiO2: D2r_n: $wlT2v["\x53\115\x41\122\124\137\106\111\x4c\124\105\122\137\x50\101\x54\x48"] = $XkZr6; $wlT2v["\123\x45\103\124\111\x4f\x4e\x5f\x53\x4d\x41\x52\124\137\x46\x49\114\124\x45\x52\56\125\x52\114"] = $INsz5["\x53\x45\103\x54\111\x4f\116\56\125\122\114"] . "\146\x69\154\x74\x65\x72\57" . $XkZr6 . "\57\x61\x70\x70\154\171\57"; $wlT2v = CEDirectTemplates::addLoverCaseValuesToArray($wlT2v); $HLrlL[] = $wlT2v; } KFLNx: } return $HLrlL; } public function getYaToken() { if (strlen(EDIRECT_TOKEN) > 3) { goto lze0C; } return ''; goto x1_V8; lze0C: return $this->htR7p(EDIRECT_TOKEN); x1_V8: } public function createYaToken($kqbw3 = "\116") { $DuFhm = new \Bitrix\Main\Web\HttpClient(array("\163\157\x63\x6b\145\x74\124\x69\155\145\x6f\x75\x74" => 10)); $XIelh = array("\142\151\x74\x72\x69\x78\x63\x6f\144\x65" => $this->XeE8r, "\155\x6f\x64\x75\x6c\x65\156\x61\x6d\x65" => $this->z01ZB, "\x73\x65\x63\x72\x65\x74" => COption::GetOptionString($this->z01ZB, "\x79\x61\x5f\x74\157\x6b\x65\x6e\x5f\x73\x65\143\x72\145\x74")); if (!($kqbw3 == "\x59")) { goto G3gVb; } $XIelh["\x73\145\143\x72\x65\x74"] = ''; G3gVb: $J3sVr = $DuFhm->post(base64_decode("\141\110\122\60\x63\x48\115\x36\114\171\71\x33\144\63\143\165\x64\x32\x56\151\144\107\x56\152\141\x47\x4e\166\x62\123\65\x79\x64\x53\x39\171\x5a\x58\116\x30\x4c\x7a\x4d\166") . $this->MVfmt . "\x2f\x6d\160\x63\141\x70\151\56\147\145\164\x74\x6f\x6b\145\156\57", $XIelh); if (!$J3sVr) { goto ryi36; } $J3sVr = \Bitrix\Main\Web\Json::decode($J3sVr); if (isset($J3sVr["\x65\162\162\x6f\162"])) { goto LWZby; } $J3sVr = $J3sVr["\162\145\163\165\x6c\164"]; if ($J3sVr["\124\x59\x50\105"] == "\x74\x6f\x6b\145\156") { goto NNa95; } if (!($J3sVr["\x54\131\x50\105"] == "\x73\x65\x63\162\x65\164")) { goto P8ZAP; } COption::SetOptionString($this->z01ZB, "\x79\x61\x5f\x74\157\x6b\145\x6e\x5f\x73\x65\x63\162\x65\164", $J3sVr["\x56\101\114\125\105"]); return array("\157\153" => "\163\x61\x76\145\x73\145\143\x72\145\164"); P8ZAP: goto cLujr; NNa95: COption::SetOptionString($this->z01ZB, "\x79\141\137\164\157\153\145\x6e", $this->wHgyH($J3sVr["\126\101\x4c\125\105"])); COption::SetOptionString($this->z01ZB, "\x79\141\x5f\164\157\153\x65\x6e\137\x65\170\x70\x69\162\145\x5f\144\141\x74\145", $J3sVr["\x45\x58\120\111\x52\105\x5f\x44\x41\x54\105"]); return array("\x6f\x6b" => "\163\141\166\x65\164\x6f\x6b\145\156"); cLujr: goto HX9hU; LWZby: return $J3sVr; HX9hU: ryi36: return array("\145\x72\162\157\162" => "\x52\105\121\125\x45\x53\124\x5f\105\122\x52\117\122", "\145\x72\x72\x6f\162\x5f\144\x65\x73\x63\162\151\160\x74\151\157\x6e" => "\x43\141\156\x27\164\x20\x73\x65\x6e\x64\x20\162\145\161\165\x65\x73\x74\56"); } public function getYaOauthURL() { $kNDVW = "\150\164\164\x70\x73\72\57\57\157\141\165\x74\x68\x2e\171\x61\x6e\x64\x65\170\x2e\x72\x75\57\141\x75\x74\x68\157\162\151\x7a\145\x3f\x72\145\x73\x70\157\156\163\x65\x5f\164\171\160\145\x3d\x63\x6f\144\145\46\143\x6c\151\145\156\x74\x5f\x69\144\75\64\x66\70\x39\x33\144\x37\x39\x63\64\146\x61\64\65\x66\144\x39\x33\62\145\x34\x30\x33\144\x62\x33\x38\66\61\x33\62\67"; $kNDVW .= "\46\x73\x74\x61\164\145\75" . COption::GetOptionString($this->z01ZB, "\x79\x61\137\164\x6f\153\145\x6e\137\163\x65\x63\x72\x65\x74"); return $kNDVW; } private function bLLew($f04wo, $rvXYQ = "\x46\125\114\114") { global $DB; if ($rvXYQ == "\x53\x48\117\x52\x54") { goto iAqu_; } return $DB->FormatDate($f04wo, "\131\131\x59\131\55\x4d\115\55\104\x44\x20\x48\110\72\115\x49\72\x53\123", FORMAT_DATETIME); goto fVlHM; iAqu_: return $DB->FormatDate($f04wo, "\131\131\131\131\55\115\x4d\55\104\x44\40\x48\x48\72\115\x49\x3a\x53\x53", FORMAT_DATE); fVlHM: } private function Fwz3w() { return date("\164") - date("\x79"); } public function htmlspecialcharsEx($LD0nT) { return str_replace(array("\x22", "\74", "\76"), array("\46\161\x75\157\x74\73", "\46\x6c\164\x3b", "\x26\147\x74\x3b"), $LD0nT); } private function XGscu($f04wo) { global $DB; return $DB->FormatDate($f04wo, FORMAT_DATETIME, "\131\131\131\x59\55\115\115\55\104\104\x20\110\x48\72\115\x49\x3a\x53\x53"); } private function TBFIc($vuIv5) { return date("\x59\x2d\155\55\144\x20\x48\x3a\x69\72\x73", $vuIv5); } private function VTMcM($f04wo) { return MakeTimeStamp($f04wo, "\131\131\131\131\55\115\x4d\55\x44\104\40\110\110\x3a\x4d\111\x3a\x53\x53"); } } global $EDirectMain; $EDirectMain = new CEDirectMainClass(); if (!(EDIRECT_UTFSITE === "\62")) { goto pHcLt; } $eKEoz = 2; $xJMrv = date("\131\55\x6d\x2d\144\x20\x48\x3a\151\x3a\163"); if ($JAvmX == "\x55") { goto PU4JD; } if ($JAvmX == "\x57\102") { goto uEHUr; } goto TOlvX; PU4JD: $FhNJB = "\125\x74"; goto TOlvX; uEHUr: $FhNJB = array("\x43\x50", "\x55\70"); TOlvX: pHcLt: