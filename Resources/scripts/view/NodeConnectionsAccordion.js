Ext.provide('Phlexible.nodeconnection.view.NodeConnectionsAccordion');

Ext.require('Phlexible.nodeconnection.model.Connection');
Ext.require('Phlexible.nodeconnection.window.NodeConnectionsSortWindow');
Ext.require('Phlexible.nodeconnection.window.NodeConnectionsWindow');

Phlexible.nodeconnection.view.NodeConnectionsAccordion = Ext.extend(Ext.grid.GridPanel, {
    title: Phlexible.nodeconnection.Strings.connections,
    strings: Phlexible.nodeconnection.Strings,
    cls: 'p-nodeconnection-accordion',
    iconCls: 'p-nodeconnection-component-icon',
    autoHeight: true,
    border: false,
    enableColumnHide: false,
    enableColumnMove: false,
    enableColumnResize: false,
    enableHdMenu: false,
    autoExpandColumn: 6,

    noGrouping: false,
    key: 'nodeconnections',

    initComponent: function() {
        this.store = new Ext.data.GroupingStore({
            proxy: new Ext.data.HttpProxy({
                url: Phlexible.Router.generate('node_connections_list', {"_locale": Phlexible.Config.get('language.backend') || 'en'}),
                baseParams: {
                    tid: 0,
                    language: ''
                }
            }),
            reader: new Ext.data.JsonReader({
                root: 'connections',
                id: 'id'
            }, Phlexible.nodeconnection.model.Connection),
            //url: MWF.baseUrl + '/elementconnections/list/index',
            //fields: Makeweb.elementconnections.Connection,
            //root: 'connections',
            //id: 'id',
            sortInfo: {field: 'sort', direction: 'ASC'},
            groupField: 'typeText',
            //baseParams: {
            //    tid: 0,
            //    language: ''
            //},
            listeners: {
                datachanged: function(store) {
                    this.types = store.reader.jsonData.types;
                },
                load: function(store, records) {
                    if (records.length) {
                        this.updateTitle(records.length);
                    }
                    else {
                        this.updateTitle();
                    }
                },
                scope: this
            }
        });

        this.view = new Ext.grid.GroupingView({
            //forceFit: true,
            showGroupName: false,
            enableNoGroups: true,
            enableGroupingMenu: false,
            groupTextTpl: '{text} ({[values.rs.length]})',
            emptyText: this.strings.no_connections_defined,
            deferEmptyText: false
        });

        this.columns = [{
            header: '_id',
            dataIndex: 'id',
            hidden: true
        },{
            header: '_type',
            dataIndex: 'type',
            hidden: true
        },{
            header: '_origin',
            dataIndex: 'origin',
            hidden: true
        },{
            header: '_source',
            dataIndex: 'source',
            hidden: true
        },{
            header: '_target',
            dataIndex: 'target',
            hidden: true
        },{
            header: '_type',
            dataIndex: 'typeText',
            width: 200,
            hidden: !this.noGrouping,
            renderer: function(v, md, r) {
                return (r.data.iconCls ? Phlexible.inlineIcon(r.data.iconCls) + ' ' : '') + v;
            }
        },{
            header: this.strings.connection,
            dataIndex: 'targetText',
            renderer: function(v, md, r) {
                return v;
                var key = r.data.type + '_' + r.data.origin;
                var type = this.types[key];
                var tpl = type.textTpl;
                if (!type) {
                    return v;
                }

                return String.format(tpl, v);
            }.createDelegate(this)
        }];

        this.selModel = new Ext.grid.RowSelectionModel({
            multiSelect: true,
            listeners: {
                selectionchange: function(sm) {
                    records = sm.getSelections();

                    if (records.length == 1) {
                        this.getTopToolbar().items.items[2].enable();
                        this.getTopToolbar().items.items[4].enable();
                    }
                    else if (records.length > 1) {
                        this.getTopToolbar().items.items[2].disable();
                        this.getTopToolbar().items.items[4].enable();
                    }
                    else {
                        this.getTopToolbar().items.items[2].disable();
                        this.getTopToolbar().items.items[4].disable();
                    }
                },
                scope: this
            }
        });

        // TODO: white-space: no-wrap aus

        this.tbar = [{
            //text: this.strings.add,
            tooltip: this.strings.add,
            iconCls: 'p-nodeconnection-add-icon',
            handler: function() {
                var r = new Phlexible.nodeconnection.model.Connection({
                    id: Ext.id(),
                    'new': 1,
                    origin: null,
                    type: null,
                    source: this.tid,
                    target: null,
                    text: null
                });

                var w = new Phlexible.nodeconnection.window.NodeConnectionsWindow({
                    siteroot_id: this.siteroot_id,
                    language: this.language,
                    types: this.types,
                    //record: r,
                    tid: this.tid,
                    listeners: {
                        connect: function(r) {
                            var max = 0;
                            this.store.each(function(r) {
                                if (r.data.sort > max) max = r.data.sort;
                            }, this);
                            r.set('sort', max + 1);
                            this.store.add(r);
                            this.store.sort('sort', 'ASC');
                        },
                        scope: this
                    }
                });
                w.show();
            },
            scope: this
        },'-',{
            //text: this.strings.edit,
            tooltip: this.strings.edit,
            iconCls: 'p-nodeconnection-edit-icon',
            disabled: true,
            handler: function() {
                var r = this.getSelectionModel().getSelected();

                var w = new Phlexible.nodeconnection.window.NodeConnectionsWindow({
                    siteroot_id: this.siteroot_id,
                    language: this.language,
                    types: this.types,
                    record: r
                });
                w.show();
            },
            scope: this
        },'-',{
            //text: this.strings['remove'],
            tooltip: this.strings['remove'],
            iconCls: 'p-nodeconnection-delete-icon',
            disabled: true,
            handler: function() {
                var records = this.getSelectionModel().getSelections();

                Ext.each(records, function(r) {
                    this.store.remove(r);
                    this.store.sort('sort', 'ASC');
                }, this);
            },
            scope: this
        },'-',{
            text: this.strings.sort,
            handler: function() {
                var records = this.store.getRange();
                var data = [];
                Ext.each(records, function(r) {
                    data.push([r.id, r.data.typeText, r.data.iconCls, r.data.targetText, r.data.sort]);
                }, this);

                var w = new Phlexible.nodeconnection.window.NodeConnectionSortWindow({
                    data: data,
                    listeners: {
                        updateData: function(data) {
                            this.store.each(function(r) {
                                if (data[r.id] >= 0) {
                                    r.set('sort', data[r.id]);
                                }
                            }, this);
                            this.store.sort('sort', 'ASC');
                        },
                        scope: this
                    }
                });
                w.show();
            },
            scope: this
        }];

        this.on({
            rowdblclick: function(grid, rowIndex) {
                var r = grid.getStore().getAt(rowIndex);

                this.element.load(r.data.target);
            },
            scope: this
        });

        Phlexible.nodeconnection.view.NodeConnectionsAccordion.superclass.initComponent.call(this);
    },

    load: function(data, element) {
        if (data.properties.et_type != 'full' && data.properties.et_type != 'structure') {
            this.updateTitle();
            this.hide();
            return;
        }

        this.element = element;
        this.loadData(data.properties.siteroot_id, data.properties.tid, data.properties.language);
    },

    loadData: function(siteroot_id, tid, language) {
        this.siteroot_id = siteroot_id;
        this.tid = tid;
        this.language = language;

        this.store.baseParams.tid = tid;
        this.store.baseParams.language = language;
        this.store.load();

        this.show();
    },

    getData: function() {
        var data = [];

        var records = this.store.getRange();

        for(var i=0; i<records.length; i++) {
            data.push({
                id: records[i].id,
                'new': records[i]['new'],
                type: records[i].data.type,
                origin: records[i].data.origin,
                source: records[i].data.source,
                target: records[i].data.target,
                sort: records[i].data.sort
            });
        }

        return data;
    },

    updateTitle: function(count) {
        if (!count) {
            this.setTitle(this.strings.connections);
        }
        else {
            this.setTitle(this.strings.connections + ' [' + count + ']');
        }
    }
});

Ext.reg('nodeconnection-nodeconnectionsaccordion', Phlexible.nodeconnection.view.NodeConnectionsAccordion);
