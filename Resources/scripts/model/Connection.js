Ext.provide('Phlexible.nodeconnection.model.Connection');

Phlexible.nodeconnection.model.Connection = Ext.data.Record.create([
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
