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
var topic = '/importx/';
var register = 'mgr';
importX.page.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'importx-form-create-import'
        ,buttons: [{
            process: 'import',
            text: _('importx.startbutton'), 
            handler: function() {
                if (this.console == null || this.console == undefined) {
                    this.console = MODx.load({
                       xtype: 'modx-console'
                       ,register: register
                       ,topic: topic
                       ,show_filename: 0
                       ,listeners: {
                         'shutdown': {fn:function() {
                             Ext.getCmp('modx-layout').refreshTrees();
                         },scope:this}
                       }
                    });
                } else {
                    this.console.setRegister(register, topic);
                }
                this.console.show(Ext.getBody());
                Ext.getCmp('importx-panel-import').form.submit({
                    success:{fn:function() {
                        this.console.fireEvent('complete');
                    },scope:this},
                    failure: function(f, a) {
                        //alert(_('importx.importfailure')+' '+a.result.message);
                        //console.fireEvent('complete');
                    }
                });
            }
        },'-',{
            text: _('help_ex'),
            handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'importx-form-create-import'
        }]
    });
    importX.page.createImport.superclass.constructor.call(this,config);
};
Ext.extend(importX.page.createImport,MODx.Component);
Ext.reg('importx-page-import',importX.page.createImport);



importX.panel.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: importX.config.connectorUrl
        ,baseParams: {
            action: 'startimport',
            register: register,
            topic: topic
        }
        ,layout: 'fit'
        ,id: 'importx-panel-import'
        ,buttonAlign: 'center'
        ,fileUpload: true
        ,width: '98%'
        ,items: [{
            border: true
            ,labelWidth: 150
            ,autoHeight: true
            ,buttonAlign: 'center'
            ,items: [{
                html: '<p>'+_('importx.desc')+'</p>',
                border: false,
                bodyCssClass: 'panel-desc'
            },{
                xtype: 'modx-tabs',
                cls: 'main-wrapper',
                deferredRender: false,
                forceLayout: true,
                defaults: {
                    layout: 'form',
                    autoHeight: true,
                    hideMode: 'offsets',
                    padding: 15
                },
                items: [{
                    xtype: 'modx-panel',
                    title: _('importx.tab.input'),
                    items: [{
                        html: '<p>'+_('importx.tab.input.desc')+'</p>',
                        border: false
                    },{
                        xtype: 'textarea',
                        fieldLabel: _('importx.csv'), 
                        name: 'csv',
                        id: 'importx-import-csv',
                        labelSeparator: '',
                        anchor: '100%',
                        height: 150,
                        allowBlank: false,
                        blankText: _('importx.nocsv')
                    },{
                        xtype: 'textfield',
                        fieldLabel: _('importx.csvfile'),
                        name: 'csv-file',
                        id: 'csv-file',
                        inputType: 'file'
                    },{
                        html: '<p>'+_('importx.tab.input.sep')+'</p>',
                        border: false
                    },{
                        xtype: 'textfield',
                        fieldLabel: _('importx.separator'),
                        name:  'separator',
                        id: 'importx-import-sep',
                        anchor: '100%',
                        allowBlank: true
                    }]
                },{
                    title: _('importx.tab.settings'),
                    id: 'importx-defaults',                    
                    items: [{
                        html: '<p>'+_('importx.tab.settings.desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('importx.parent')
                        ,name: 'parent'
                        ,id: 'importx-import-parent'
                        ,labelSeparator: ''
                        ,anchor: '100%'
                        ,value: 0
                        ,allowBlank: false
                        ,blankText: _('importx.noparent')
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_published'),
                        name: 'published',
                        id: 'importx-import-published',
                        anchor: '100%',
                        checked: importX.defaults['published']
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_searchable'),
                        name: 'searchable',
                        id: 'importx-import-searchable',
                        anchor: '100%',
                        checked: importX.defaults['searchable']
                    },{
                        xtype: 'checkbox',
                        fieldLabel: _('resource_hide_from_menus'),
                        name: 'hidemenu',
                        id: 'importx-import-hidemenu',
                        anchor: '100%',
                        checked: importX.defaults['hidemenu']
                    }]
                }]
            }]
        }]
    });
    Ext.Ajax.timeout = 0;
    importX.panel.createImport.superclass.constructor.call(this,config);
};
Ext.extend(importX.panel.createImport,MODx.FormPanel);
Ext.reg('importx-form-create-import',importX.panel.createImport);
