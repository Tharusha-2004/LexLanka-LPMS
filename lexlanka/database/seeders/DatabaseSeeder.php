<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\CourtDate;
use App\Models\Document;
use App\Models\LedgerEntry;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |----------------------------------------------------------------------
        | Staff Accounts
        |----------------------------------------------------------------------
        |
        | Three core roles: Partner, Associate, Clerk.
        |
        | Login credentials:
        |   Partner   →  partner@lexlanka.com    / password
        |   Associate →  associate@lexlanka.com  / password
        |   Clerk     →  clerk@lexlanka.com      / password
        |
        */
        $partner = User::updateOrCreate(
            ['email' => 'partner@lexlanka.com'],
            [
                'name'                 => 'Arjuna Rajapaksa',
                'password'             => Hash::make('password'),
                'role'                 => 'partner',
                'status'               => 'active',
                'branch'               => 'Colombo Head Office',
                'flat_appearance_rate' => 15000.00,
            ]
        );

        $associate = User::updateOrCreate(
            ['email' => 'associate@lexlanka.com'],
            [
                'name'                 => 'Dilshan Fernando',
                'password'             => Hash::make('password'),
                'role'                 => 'associate',
                'status'               => 'active',
                'branch'               => 'Colombo Head Office',
                'flat_appearance_rate' => 8500.00,
            ]
        );

        $clerk = User::updateOrCreate(
            ['email' => 'clerk@lexlanka.com'],
            [
                'name'                 => 'Nethmi Perera',
                'password'             => Hash::make('password'),
                'role'                 => 'clerk',
                'status'               => 'active',
                'branch'               => 'Colombo Head Office',
                'flat_appearance_rate' => 0.00,
            ]
        );

        $this->command->info('✓ Staff accounts created (partner, associate, clerk).');

        /*
        |----------------------------------------------------------------------
        | 50 Clients via Factory
        |----------------------------------------------------------------------
        */
        $clients = Client::factory()->count(50)->create();

        $this->command->info('✓ 50 clients created.');

        /*
        |----------------------------------------------------------------------
        | Legal Cases — 1 to 3 per client
        |----------------------------------------------------------------------
        | Each case is randomly assigned to either the Partner or Associate.
        */
        $attorneys    = collect([$partner->id, $associate->id]);
        $caseCount    = 0;
        $courtCount   = 0;
        $ledgerCount  = 0;

        foreach ($clients as $client) {
            $numCases = rand(1, 3);

            for ($i = 0; $i < $numCases; $i++) {
                $case = LegalCase::factory()->create([
                    'client_id'            => $client->id,
                    'assigned_attorney_id' => $attorneys->random(),
                ]);
                $caseCount++;

                // ── Court Dates: 0–3 per case ────────────────────────
                $numDates = rand(0, 3);
                for ($d = 0; $d < $numDates; $d++) {
                    CourtDate::create([
                        'case_id'       => $case->id,
                        'date'          => now()->addDays(rand(3, 180))->setTime(rand(8, 15), rand(0, 1) * 30),
                        'type'          => fake()->randomElement(['calling_date', 'trial_date']),
                        'reminder_sent' => false,
                    ]);
                    $courtCount++;
                }

                // ── Ledger Entries: 0–4 per case ─────────────────────
                $numEntries = rand(0, 4);
                $descriptions = [
                    'trust' => [
                        'Initial retainer deposit',
                        'Client trust top-up',
                        'Retainer payment received',
                        'Trust fund replenishment',
                    ],
                    'operational' => [
                        'Filing fee payment',
                        'Court stamp duty',
                        'Service of process charge',
                        'Notarial attestation fee',
                        'Appearance fee — trial date',
                        'Document preparation charge',
                    ],
                ];

                for ($e = 0; $e < $numEntries; $e++) {
                    $type = fake()->randomElement(['trust', 'operational']);
                    LedgerEntry::create([
                        'case_id'     => $case->id,
                        'recorded_by' => $partner->id,
                        'type'        => $type,
                        'amount'      => fake()->randomFloat(2, 500, 50000),
                        'description' => fake()->randomElement($descriptions[$type]),
                    ]);
                    $ledgerCount++;
                }
            }
        }

        $this->command->info("✓ {$caseCount} legal cases created.");
        $this->command->info("✓ {$courtCount} court dates scheduled.");
        $this->command->info("✓ {$ledgerCount} ledger entries recorded.");
        $this->command->newLine();
        $this->command->info('🎉 Database seeded successfully!');
    }
}
