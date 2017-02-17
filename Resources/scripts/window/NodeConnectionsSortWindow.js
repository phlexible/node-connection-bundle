Phlexible.nodeconnection.NodeConnectionsSortWindow = Ext.extend(Ext.Window, {
    title: Phlexible.strings.NodeConnections.sort_connections,
    strings: Phlexible.strings.NodeConnections,
    width: 800,
    height: 500,
    constrainHeader: true,
    modal: true,
    layout: 'fit',

    initComponent: function() {
        this.items = [{
            xtype: 'grid',
            cls: 'p-nodeconnection-sort',
            header: false,
            border: false,
            autoExpandColumn: 1,
            store: new Ext.data.SimpleStore({
                fields: ['id', 'typeText', 'iconCls', 'targetText', 'sort'],
                data: this.data,
                id: 'id',
                sortInfo: {field: 'sort', direction: 'ASC'}
            }),
            columns: [{
                header: this.strings.type,
                dataIndex: 'typeText',
                width: 200,
                renderer: function(v, md, r) {
                    return (r.data.iconCls ? MWF.inlineIcon(r.data.iconCls) + ' ' : '') + v;
                }
            },{
                header: this.strings.connection,
                dataIndex: 'targetText',
                xrenderer: function(v, md, r) {
                    return v;
                    var key = r.data.type + '_' + r.data.origin;
                    var type = this.types[key];
                    var tpl = type.textTpl;
                    if (!type) {
                        return v;
                    }

                    return String.format(tpl, v);
                }.createDelegate(this)
            }],
            enableDragDrop: true,
            ddGroup: 'elementConnectionsSortDD',
            listeners: {
                render: {
                    fn: function(grid) {
                        this.ddrow = new Ext.ux.dd.GridReorderDropTarget(grid, {
                            copy: false
                        });
                    }
                }
            }
        }];

        this.buttons = [{
            text: this.strings.cancel,
            handler: this.close,
            scope: this
        },{
            text: this.strings.update,
            handler: function() {
                var records = this.getComponent(0).getStore().getRange();

                var data = {};
                var i = 0;
                Ext.each(records, function(r) {
                    data[r.data.id] = i++;
                }, this);

                this.fireEvent('updateData', data);
                this.close();
            },
            scope: this
        }];

        Phlexible.nodeconnection.NodeConnectionsSortWindow.superclass.initComponent.call(this);
    }
});
