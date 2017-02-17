<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle;

/**
 * Node connection events
 *
 * @author Matthias Harmuth <mharmuth@brainbits.net>
 */
interface NodeConnectionEvents
{
    /**
     * Before Update Connection Event
     *
     * Fired before a connection gets saved.
     */
    const BEFORE_UPDATE_NODE_CONNECTION = 'phlexible_node_connection.before_update_node_connection';

    /**
     * Save Connection Event
     *
     * Fired after connection was saved.
     */
    const UPDATE_NODE_CONNECTION = 'phlexible_node_connection.update_node_connection';

    /**
     * Before Delete Connection Event
     *
     * Fired before a connection gets deleted.
     */
    const BEFORE_DELETE_NODE_CONNECTION = 'phlexible_node_connection.before_delete_node_connection';

    /**
     * Delete Connection Event
     *
     * Fired after connection was deleted.
     */
    const DELETE_NODE_CONNECTION = 'phlexible_node_connection.delete_node_connection';
}
