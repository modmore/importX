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
Ext.onReady(function() {
    MODx.load({ xtype: 'modimport-page-home'});
});
 
modImport.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        renderTo: 'modimport-panel-home-div'
        ,components: [{
            xtype: 'modimport-panel-tabs'
        },{
            xtype: 'modimport-page-import'
        }]
    });
    modImport.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(modImport.page.Home,MODx.Component);
Ext.reg('modimport-page-home',modImport.page.Home);