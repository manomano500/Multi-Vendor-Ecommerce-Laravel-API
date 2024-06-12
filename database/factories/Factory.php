<?php

// database/factories/Factory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

class Factory extends BaseFactory
{
    /**
     * The namespaces for all custom Faker providers.
     *
     * @var array
     */
    protected $fakerProviders = [
        StoreNameProvider::class,
    ];

    public function definition()
    {
        // TODO: Implement definition() method.
    }
}
