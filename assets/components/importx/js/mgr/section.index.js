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
Ext.onReady(function() {
    Ext.QuickTips.init();
    MODx.load({ xtype: 'importx-page-home'});
});
 
importX.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        renderTo: 'importx-panel-home-div'
        ,cls: 'container'
        ,components: [{
            xtype: 'panel',
            html: '<h2>'+_('importx')+'</h2>',
            border: false,
            cls: 'modx-page-header'
        },{
            xtype: 'importx-page-import'
        }]
    });
    importX.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(importX.page.Home,MODx.Component);
Ext.reg('importx-page-home',importX.page.Home);
