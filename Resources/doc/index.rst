StasPlovDtoValidatorBundle
================

The StasPlovDtoValidatorBundle allows you to send `Cross-Origin Resource Sharing`_
headers with ACL-style per-URL configuration.

If you need it, check `this flow chart image`_ to have a global overview of
entire CORS workflow.

Installation
------------

Require the ``stas-plov/dto-validator-bundle`` package in your composer.json and update
your dependencies:

.. code-block:: terminal

    $ composer require stas-plov/dto-validator-bundle

The bundle should be automatically enabled by `Symfony Flex`_. If you don't use
Flex, you'll need to manually enable the bundle by adding the following line in
the ``config/bundles.php`` file of your project::

    <?php
    // config/bundles.php

    return [
        // ...
        StasPlov\DtoValidatorBundle\StasPlovDtoValidatorBundle::class => ['all' => true],
        // ...
    ];

If you don't have a ``config/bundles.php`` file in your project, chances are that
you're using an older Symfony version. In this case, you should have an
``app/AppKernel.php`` file instead. Edit such file::

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                // ...

                new StasPlov\DtoValidatorBundle\StasPlovDtoValidatorBundle(),
            ];

            // ...
        }

        // ...
    }