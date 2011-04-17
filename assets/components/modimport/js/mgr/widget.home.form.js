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
var topic = '/modimport/';
var register = 'mgr';
modImport.page.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modimport-form-create-import'
        ,buttons: [{
            process: 'import',
            text: _('modimport.startbutton'), 
            handler: function() {
                var console = MODx.load({
                   xtype: 'modx-console'
                   ,register: register
                   ,topic: topic
                   ,show_filename: 0
                   ,listeners: {
                     'shutdown': {fn:function() {
                         /* do code here when you close the console */
                     },scope:this}
                   }
                });
                console.show(Ext.getBody());
                Ext.getCmp('modimport-panel-import').form.submit({
                    success:{fn:function() {
                        console.fireEvent('complete');
                    },scope:this},
                    failure: function(f, a) {
                        //alert(_('modimport.importfailure')+' '+a.result.message);
                        //console.fireEvent('complete');
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
        ,fileUpload: true
        ,baseParams: {
            action: 'startimport',
            register: register,
            topic: topic
        }
        ,layout: 'fit'
        ,id: 'modimport-panel-import'
        ,buttonAlign: 'center'
        ,items: [{
            //layout: 'form'
            bodyStyle: 'padding: 15px;'
            ,border: true
            ,labelWidth: 150
            ,width: '100%'
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('modimport.desc')+'</p>',
                border: false
            },{
                xtype: 'modx-tabs',
                deferredRender:false,
                forceLayout: true,
                defaults: {
                    layout:'form'
                    ,labelWidth:150
                    ,bodyStyle:'padding:15px'
                    ,autoHeight: true
                    ,hideMode:'offsets'
                },
                items: [{
                    xtype: 'modx-panel',
                    title: _('modimport.tab.input'),
                    items: [{
                        html: '<p>'+_('modimport.tab.input.desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('modimport.csv') 
                        ,name: 'csv'
                        ,id: 'modimport-import-csv'
                        ,labelSeparator: ''
                        ,anchor: '100%'
                        ,height: 150
                        ,allowBlank: false
                        ,blankText: _('modimport.nocsv')
                    }]
                },{
                    title: _('modimport.tab.settings'),
                    items: [{
                        html: '<p>'+_('modimport.tab.settings.desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_parent') 
                        ,name: 'parent'
                        ,id: 'modimport-import-parent'
                        ,labelSeparator: ''
                        ,anchor: '100%'
                        ,value: 0
                        ,allowBlank: false
                        ,blankText: _('modimport.noparent')
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_published'),
                        name: 'published',
                        id: 'modimport-import-published',
                        anchor: '100%'
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_searchable'),
                        name: 'searchable',
                        id: 'modimport-import-searchable',
                        anchor: '100%'
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_hide_from_menus'),
                        name: 'hidemenu',
                        id: 'modimport-import-hidemenu',
                        anchor: '100%'
                    }]
                }]
            }
            /*,{
                xtype: 'fileuploadfield',
                buttonOnly: true
                id: 'form-file',
                emptyText: 'Select an image',
                fieldLabel: 'Or, choose a file.',
                name: 'csvfile',
                buttonText: 'Browse',
                buttonCfg: {
                    iconCls: 'upload-icon'
                },
                buttonOnly: true
            }*/]
        }]
    });
    Ext.Ajax.timeout = 0;
    modImport.panel.createImport.superclass.constructor.call(this,config);
};
Ext.extend(modImport.panel.createImport,MODx.FormPanel);
Ext.reg('modimport-form-create-import',modImport.panel.createImport);