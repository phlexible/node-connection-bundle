Ext.require('Phlexible.nodeconnection.view.NodeConnectionsAccordion');
Ext.require('Phlexible.elements.ElementAccordion');

Phlexible.elements.ElementAccordion.prototype.populateItems = Phlexible.elements.ElementAccordion.prototype.populateItems.createSequence(function() {
    if (Phlexible.User.isGranted('ROLE_NODE_CONNECTIONS')) {
        this.items.push({
            xtype: 'nodeconnection-nodeconnectionsaccordion',
            collapsed: true
        });
    }
});
