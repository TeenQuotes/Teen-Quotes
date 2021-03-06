<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Faker\Factory as Faker;
use TeenQuotes\Users\Models\ProfileVisitor;

class ProfileVisitorsTableSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Deleting existing ProfileVisitor table ...');
        ProfileVisitor::truncate();

        $faker = Faker::create();

        $this->command->info('Seeding ProfileVisitor table using Faker...');
        foreach (range(1, 400) as $index) {
            ProfileVisitor::create([
                'user_id'    => $faker->numberBetween(1, 100),
                'visitor_id' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
