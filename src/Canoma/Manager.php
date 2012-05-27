<?php

namespace Canoma;

/**
 * @author Mark van der Velden <mvdvelden@ibuildings.nl>
 */
class Manager
{
    /**
     * @var HashAdapterInterface
     */
    private $adapter;
    /**
     * @var int
     */
    private $replicaCount;


    /**
     * Construct the manager, requiring an adapter and a replica count of 0 or more.
     *
     * @param HashAdapterInterface $adapter
     * @param int $replicaCount
     */
    public function __construct(HashAdapterInterface $adapter, $replicaCount)
    {
        $this->adapter = $adapter;
        $this->replicaCount = (int) $replicaCount;
    }
}
