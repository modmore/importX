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
require_once dirname(dirname(__FILE__)).'/model/importx/importx.class.php';
$importx = new importX($modx);
$importx->initialize('mgr');
include 'header.php';
//$modx->regClientStartupScript($importX->config['jsUrl'].'mgr/widgets/doodles.grid.js');
$modx->regClientStartupScript($importx->config['jsUrl'].'mgr/widget.home.panel.js');
$modx->regClientStartupScript($importx->config['jsUrl'].'mgr/widget.home.form.js');
$modx->regClientStartupScript($importx->config['jsUrl'].'mgr/section.index.js');
 
return '<div id="importx-panel-home-div"></div>';
