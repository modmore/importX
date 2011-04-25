<?php
/*
 * importX
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

$_lang['importx'] = 'importX';
$_lang['importx.description'] = 'Import your CSV formatted data into new Resources';
$_lang['importx.desc'] = 'This component can be used to import CSV formatted files into new MODX Resources. Simply choose a parent to use and enter your CSV formatted information. The default separator is a semi-colon.';
$_lang['importx.form.basic'] = 'Basic import';
$_lang['importx.startbutton'] = 'Start import';
$_lang['importx.importsuccess'] = 'Succesfully imported resources into MODX.';
$_lang['importx.importfailure'] = 'Oops, an error occurred while importing your resources.';
$_lang['importx.tab.input'] = 'CSV Input';
$_lang['importx.tab.input.desc'] = 'Paste your raw text, separating records with a newline and fields with a semi-colon (;) or the separator of your choice, in the field below.';
$_lang['importx.tab.input.sep'] = 'When your CSV formatted entry uses a different separator, you can declare it here. Leave empty to use a semi-colon.';
$_lang['importx.csv'] = 'CSV brut';
$_lang['importx.separator'] = 'Séparateur';
$_lang['importx.tab.settings'] = 'Paramètres par défaut';
$_lang['importx.tab.settings.desc'] = 'Veuillez indiquer les paramètres à utiliser par défaut. Vous pourrez outrepasser ces paramètres « by referencing the fieldname in your CSV formatted values ».';
$_lang['importx.err.noparent'] = 'Veuillez indiquer le parent cible de l\'import. Indiquez 0 pour importer vos ressources à la racine de votre site.';
$_lang['importx.err.parentnotnumeric'] = 'Parent non numérique.';
$_lang['importx.err.parentlessthanzero'] = 'Le parent doit être un entier positif.';
$_lang['importx.err.nocsv'] = 'Veuillez ajouter les valeurs de votre CSV dans l\'ordre dans lesquelles elles doivent être éxécutées.';
$_lang['importx.err.invalidcsv'] = 'Valeur de CSV invalide.';
$_lang['importx.err.notenoughdata'] = 'Pas assez d\'informations fournies. Veuillez indiquer au minimum une ligne d\'entête ainsi qu\'une ligne de données.';
$_lang['importx.err.elementmismatch'] = 'Le nombre d\élément ne correspond pas. Veuillez vérifier la syntaxe de la ligne [[+line]].';
$_lang['importx.err.savefailed'] = 'Une erreur est survenue lors de la sauvegarde de la ressource.';
$_lang['importx.err.invalidheader'] = 'Votre entête contient au moins un nom éronné. Le(s) nom(s) est(sont): [[+fields]].';
$_lang['importx.err.intexpected'] = '[[+field]] ([[+int]] doit être un entier)';
$_lang['importx.err.tvdoesnotexist'] = '[[+field]] (aucune variable de modèle ayant pour ID [[+id]])';
$_lang['importx.log.runningpreimport'] = 'Tests de pré-importation des données…';
$_lang['importx.log.preimportclean'] = 'Aucune erreur trouvée lors de la pré-importation. Préparation de l\'import des valeurs…';
$_lang['importx.log.importvaluesclean'] = 'Aucune erreur trouvée lors de la vérification des données. Import en cours…';
$_lang['importx.log.complete'] = 'Import terminé. [[+count]] ressources ont été importées.';
?>