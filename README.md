Canoma - Caching Node Manager
=============================

Usage
-----

Example usage
```
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
$node = $manager->getNodeForString('user:42');


// Connect to you cache backend and save the cache, using your favorite backends and libraries
// ..


?>
```

What is Canoma?
---------------

Canoma (CAching NOde MAnager) is a consistent hashing implementation. The ideas behind it:

* Providing a intuitive API
* Provide a flexible library
* Unit-tested (This project is being developed using TDD)


What is consistent hashing?
---------------------------

Consistent hashing is a concept developed around 1997. The basic idea is to evenly distribute cache on distributed cache servers. To read an excellent write-up on the subject, be sure to read the following:

* http://weblogs.java.net/blog/tomwhite/archive/2007/11/consistent_hash.html
* http://www8.org/w8-papers/2a-webserver/caching/paper2.html
