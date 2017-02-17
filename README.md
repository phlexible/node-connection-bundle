PhlexibleNodeConnectionBundle
=============================

The PhlexibleNodeConnectionBundle adds support inter-node-connection in phlexible.

Installation
------------

1. Download PhlexibleNodeConnectionBundle using composer
2. Enable the Bundle
3. Clear the symfony cache

### Step 1: Download PhlexibleNodeConnectionBundle using composer

Add PhlexibleNodeConnectionBundle by running the command:

``` bash
$ php composer.phar require phlexible/node-connection-bundle "~1.0.0"
```

Composer will install the bundle to your project's `vendor/phlexible` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Phlexible\Bundle\NodeConnectionBundle\PhlexibleNodeConnectionBundle(),
    );
}
```

### Step 3: Update your database schema

Now that the bundle is set up, the last thing you need to do is update your database schema because the node connection bundle includes entities that need to be installed in your database.

For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 4: Clear the symfony cache

If you access your phlexible application with environment prod, clear the cache:

``` bash
$ php app/console cache:clear --env=prod
```
