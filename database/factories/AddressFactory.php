<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Country;
use App\Models\Voivodeship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $country = Country::all()->random();

        return [
            'country_id' => $country->id,
            'voivodeship_id' => $country->code === 'PL'
                ? Voivodeship::all()->random()->id
                : null,
            'state' => $country->code !== 'PL'
                ? $this->faker->streetName()
                : null,
            'post_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'house_number' => $this->faker->buildingNumber(),
            'apartment_number' => $this->faker->boolean()
                ? $this->faker->buildingNumber()
                : null,
            'post_office' => $this->faker->city(),
        ];
    }
}
