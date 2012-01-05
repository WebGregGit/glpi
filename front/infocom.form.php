<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

$ic = new Infocom();

if (isset($_GET["add"])) {
   $ic->check(-1,'w',$_GET);

   $newID = $ic->add($_GET, false);
   Event::log($newID, "infocom", 4, "financial",
               sprintf(__('%1$s adds the item %2%s'), $_SESSION["glpiname"], $newID));
   Html::back();

} else if (isset($_POST["delete"])) {
   $ic->check($_POST["id"],'w');

   $ic->delete($_POST);
   Event::log($_POST["id"], "infocom", 4, "financial", 
            //TRANS: %s is the user login
            sprintf(__('%s purges the item'), $_SESSION["glpiname"]));         
   Html::back();

} else if (isset($_POST["update"])) {
   $ic->check($_POST["id"],'w');

   $ic->update($_POST);
   Event::log($_POST["id"], "infocom", 4, "financial", 
            //TRANS: %s is the user login
            sprintf(__('%s updates the item'), $_SESSION["glpiname"]));         
   Html::back();

} else {
   Session::checkRight("infocom", "r");

   Html::popHeader(__('Financial and administrative information'), $_SERVER['PHP_SELF']);

   if (isset($_GET["id"])) {
      $ic->getFromDB($_GET["id"]);
      $_GET["itemtype"] = $ic->fields["itemtype"];
      $_GET["items_id"] = $ic->fields["items_id"];
   }
   $item = false;
   if (isset($_GET["itemtype"]) && ($item = getItemForItemtype($_GET["itemtype"]))) {
      if (!isset($_GET["items_id"]) || !$item->getFromDB($_GET["items_id"])) {
         $item = false;
      }
   }

   if (isset($_GET["update"]) && $_GET["update"]==1) {
      $withtemplate = 0;
   } else {
      $withtemplate = 2;
   }
   Infocom::showForItem($item, $withtemplate);

   Html::popFooter();
}
?>