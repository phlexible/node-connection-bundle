Phlexible.nodeconnections.NodeConnectionWindow = Ext.extend(Ext.Window, {
    title: Makeweb.strings.ElementConnections.connection,
    strings: Makeweb.strings.ElementConnections,
    layout: 'fit',
    width: 400,
    height: 200,
    modal: true,

    getNewRecord: function() {
        return new Phlexible.nodeconnections.Connection({
            id: Ext.id(),
            'new': 1,
            origin: null,
            type: null,
            source: this.tid,
            target: null,
            text: null
        });
    },

    initComponent: function() {
        if (!this.record) {
            this.record = this.getNewRecord();
            this.isNew = true;
        }

        var data = [];
        for (var i in this.types) {
            var type = this.types[i];

            if (type.type === 'undirected' && type.origin === 'target') continue;

            data.push([type.key + '_' + type.origin, type.key, type.origin, type.title, type.iconCls, type.allowedElementTypeIds]);
        }

        this.items = [{
            xtype: 'form',
            border: false,
            monitorValid: true,
            bodyStyle: 'padding: 5px',
            items: [{
                xtype: 'iconcombo',
                name: 'type',
                fieldLabel: this.strings.type,
                value: this.record.data['new'] ? null : this.record.data.type + '_' + this.record.data.origin,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'key', 'origin', 'name', 'iconCls', 'allowedElementTypeIds'],
                    data: data,
                    id: 0
                }),
                emptyText: this.strings.select_type,
                displayField: 'name',
                valueField: 'id',
                iconClsField: 'iconCls',
                mode: 'local',
                editable: false,
                typeAhead: false,
                triggerAction: 'all',
                selectOnFocus: true,
                anchor: '-20',
                allowBlank: false,
                listeners: {
                    select: {
                        fn: function(combo, r) {
                            this.getComponent(0).getComponent(1).setElementTypeIds(r.data.allowedElementTypeIds);
                        },
                        scope: this
                    }
                }
            },{
                xtype: 'linkfield',
                hiddenName: 'target',
                fieldLabel: this.strings.target,
                anchor: '-20',
                listWidth: 400,
                allowBlank: false,
                siteroot_id: this.siteroot_id,
                language: this.language,
                allowed: {
                    tid: true,
                    intrasiteroot: true,
                    url: false,
                    mailto: false
                }
            }],
            bindHandler : function(){
                var valid = true;
                this.form.items.each(function(f){
                    if(!f.isValid(true)){
                        valid = false;
                        return false;
                    }
                });
                if(this.ownerCt.buttons){
                    for(var i = 0, len = this.ownerCt.buttons.length; i < len; i++){
                        var btn = this.ownerCt.buttons[i];
                        if(btn.formBind === true && btn.disabled === valid){
                            btn.setDisabled(!valid);
                        }
                    }
                }
                this.fireEvent('clientvalidation', this, valid);
            }
        }];

        if (this.record.data['new'] != 1) {
            this.items[0].items[0].value = this.record.data.type + '_' + this.record.data.origin;
            this.items[0].items[1].value = this.record.data.targetText;
            this.items[0].items[1].hiddenValue = 'id:' + this.record.data.target;
        }

        this.buttons = [{
            text: this.isNew ? this.strings.connect_and_close : this.strings.update,
            iconCls: '',
            formBind: true,
            handler: function() {
                var typeField = this.getComponent(0).getComponent(0);
                var typeRecord = typeField.getStore().getById(typeField.getValue());

                var targetField = this.getComponent(0).getComponent(1);
                var targetValue = targetField.getValue();
                var targetValue = targetValue.match(/^.*\:(\d+)/)[1];

                var type = this.types[typeRecord.data.key + '_' + typeRecord.data.origin];

                this.record.set('new', 2);
                this.record.set('origin', typeRecord.data.origin);
                this.record.set('type', typeRecord.data.key);
                this.record.set('iconCls', type.iconCls);
                this.record.set('typeText', type.title);
                this.record.set('target', targetValue);
                this.record.set('targetText', targetField.getRawValue());
                this.record.set('sort', 0);

                this.fireEvent('connect', this.record);

                this.close();
            },
            scope: this
        }];

        if (this.isNew) {
            this.buttons.push({
                text: this.strings.connect,
                iconCls: '',
                formBind: true,
                handler: function() {
                    var typeField = this.getComponent(0).getComponent(0);
                    var typeRecord = typeField.getStore().getById(typeField.getValue());

                    var targetField = this.getComponent(0).getComponent(1);
                    var targetValue = targetField.getValue();
                    var targetValue = targetValue.match(/^.*\:(\d+)$/)[1];

                    var type = this.types[typeRecord.data.key + '_' + typeRecord.data.origin];

                    this.record.set('new', 2);
                    this.record.set('origin', typeRecord.data.origin);
                    this.record.set('type', typeRecord.data.key);
                    this.record.set('iconCls', type.iconCls);
                    this.record.set('typeText', type.title);
                    this.record.set('target', targetValue);
                    this.record.set('targetText', targetField.getRawValue());

                    this.fireEvent('connect', this.record);

                    this.record = this.getNewRecord();
                    var dummy = typeField.getValue();
                    this.getComponent(0).getForm().reset();
                    typeField.setValue(dummy);
                },
                scope: this
            });
        }

        this.buttons.push({
            text: this.strings.cancel,
            handler: function() {
                this.close();
            },
            scope: this
        });

        Phlexible.nodeconnection.NodeConnectionWindow.superclass.initComponent.call(this);
    }
});
