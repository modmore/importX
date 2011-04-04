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
modImport.page.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modimport-form-create-import'
        ,buttons: [{
            process: 'import',
            text: _('modimport.startbutton'), 
            handler: function() {
                Ext.getCmp('modimport-panel-import').form.submit({
                    success: function(f, a) {
                        alert(_('modimport.importsuccess')+' '+a.result.message);
                    },
                    failure: function(f, a) {
                        alert(_('modimport.importfailure')+' '+a.result.message);
                    }
                });
            }
        }]
        ,components: [{
            xtype: 'modimport-form-create-import'
        }]
    });
    modImport.page.createImport.superclass.constructor.call(this,config);
};
Ext.extend(modImport.page.createImport,MODx.Component);
Ext.reg('modimport-page-import',modImport.page.createImport);



modImport.panel.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: modImport.config.connectorUrl
        ,baseParams: {
            action: 'startimport'
        }
        ,id: 'modimport-panel-import'
        ,buttonAlign: 'center'
        ,items: [{
            layout: 'form'
            ,bodyStyle: 'padding: 15px;'
            ,border: true
            ,labelWidth: 150
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('modimport.desc')+'</p>'
                ,border: false
            },{
                xtype: 'textfield'
                ,fieldLabel: _('modimport.parent') //'Import parent'
                ,name: 'parent'
                ,id: 'modimport-import-parent'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,value: 0
            },{
                xtype: 'textarea'
                ,fieldLabel: _('modimport.csv') //'CSV Values'
                ,name: 'csv'
                ,id: 'modimport-import-csv'
                ,labelSeparator: ''
                ,anchor: '100%'
            }]
        }]
    });
    Ext.Ajax.timeout = 0;
    modImport.panel.createImport.superclass.constructor.call(this,config);
};
Ext.extend(modImport.panel.createImport,MODx.FormPanel);
Ext.reg('modimport-form-create-import',modImport.panel.createImport);