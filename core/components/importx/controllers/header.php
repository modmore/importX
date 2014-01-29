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
$modx->regClientStartupScript($importx->config['jsUrl'].'mgr/importx.js');
//$modx->regClientStartupScript($importx->config['jsUrl'].'mgr/fileupload.xtype.js');
$defaults = array();
$defaults['richtext'] = (boolean)$modx->getOption('richtext_default',null,true);
$defaults['template'] = (int)$modx->getOption('default_template',null,1);
$defaults['hidemenu'] = (boolean)$modx->getOption('hidemenu_default',null,false);
$defaults['published'] = (boolean)$modx->getOption('publish_default',null,true);
$defaults['searchable'] = (boolean)$modx->getOption('search_default',null,true);
$defaults = $modx->toJSON($defaults);
$modx->regClientStartupHTMLBlock('<script type="text/javascript">
Ext.onReady(function() {
    importX.config = '.$modx->toJSON($importx->config).';
    importX.defaults = '.$defaults.';
});
</script>');
return '';
