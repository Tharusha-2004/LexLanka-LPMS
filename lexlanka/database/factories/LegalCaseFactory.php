<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LegalCase>
 */
class LegalCaseFactory extends Factory
{
    protected $model = LegalCase::class;

    public function definition(): array
    {
        return [
            'client_id'            => Client::factory(),
            'assigned_attorney_id' => User::factory(),
            'case_type'            => $this->faker->randomElement([
                'Civil Litigation',
                'Criminal Defence',
                'Corporate Contract',
                'Property Dispute',
                'Family Law',
                'Labour Tribunal',
                'Land Acquisition',
                'Motor Vehicle Accident',
                'Intellectual Property',
                'Immigration & Visa',
            ]),
            'status'               => $this->faker->randomElement([
                'pending',
                'active',
                'trial_scheduled',
                'judgment_delivered',
                'case_closed',
            ]),
        ];
    }
}
