<?php

$setup = array(
    'database' => array(
        array(
            'action' => 'createTableIfNotExists',
            'data'   => array(
                DB_PREFIX . 'element_tree_connections' => array(
                    'options' => array(
                        'charset' => 'utf8',
                        'collate' => 'utf8_unicode_ci',
                        'type'    => 'innodb',
                        'primary' => array('id')
                    ),

                    'definition' => array(
                        'id' => array(
                            'type'    => 'char',
                            'length'  => 36,
                            'fixed'   => true,
                            'notnull' => true,
                        ),
                        'type' => array (
                            'type'    => 'string',
                            'length'  => 50,
                            'notnull' => true,
                        ),
                        'source' => array(
                            'type'    => 'integer',
                            'notnull'  => true,
                            'unsigned' => true,
                        ),
                        'target' => array(
                            'type'     => 'integer',
                            'notnull'  => true,
                            'unsigned' => true,
                        ),
                        'sort_source' => array(
                            'type'    => 'integer',
                            'notnull'  => true,
                            'unsigned' => true,
                            'default'  => '0'
                        ),
                        'sort_target' => array(
                            'type'    => 'integer',
                            'notnull'  => true,
                            'unsigned' => true,
                            'default'  => '0'
                        ),
                    ),
                ),
            ),
        ),
        // createForeignKey
        array(
            'action' => 'createForeignKey',
            'data'   => array(
                DB_PREFIX . 'element_tree_connections' => array(
                    array(
                        'name'         => 'elementconnections_to_element_tree',
                        'local'        => array('source'),
                        'foreign'      => array('id'),
                        'foreignTable' => DB_PREFIX . 'element_tree',
                        'onDelete'     => 'CASCADE',
                        'onUpdate'     => 'CASCADE',
                    ),
                ),
                DB_PREFIX . 'element_tree_connections' => array(
                    array(
                        'name'         => 'elementconnections_to_element_tree',
                        'local'        => array('target',),
                        'foreign'      => array('id',),
                        'foreignTable' => DB_PREFIX . 'element_tree',
                        'onDelete'     => 'CASCADE',
                        'onUpdate'     => 'CASCADE',
                    ),
                ),
            ),
        ),
    ),
);
