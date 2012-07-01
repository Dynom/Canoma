<?php
/**
 * The standard deviation test script.
 *
 * Call like:
 *
 *     php calculateReplicates.php [number of replicates]
 *
 *
 * This script is used to test about what settings you want to use in your project. There are a couple of parameters you
 * might want to fiddle with:
 *
 * - The hashing adapter
 * - The amount of replicates (per node)
 * - The amount of nodes
 * - The size of your cache and node keys (string-length of the argument for 'addNode' and 'getNodeForString')
 * - The amount of cache-keys
 *
 * Want to learn more? Be sure to read the documentation on: https://github.com/Dynom/Canoma
 *
 * Have fun!
 */
require __DIR__ .'/bootstrap.php';

// The number and the algorithm you want to play with
$replicates = (int) (isset($argv[1]) ? $argv[1] : 45);
$adapter = new \Canoma\HashAdapter\Md5();
$nodes = 2;

// The amount of cache-keys we'll be storing to simulate. A higher value means more accuracy and more real-world,
// but it also takes longer to complete.
$cacheKeys = 10000;

// ---------------------------------------------------------------------------------------------------------------------

// Create our manager
$manager = new \Canoma\Manager(
    $adapter,
    $replicates
);

// Adding our nodes
for ($i = 0; $i < $nodes; $i++) {
    $manager->addNode('Node '. $i);
}

// Doing the lookup
$start = microtime(true);
$result = array();
for ($i = 0; $i < $cacheKeys; $i++) {
    $result[] = $manager->getNodeForString("user:". $i);
}
$stop = microtime(true);


printf("\n\n===> Standard deviation: %0.2f %%\n\n", calculateStandardDeviation($result));
echo "Parameters:
    Replicates: $replicates
    Adapter: ". get_class($adapter) ."
    Nodes: $nodes (The amount of cache-servers that will be storing your data)
    Cache keys: $cacheKeys
";

print_r(array_count_values($result));
printf("Spent %0.3f seconds doings lookups.", $stop - $start);



/**
 * @param array $result
 * @return float
 */
function calculateStandardDeviation(array $result)
{
    $resultSummary = array_count_values($result);
    $mean = array_sum($resultSummary) / count($resultSummary);

    $deviationResult = array();
    foreach ($resultSummary as $nodeCount) {
        $deviationResult[] = pow($nodeCount - $mean, 2);
    }

    return sqrt(array_sum($deviationResult) / count($deviationResult));
}
