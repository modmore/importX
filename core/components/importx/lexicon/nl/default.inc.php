<?php
/*
 * importX
 *
 * Copyright 2011-2014 by Mark Hamstra (https://www.markhamstra.com)
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
// Last update: 16/5/2011
// Translator: Mark Hamstra
$_lang['importx'] = 'ImportX';
$_lang['importx.description'] = 'Importeer CSV gegevens als nieuwe resources.';
$_lang['importx.desc'] = 'Deze addon kan gebruikt worden om CSV gegevens te importeren naar nieuwe MODX resources. Kies een bovenliggende resource, en voer je CSV gegevens in. Het standaard scheidingsteken is een puntkomma.';
$_lang['importx.form.basic'] = 'Basis import';
$_lang['importx.startbutton'] = 'Begin import';
$_lang['importx.importsuccess'] = 'Het importeren van resources is gelukt.';
$_lang['importx.importfailure'] = 'Oeps, er is iets fout gegaan tijdens het importeren.';
$_lang['importx.tab.input'] = 'CSV Invoer';
$_lang['importx.tab.input.desc'] = 'Plak of upload hier je invoer, waarbij aparte resources gescheiden zijn door een enter en de verschillende velden met een puntkomma (;) of het scheidingsteken wat je beneden aangegeven hebt.';
$_lang['importx.tab.input.sep'] = 'Wanneer je CSV invoer een ander scheidingsteken gebruikt, kan je dat hier aangeven. Als je dit leeg laat wordt er een puntkomma gebruikt.';
$_lang['importx.csv'] = 'CSV';
$_lang['importx.parent'] = 'Bovenliggend:'; 
$_lang['importx.csvfile'] = 'CSV bestand upload';
$_lang['importx.separator'] = 'Scheidingsteken';
$_lang['importx.tab.settings'] = 'Standaard instellingen';
$_lang['importx.tab.settings.desc'] = 'Geef de standaard instellingen aan. Deze kunnen eventueel per regel worden aangegeven door het veld te benoemen in de eerste regel.';
$_lang['importx.err.noparent'] = 'Geef een bovenliggend resource ID op, of geef 0 aan om naar de root van de site te importeren.';
$_lang['importx.err.parentnotnumeric'] = 'Bovenliggend resource moet een cijfer zijn of een context key.';
$_lang['importx.err.parentlessthanzero'] = 'Bovenliggend resource moet een positief cijfer zijn.';
$_lang['importx.err.nocsv'] = 'Vul je CSV gegevens in om deze te kunnen verwerken.';
$_lang['importx.err.fileuploadfailed'] = 'Fout tijdens het lezen van het geuploade bestand.';
$_lang['importx.err.invalidcsv'] = 'Foutieve CSV gegevens ontvangen.';
$_lang['importx.err.notenoughdata'] = 'Niet genoeg gegevens ontvangen. Er wordt tenminste een rij met veldnamen en een rij met waardes verwacht.';
$_lang['importx.err.elementmismatch'] = 'Element velden kloppen niet. Check en corrigeer de syntax op regel [[+line]].';
$_lang['importx.err.savefailed'] = 'Er heeft een onverwachte fout plaatsgevonden tijdens het opslaan van de resource.';
$_lang['importx.err.invalidheader'] = 'Je veldnamenrij heeft een of meerdere ongeldige velden. De ongeldig(e) veld(en) zijn: [[+fields]].';
$_lang['importx.err.intexpected'] = '[[+field]] ([[+int]] zou een cijfer moeten zijn)';
$_lang['importx.err.tvdoesnotexist'] = '[[+field]] (er bestaat geen TV met ID [[+id]])';
$_lang['importx.log.runningpreimport'] = 'Pre-import controles worden uitgevoerd op de gegevens...';
$_lang['importx.log.fileuploadfound'] = 'CSV bestand upload overschrijft handmatige invoer. Bestandsnaam: [[+filename]]';
$_lang['importx.log.preimportclean'] = 'Geen fouten gevonden in pre-import controles. Import waardes voorbereiden...';
$_lang['importx.log.importvaluesclean'] = 'Geen fouten in import waardes gevonden. [[+count]] resources gevonden. Importeren...';
$_lang['importx.log.complete'] = 'Importeren is voltooid. Er zijn [[+count]] resources geimporteerd.';
?>
