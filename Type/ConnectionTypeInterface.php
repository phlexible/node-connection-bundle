<?php

/*
 * This file is part of the phlexible node connection package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\NodeConnectionBundle\ConnectionType;

/**
 * Connection type interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface ConnectionTypeInterface
{
    public function getKey();

    public function getType();

    /**
     * Get allowed element types (unique ids).
     *
     * @param string $origin
     *
     * @return array
     */
    public function getAllowedElementTypeUniqueIds($origin);

    /**
     * Get allowed element types (ids).
     *
     * @param string $origin
     *
     * @return array
     */
    public function getAllowedElementTypeIds($origin);

    public function getIconClass($origin);

    public function getTitle($origin, $language);

    public function getText($origin, $text, $language);

    public function getTextTemplate($origin, $language);

}
