<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Sri Lankan–style first and last name pools for realistic data.
     */
    public function definition(): array
    {
        $sriLankanFirstNames = [
            'Ashan', 'Nuwan', 'Kamal', 'Dinesh', 'Priya', 'Sanduni',
            'Tharindu', 'Chaminda', 'Amara', 'Nadeesha', 'Ruwan', 'Shanaka',
            'Dilani', 'Mahesh', 'Lakshmi', 'Ranjith', 'Kumari', 'Suresh',
            'Nethmi', 'Isuru', 'Kavinda', 'Sachini', 'Harsha', 'Gayani',
            'Prasanna', 'Thisara', 'Malini', 'Buddhika', 'Iresha', 'Sampath',
            'Kasun', 'Chathurika', 'Dasun', 'Anusha', 'Dimuthu', 'Hasitha',
            'Janani', 'Lasith', 'Nirmala', 'Pathum', 'Rashmi', 'Saman',
            'Thilini', 'Upul', 'Vindya', 'Wasantha', 'Yasodha', 'Ajith',
        ];

        $sriLankanLastNames = [
            'Perera', 'Fernando', 'De Silva', 'Jayawardena', 'Bandara',
            'Wickramasinghe', 'Rathnayake', 'Gunasekara', 'Herath', 'Dissanayake',
            'Samarasinghe', 'Rajapaksa', 'Karunaratne', 'Wijesinghe', 'Senanayake',
            'Abeysekara', 'Thilakaratne', 'Gunawardena', 'Ekanayake', 'Kumarasinghe',
            'Weerasinghe', 'Liyanage', 'Pathirana', 'Cooray', 'Abeyratne',
        ];

        $firstName = $this->faker->randomElement($sriLankanFirstNames);
        $lastName  = $this->faker->randomElement($sriLankanLastNames);

        // NIC: old-style 9-digit + V/X  OR  new-style 12-digit
        $nic = $this->faker->boolean(60)
            ? $this->faker->numerify('#########') . $this->faker->randomElement(['V', 'X'])
            : $this->faker->numerify('############');

        return [
            'name'        => "{$firstName} {$lastName}",
            'nic'         => $nic,
            'phone'       => '+94 ' . $this->faker->numerify('7# ### ####'),
            'email'       => strtolower($firstName) . '.' . strtolower(str_replace(' ', '', $lastName)) . '@' . $this->faker->randomElement(['gmail.com', 'yahoo.com', 'outlook.com']),
            'intake_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
