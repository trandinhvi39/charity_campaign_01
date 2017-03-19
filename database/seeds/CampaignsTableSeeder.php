<?php

use App\Models\Group;
use App\Models\Campaign;
use App\Models\Image;
use App\Models\UserCampaign;
use App\Models\Contribution;
use App\Models\Category;
use App\Models\CategoryContribution;
use Illuminate\Database\Seeder;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campaign::truncate();
        $faker = Faker\Factory::create();

        static $userIds;

        factory(Campaign::class, 20)->create()->each(function ($campaign) use ($faker, $userIds) {
            $campaign->image()->create([
                'image' => 'campaign.png',
            ]);

            UserCampaign::create([
                'user_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
                'campaign_id' => $campaign->id,
                'is_owner' => 1,
                'status' => 1,
            ]);

            foreach (range(1, 2) as $key) {
                $category = Category::create([
                    'name' => $faker->word,
                    'campaign_id' => $campaign->id,
                    'goal' => $faker->randomDigitNotNull,
                    'unit' => $faker->word,
                ]);

                $contribution = Contribution::create([
                    'user_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
                    'campaign_id' => $campaign->id,
                    'name' => $faker->name,
                    'email' => $faker->safeEmail,
                    'count' => $faker->randomDigitNotNull,
                    'description' => $faker->paragraph,
                    'status' => $faker->boolean,
                ]);

                CategoryContribution::create([
                    'category_id' => $category->id,
                    'contribution_id' => $contribution->id,
                    'amount' => $faker->randomDigitNotNull,
                ]);
            }

            Group::create([
                'campaign_id' => $campaign->id,
                'name' => $faker->word,
            ]);
        });
    }
}
