<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Collection\TraversableCollection;
use Fusion\Router\Interfaces\RouteStoreInterface;

class RouteStore extends TraversableCollection implements RouteStoreInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addRestriction('\Fusion\Router\Interfaces\RouteInterface');
    }
}