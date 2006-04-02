<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2006 by the INDEPNET Development Team.
 
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/
 
// Based on:
// IRMA, Information Resource-Management and Administration
// Christian Bauer 
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

include ("_relpos.php");
// FUNCTIONS Phone


function titlephones(){
                GLOBAL  $lang,$HTMLRel;
                echo "<div align='center'><table border='0'><tr><td>";
                echo "<img src=\"".$HTMLRel."pics/phones.png\" alt='".$lang["phones"][0]."' title='".$lang["phones"][0]."'></td><td><a  class='icon_consol' href=\"phones-add-select.php\"><b>".$lang["phones"][0]."</b></a>";
                echo "</td>";
                echo "<td><a class='icon_consol' href='".$HTMLRel."setup/setup-templates.php?type=".PHONE_TYPE."'>".$lang["common"][8]."</a></td>";
                echo "</tr></table></div>";
}

function showPhoneOnglets($target,$withtemplate,$actif){
	global $lang, $HTMLRel;
	
	$template="";
	if(!empty($withtemplate)){
		$template="&amp;withtemplate=$withtemplate";
	}

	echo "<div id='barre_onglets'><ul id='onglet'>";
	echo "<li "; if ($actif=="1"){ echo "class='actif'";} echo  "><a href='$target&amp;onglet=1$template'>".$lang["title"][26]."</a></li>";
	echo "<li "; if ($actif=="4") {echo "class='actif'";} echo "><a href='$target&amp;onglet=4$template'>".$lang["Menu"][26]."</a></li>";
	echo "<li "; if ($actif=="5") {echo "class='actif'";} echo "><a href='$target&amp;onglet=5$template'>".$lang["title"][25]."</a></li>";
	if(empty($withtemplate)){
	echo "<li "; if ($actif=="6") {echo "class='actif'";} echo "><a href='$target&amp;onglet=6$template'>".$lang["title"][28]."</a></li>";
	echo "<li "; if ($actif=="7") {echo "class='actif'";} echo "><a href='$target&amp;onglet=7$template'>".$lang["title"][34]."</a></li>";
	echo "<li "; if ($actif=="10") {echo "class='actif'";} echo "><a href='$target&amp;onglet=10$template'>".$lang["title"][37]."</a></li>";
	echo "<li "; if ($actif=="12") {echo "class='actif'";} echo "><a href='$target&amp;onglet=12$template'>".$lang["title"][38]."</a></li>";

	display_plugin_headings($target,PHONE_TYPE,$withtemplate,$actif);

	echo "<li class='invisible'>&nbsp;</li>";
	echo "<li "; if ($actif=="-1") {echo "class='actif'";} echo "><a href='$target&amp;onglet=-1$template'>".$lang["title"][29]."</a></li>";
	}
	
	echo "<li class='invisible'>&nbsp;</li>";
	
	if (empty($withtemplate)&&preg_match("/\?ID=([0-9]+)/",$target,$ereg)){
	$ID=$ereg[1];
	$next=getNextItem("glpi_phones",$ID);
	$prev=getPreviousItem("glpi_phones",$ID);
	$cleantarget=preg_replace("/\?ID=([0-9]+)/","",$target);
	if ($prev>0) echo "<li><a href='$cleantarget?ID=$prev'><img src=\"".$HTMLRel."pics/left.png\" alt='".$lang["buttons"][12]."' title='".$lang["buttons"][12]."'></a></li>";
	if ($next>0) echo "<li><a href='$cleantarget?ID=$next'><img src=\"".$HTMLRel."pics/right.png\" alt='".$lang["buttons"][11]."' title='".$lang["buttons"][11]."'></a></li>";
	if (isReservable(PHONE_TYPE,$ID)){
		echo "<li class='invisible'>&nbsp;</li>";
		echo "<li "; if ($actif=="11") {echo "class='actif'";} echo "><a href='$target&amp;onglet=11$template'>".$lang["title"][35]."</a></li>";
	}
	}
	echo "</ul></div>";
	
}


function showPhoneForm ($target,$ID,$withtemplate='') {

	GLOBAL $cfg_glpi, $lang,$HTMLRel;

	$mon = new Phone;

	$mon_spotted = false;

	if(empty($ID) && $withtemplate == 1) {
		if($mon->getEmpty()) $mon_spotted = true;
	} else {
		if($mon->getfromDB($ID)) $mon_spotted = true;
	}

	if($mon_spotted) {
		if(!empty($withtemplate) && $withtemplate == 2) {
			$template = "newcomp";
			$datestring = $lang["computers"][14].": ";
			$date = convDateTime(date("Y-m-d H:i:s"));
		} elseif(!empty($withtemplate) && $withtemplate == 1) { 
			$template = "newtemplate";
			$datestring = $lang["computers"][14].": ";
			$date = convDateTime(date("Y-m-d H:i:s"));
		} else {
			$datestring = $lang["common"][26].": ";
			$date = convDateTime($mon->fields["date_mod"]);
			$template = false;
		}


	echo "<div align='center'>";
	echo "<form method='post' name=form action=\"$target\">";
	if(strcmp($template,"newtemplate") === 0) {
		echo "<input type=\"hidden\" name=\"is_template\" value=\"1\" />";
	}

	echo "<table width='950' class='tab_cadre' cellpadding='2'>";

		echo "<tr><th align='center' >";

		
		
		if(!$template) {
			echo $lang["phones"][29].": ".$mon->fields["ID"];
		}elseif (strcmp($template,"newcomp") === 0) {
			echo $lang["phones"][30].": ".$mon->fields["tplname"];
			echo "<input type='hidden' name='tplname' value='".$mon->fields["tplname"]."'>";
		}elseif (strcmp($template,"newtemplate") === 0) {
			echo $lang["common"][6]."&nbsp;: ";
			autocompletionTextField("tplname","glpi_phones","tplname",$mon->fields["tplname"],20);	
		}
		
		echo "</th><th  align='center'>".$datestring.$date;
		if (!$template&&!empty($mon->fields['tplname']))
			echo "&nbsp;&nbsp;&nbsp;(".$lang["common"][13].": ".$mon->fields['tplname'].")";
		echo "</th></tr>";

	echo "<tr><td class='tab_bg_1' valign='top'>";

	echo "<table cellpadding='1' cellspacing='0' border='0'>\n";

	echo "<tr><td>".$lang["common"][16].":	</td>";
	echo "<td>";
	autocompletionTextField("name","glpi_phones","name",$mon->fields["name"],20);		
	echo "</td></tr>";

	echo "<tr><td>".$lang["common"][15].": 	</td><td>";
		dropdownValue("glpi_dropdown_locations", "location", $mon->fields["location"]);
	echo "</td></tr>";

	echo "<tr class='tab_bg_1'><td>".$lang["common"][10].": 	</td><td colspan='2'>";
		dropdownUsersID("tech_num", $mon->fields["tech_num"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["common"][21].":	</td><td>";
	autocompletionTextField("contact_num","glpi_phones","contact_num",$mon->fields["contact_num"],20);		
	echo "</td></tr>";

	echo "<tr><td>".$lang["common"][18].":	</td><td>";
	autocompletionTextField("contact","glpi_phones","contact",$mon->fields["contact"],20);		
	echo "</td></tr>";

		if (!$template){
		echo "<tr><td>".$lang["reservation"][24].":</td><td><b>";
		showReservationForm(PHONE_TYPE,$ID);
		echo "</b></td></tr>";
		}
		
	echo "<tr><td>".$lang["phones"][33].":</td><td>";
	echo "<select name='is_global'>";
	echo "<option value='0' ".(!$mon->fields["is_global"]?" selected":"").">".$lang["phones"][32]."</option>";
	echo "<option value='1' ".($mon->fields["is_global"]?" selected":"").">".$lang["phones"][31]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td>".$lang["common"][17].": 	</td><td>";
		dropdownValue("glpi_type_phones", "type", $mon->fields["type"]);
	echo "</td></tr>";

	echo "<tr><td>".$lang["common"][22].": 	</td><td>";
		dropdownValue("glpi_dropdown_model_phones", "model", $mon->fields["model"]);
	echo "</td></tr>";

	echo "</table>";

	echo "</td>\n";	
	echo "<td class='tab_bg_1' valign='top'>";

	echo "<table cellpadding='1' cellspacing='0' border='0'>";

		
	echo "<tr><td>".$lang["phones"][36].":</td><td>";
		dropdownValue("glpi_dropdown_phone_power", "power", $mon->fields["power"]);
	echo "</td></tr>";

	
	echo "<tr class='tab_bg_1'><td>".$lang["common"][5].": 	</td><td colspan='2'>";
		dropdownValue("glpi_enterprises","FK_glpi_enterprise",$mon->fields["FK_glpi_enterprise"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["phones"][18].":</td><td>";
	autocompletionTextField("brand","glpi_phones","brand",$mon->fields["brand"],20);		
	echo "</td></tr>";

	
	echo "<tr><td>".$lang["common"][19].":	</td><td>";
	autocompletionTextField("serial","glpi_phones","serial",$mon->fields["serial"],20);		
	echo "</td></tr>";

	echo "<tr><td>".$lang["common"][20].":</td><td>";
	autocompletionTextField("otherserial","glpi_phones","otherserial",$mon->fields["otherserial"],20);		
	echo "</td></tr>";


	echo "<tr><td>".$lang["phones"][35].":	</td><td>";
	autocompletionTextField("firmware","glpi_phones","firmware",$mon->fields["firmware"],20);		
	echo "</td></tr>";

		
		echo "<tr><td>".$lang["state"][0].":</td><td>";
		$si=new StateItem();
		$t=0;
		if ($template) $t=1;
		$si->getfromDB(PHONE_TYPE,$mon->fields["ID"],$t);
		dropdownValue("glpi_dropdown_state", "state",$si->fields["state"]);
		echo "</td></tr>";

	echo "<tr><td>".$lang["phones"][40].":	</td><td>";
	autocompletionTextField("number_line","glpi_phones","number_line",$mon->fields["number_line"],20);		
	echo "</td></tr>";


		echo "<tr><td>".$lang["phones"][37].": </td><td>";

		// micro?
		echo "<table border='0' cellpadding='2' cellspacing='0'><tr>";
		echo "<td>";
		if ($mon->fields["flags_casque"] == 1) {
			echo "<input type='checkbox' name='flags_casque' value='1' checked>";
		} else {
			echo "<input type='checkbox' name='flags_casque' value='1'>";
		}
		echo "</td><td>".$lang["phones"][38]."</td>";

		// hp?
		echo "<td>";
		if ($mon->fields["flags_hp"] == 1) {
			echo "<input type='checkbox' name='flags_hp' value='1' checked>";
		} else {
			echo "<input type='checkbox' name='flags_hp' value='1'>";
		}
		echo "</td><td>".$lang["phones"][39]."</td>";

		echo "</tr><tr></table>";
	
	echo "</table>";
	echo "</td>\n";	
	echo "</tr>";
	echo "<tr>";
	echo "<td class='tab_bg_1' valign='top' colspan='2'>";

	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'><tr><td valign='top'>";
	echo $lang["common"][25].":	</td>";
	echo "<td align='center'><textarea cols='35' rows='4' name='comments' >".$mon->fields["comments"]."</textarea>";
	echo "</td></tr></table>";

	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";

	if ($template) {

			if (empty($ID)||$withtemplate==2){
			echo "<td class='tab_bg_2' align='center' colspan='2'>\n";
			echo "<input type='hidden' name='ID' value=$ID>";
			echo "<input type='submit' name='add' value=\"".$lang["buttons"][8]."\" class='submit'>";
			echo "</td>\n";
			} else {
			echo "<td class='tab_bg_2' align='center' colspan='2'>\n";
			echo "<input type='hidden' name='ID' value=$ID>";
			echo "<input type='submit' name='update' value=\"".$lang["buttons"][7]."\" class='submit'>";
			echo "</td>\n";
			}


	} else {

		echo "<td class='tab_bg_2' valign='top' align='center'>";
		echo "<input type='hidden' name='ID' value=\"$ID\">\n";
		echo "<input type='submit' name='update' value=\"".$lang["buttons"][7]."\" class='submit'>";
		echo "</td>";
		echo "<td class='tab_bg_2' valign='top'>\n";
		echo "<div align='center'>";
		if ($mon->fields["deleted"]=='N')
		echo "<input type='submit' name='delete' value=\"".$lang["buttons"][6]."\" class='submit'>";
		else {
		echo "<input type='submit' name='restore' value=\"".$lang["buttons"][21]."\" class='submit'>";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$lang["buttons"][22]."\" class='submit'>";
		}
		echo "</div>";
		echo "</td>";
	}
		echo "</tr>";

		echo "</table></form></div>";
	
		return true;	
	}
	else {
                echo "<div align='center'><b>".$lang["phones"][17]."</b></div>";
                return false;
        }

}


function updatePhone($input) {
	// Update a Phone in the database

	$mon = new Phone;
	$mon->getFromDB($input["ID"]);

	// set new date and make sure it gets updated
	$updates[0]= "date_mod";
	$mon->fields["date_mod"] = date("Y-m-d H:i:s");

	// Get all flags and fill with 0 if unchecked in form
	foreach ($mon->fields as $key => $val) {
		if (eregi("\.*flag\.*",$key)) {
			if (!isset($input[$key])) {
				$input[$key]=0;
			}
		}
	}

	// Fill the update-array with changes
	$x=1;
	foreach ($input as $key => $val) {
		if (array_key_exists($key,$mon->fields) && $mon->fields[$key] != $input[$key]) {
			// Debut logs
			constructHistory($input["ID"],PHONE_TYPE,$key,$mon->fields[$key],$input[$key]);
			// Fin des logs
			$mon->fields[$key] = $input[$key];
			$updates[$x] = $key;
			$x++;
		}
	}
	
	if (isset($input["state"]))
	if (isset($input["is_template"])&&$input["is_template"]==1)
	updateState(PHONE_TYPE,$input["ID"],$input["state"],1);
	else updateState(PHONE_TYPE,$input["ID"],$input["state"]);

	$mon->updateInDB($updates);
	
	do_hook_function("item_update",array("type"=>PHONE_TYPE, "ID" => $input["ID"]));

}

function addPhone($input) {
	// Add Phone, nasty hack until we get PHP4-array-functions
	global $db;
	$mon = new Phone;

	$oldID=$input["ID"];

	// dump status
	unset($input["ID"]);
	unset($input["add"]);
	
	// Manage state
	$state=-1;
	if (isset($input["state"])){
		$state=$input["state"];
		unset($input["state"]);
	}
 	
	// set new date.
 	$mon->fields["date_mod"] = date("Y-m-d H:i:s");
	
	// fill array for udpate
	foreach ($input as $key => $val) {
		if ($key[0]!='_'&&(!isset($mon->fields[$key]) || $mon->fields[$key] != $input[$key])) {
			$mon->fields[$key] = $input[$key];
		}
	}

	$newID=$mon->addToDB();

	
	// Add state
	if ($state>0){
		if (isset($input["is_template"])&&$input["is_template"]==1)
			updateState(PHONE_TYPE,$newID,$state,1);
		else updateState(PHONE_TYPE,$newID,$state);
	}
	
	// ADD Infocoms
	$ic= new Infocom();
	if ($ic->getFromDB(PHONE_TYPE,$oldID)){
		$ic->fields["FK_device"]=$newID;
		unset ($ic->fields["ID"]);
		$ic->addToDB();
	}
	
		// ADD Ports
	$query="SELECT ID from glpi_networking_ports WHERE on_device='$oldID' AND device_type='".PHONE_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result)){
			$np= new Netport();
			$np->getFromDB($data["ID"]);
			unset($np->fields["ID"]);
			unset($np->fields["ifaddr"]);
			unset($np->fields["ifmac"]);
			unset($np->fields["netpoint"]);
			$np->fields["on_device"]=$newID;
			$np->addToDB();
			}
	}

	// ADD Contract				
	$query="SELECT FK_contract from glpi_contract_device WHERE FK_device='$oldID' AND device_type='".PHONE_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result))
			addDeviceContract($data["FK_contract"],PHONE_TYPE,$newID);
	}
	
	// ADD Documents			
	$query="SELECT FK_doc from glpi_doc_device WHERE FK_device='$oldID' AND device_type='".PHONE_TYPE."';";
	$result=$db->query($query);
	if ($db->numrows($result)>0){
		
		while ($data=$db->fetch_array($result))
			addDeviceDocument($data["FK_doc"],PHONE_TYPE,$newID);
	}


	do_hook_function("item_add",array("type"=>PHONE_TYPE, "ID" => $newID));

	return $newID;
}

function deletePhone($input,$force=0) {
	// Delete Printer
	
	$mon = new Phone;
	$mon->deleteFromDB($input["ID"],$force);
	if ($force)
		do_hook_function("item_purge",array("type"=>PHONE_TYPE, "ID" => $input["ID"]));
	else 
		do_hook_function("item_delete",array("type"=>PHONE_TYPE, "ID" => $input["ID"]));
}

function restorePhone($input) {
	// Restore Phone
	
	$ct = new Phone;
	$ct->restoreInDB($input["ID"]);
	do_hook_function("item_restore",array("type"=>PHONE_TYPE, "ID" => $input["ID"]));
} 
 	
?>
