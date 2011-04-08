<?php
/*
 * modImport
 *
 * Copyright 2011 by Mark Hamstra (http://www.markhamstra.nl)
 * Development funded by Working Party, a Sydney based digital agency.
 *
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */

$_lang['modimport'] = 'modImport';
$_lang['modimport.description'] = 'Import your CSV formatted data into new Resources';
$_lang['modimport.desc'] = 'This component can be used to import CSV formatted files into new MODX Resources. Simply choose a parent to use and enter your CSV formatted information. The default seperator is: ;';
$_lang['modimport.form.basic'] = 'Basic import';
$_lang['modimport.startbutton'] = 'Start import';
$_lang['modimport.importsuccess'] = 'Succesfully imported resources into MODX.';
$_lang['modimport.importfailure'] = 'Oops, an error occured while importing your resources.';
$_lang['modimport.parent'] = 'Import to parent';
$_lang['modimport.csv'] = 'CSV values';
$_lang['modimport.err.noparent'] = 'Please choose a Parent to import to. Specify 0 to put new resources in the root of the site.';
$_lang['modimport.err.parentnotnumeric'] = 'Parent not numeric.';
$_lang['modimport.err.parentlessthanzero'] = 'Parent needs to be a positive integer.';
$_lang['modimport.err.nocsv'] = 'Please add your CSV values in order for them to be processed.';
$_lang['modimport.err.invalidcsv'] = 'Invalid CSV value posted.';
$_lang['modimport.err.notenoughdata'] = 'Not enough data given. Expecting at least one header row, and one data row.';
$_lang['modimport.err.elementmismatch'] = 'Element count do not match. Please check for correct syntax on line [[+line]].';
$_lang['modimport.err.savefailed'] = 'An unexpected error occured saving the resource.';
$_lang['modimport.err.invalidheader'] = 'Your header has one or more invalid fieldnames. The invalid fieldname(s) is (are): [[+fields]].';
?>