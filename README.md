Canoma - Caching Node Manager
=============================

[![Build Status](https://secure.travis-ci.org/Dynom/Canoma.png?branch=development)](http://travis-ci.org/Dynom/Canoma)


What is Canoma?
---------------
Canoma (CAche NOde MAnager) is a consistent hashing implementation. The ideas behind it:

* Provide a intuitive API
* Provide a flexible library
* Unit-tested (This project is being developed using TDD)


What problems does Canoma solve?
--------------------------------
It can act as a key component in a reliable distributed caching environment.

All your (application) caches are written to a cache-backend (For example: Redis, Membase, Memcache, etc.),
by using Canoma all your keys will be written using a circular designation system. Meaning that keys are spread fairly
evenly, with one huge advantage. If one server falls out, the keys will automatically be assigned to another server.
This doesn't mean the "next" server, but the server that is next according to the consistent hashing algorithm.

This also works for upscaling. If another server is added, keys are simply distributed evenly again.


Usage
=====


Quick-start
------------
Install by either downloading or cloning this repository, for stable releases clone or download from the master branch.


Example usage
-------------
This section provides some code examples about how to use Canoma. Currently Canoma has very few features, I'm aiming at
stability and correctness first. After that, I'll start adding features.

Example usage
```php
<?php

// Create the manager
$manager = new \Canoma\Manager(
    new \Canoma\HashAdapter\Md5,
    30
);

// Register nodes, using unique strings.
$manager->addNode('cache-1.example.com:11211');
$manager->addNode('cache-2.example.com:11211');
$manager->addNode('cache-3.example.com:11211');

// Do a lookup for your cache-identifier and see what node we can use.
$node = $manager->getNodeForString('user:42:session');


// Connect to you cache backend and save the cache, using your favorite backends and libraries
// A Redis example (Using the excellent Predis library: https://github.com/nrk/predis)
// Storing
$client = new \Predis\Client("tcp://$node");
$client->set('user:42:session', $someSessionObject);


// Fetching
$client = new \Predis\Client("tcp://$node");

// Cache hit, restore or create a new session when we had a miss.
if ($sessionSLOB = $client->get('user:42:session')) {
    $someSessionObject = SomeSessionObject::createFromSLOB($sessionSLOB);
} else {
    $someSessionObject = new SomeSessionObject();
}

?>
```

The same, but using the factory:
```php
<?php

// Create a manager object via our factory. Setting the adapter, the replicate count and the nodes
$factory = new \Canoma\Factory;
$manager = $factory->createManager($yourConfiguration);

// Do a lookup for your cache-identifier and see what node we can use.
$node = $manager->getNodeForString('user:42:session');

// Connect to you cache backend and save the cache, using your favorite backends and libraries
// ..


?>
```


Drivers
-------
Canoma uses an adapter pattern for the different hashing algorithms, there are a couple of types available:
* _Md5_ - Requires the BCMath library on 32bit architecture. Works very well on small and large keys
* _CRC32_ - Uses the CRC32b algorithm. Works very well on small and large keys
* _Salsa20_ - Uses the very fast Salsa20 algorithm, use on reasonably large keys
* _Adler32_ - Uses the very fast Adler32 algorithm, use on **large** keys only !

If you want to write your own, simply implement ```\Canoma\HashAdapterInterface``` and you're done. Be sure to send me a
patch or, even better, fork the project and send a push request!


The differences between drivers
-------------------------------
Different algorithms have different benefits. Faster algorithms tend to work quite poor at distributing, with too few
bytes as input because they use less bits to calculate. Meaning that it's very hard (if not impossible) to fan
hash-results out over the various nodes.

Choosing a hashing algorithm is should be done wisely. When you're dealing with small (amount of characters) hash keys,
choose a more complex algorithm (such as md5). When you're dealing with very large keys, you might want to pick a faster
algorithm, such as Salsa20 or Adler32. Either way, make sure you test first, using the added 'calculateReplicates.php'
script, which can be found in the test directory.


About
=====


What is consistent hashing?
---------------------------
Consistent hashing is a concept developed around 1997. The basic idea is to evenly distribute cache on distributed cache servers. To read an excellent write-up on the subject, be sure to read the following:

* http://weblogs.java.net/blog/tomwhite/archive/2007/11/consistent_hash.html
* http://www8.org/w8-papers/2a-webserver/caching/paper2.html


Acknowledgements
----------------
This library is inspired by the following projects and documents:

* http://www.allthingsdistributed.com/2007/10/amazons_dynamo.html
* https://github.com/pda/flexihash
* http://amix.dk/blog/post/19367
* http://www.codeproject.com/Articles/56138/Consistent-hashing


License
-------
Licensed under WTFPL - read the LICENSE file.


Author
------
* Mark van der Velden - mark at dynom.nl