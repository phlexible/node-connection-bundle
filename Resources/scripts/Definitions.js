Ext.ns('Phlexible.nodeconnection');

Phlexible.nodeconnection.Connection = Ext.data.Record.create([
    'id',
    'new',
    'type',
    'iconCls',
    'origin',
    'source',
    'target',
    'typeText',
    'targetText',
    'sort'
]);

Phlexible.nodeconnection.ElementAccordion.prototype.populateItems = Makeweb.elements.ElementAccordion.prototype.populateItems.createSequence(function() {
    if (Makeweb.config.user.Resources.indexOf('node_accordion_connections') !== -1) {
        this.items.push({
            xtype: 'nodeconnection-elementconnectionsaccordion',
            collapsed: true
        });
    }
});
