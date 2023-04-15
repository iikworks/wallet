<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::factory(20)
            ->create()
            ->each(function (Organization $organization) {
                $rand = mt_rand(0, 2);
                if($rand > 0) Organization::factory($rand)->create([
                    'parent_id' => $organization->id,
                ]);
            });
    }
}
