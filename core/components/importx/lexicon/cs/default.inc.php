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
 * @author modxcms.cz
 * @updated 2011-05-21
 */
// Last update: 21/5/2011
// Translator: Hansek
$_lang['importx'] = 'ImportX';
$_lang['importx.description'] = 'Import CSV dat jako nové dokumenty';
$_lang['importx.desc'] = 'Tato komponenta umožňuje naimportovat CSV data jako nové MODx dokumenty. Stačí vybrat, pod kterého rodiče má být nový dokument zařazen a zadat oddělená data. Výchozím oddělovačem je středník.';
$_lang['importx.form.basic'] = 'Základní import';
$_lang['importx.startbutton'] = 'Začít importovat';
$_lang['importx.importsuccess'] = 'Dokumenty byly úspěšně naimportovány do MODx.';
$_lang['importx.importfailure'] = 'Oops, nastala chyba při importu dokumentů.';
$_lang['importx.tab.input'] = 'CSV vstup';
$_lang['importx.tab.input.desc'] = 'Do pole níže vložte zdrojová data, jednotlivá pole oddělená oddělovačem (výchozí oddělovač je středník) a jednotlivé záznamy každý na novém řádku.';
$_lang['importx.tab.input.sep'] = 'Pokud ve zdrojových datech používáte jiný oddělovač uveďte ho do tohoto políčka. Políčko ponechte nevyplněné pokud používáte středník.';
$_lang['importx.csv'] = 'Zdrojová CSV data';
$_lang['importx.parent'] = 'Rodič:'; // Not parent resource, as this can also be a context!
$_lang['importx.csvfile'] = 'Nahrátí CSV souboru';
$_lang['importx.separator'] = 'Oddělovač';
$_lang['importx.tab.settings'] = 'Výchozí nastavení';
$_lang['importx.tab.settings.desc'] = 'Zvolte výchozí nastavení, které chcete použít. Tyto volby můžete pro každý záznam přetížit odkazováním se na název pole v CSV datech.';
$_lang['importx.err.noparent'] = 'Zadejte ID rodiče, pod kterého mají být dokumenty naimportovány. Pokud chcete dokumenty umístit do kořenu portálu zadejte 0.';
$_lang['importx.err.parentnotnumeric'] = 'Rodič není číslo nebo platný klíč kontextu.';
$_lang['importx.err.parentlessthanzero'] = 'Rodič musí být kladné celé číslo.';
$_lang['importx.err.nocsv'] = 'Pokud chcete provést import pak zadejte zdrojová CSV data.';
$_lang['importx.err.fileuploadfailed'] = 'Chyba při čtení nahrátého souboru.';
$_lang['importx.err.invalidcsv'] = 'Chybná CSV data.';
$_lang['importx.err.notenoughdata'] = 'Nedostatek zdrojových dat. Byl očekáván alespoň jeden řádek pro hlavičku hodnot a alespoň jeden řádek hodnot.';
$_lang['importx.err.elementmismatch'] = 'Počet hodnot neodpovídá. Zkontrolujte správnou syntaxi na řádku [[+line]].';
$_lang['importx.err.savefailed'] = 'Nastala chyba při ukládání dokumentu.';
$_lang['importx.err.invalidheader'] = 'Hlavička hodnot obsahuje jedno nebo více polí, která jsou neplatná. Neplatné(á) je(jsou): [[+fields]].';
$_lang['importx.err.intexpected'] = '[[+field]] ([[+int]] je očekáváno jako celé číslo)';
$_lang['importx.err.tvdoesnotexist'] = '[[+field]] (TV s ID [[+id]] nenalezena)';
$_lang['importx.log.runningpreimport'] = 'Spouštím před-importní testy na zdrojových datech...';
$_lang['importx.log.fileuploadfound'] = 'Nahrátí CSV souboru přetíží manuální vstup. Soubor: [[+filename]]';
$_lang['importx.log.preimportclean'] = 'V před-importních testech nebyla nalezena žádná chyba. Připravuji hodnoty na import...';
$_lang['importx.log.importvaluesclean'] = 'Nenalezena žádná chyba při kontrole importovaných hodnot. Nalezeno [[+count]] záznamů. Začínám importovat...';
$_lang['importx.log.complete'] = 'Import dokončen. Bylo naimportováno [[+count]] dokumentů.';
?>
