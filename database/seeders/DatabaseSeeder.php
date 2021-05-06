<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Service\LinkedList;
use App\Service\IterateEloquent;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');
        $this->users();
    }
    
    private function random_digits($num = 4) {
        $char = range(1, 9);
        $rand_max = array_rand($char,$num);
        $value  = $char[$rand_max[0]];
        for($i = 1; $i < $num; $i ++) {
            $value  .= $char[$rand_max[$i]];
        }
        return $value;
    }

    private function user_definition($faker)
    {
        $genders = ["male", "female"];
        $gender = rand(0,1);
        /*
         * Uncomment this for a faster run time but with less 
         * realistic user details and you must also comment 
         * out the one below it.
         */
        // return [
        //     'name' => Str::random(5),
        //     'email' => Str::random(5).'@example.com',
        //     'phone' => "080". $this->random_digits(8),
        //     'gender' => $genders[$gender],
        //     'country' => Str::random(5),
        //     'state' => Str::random(5),
        //     'city' => Str::random(5),
        //     'address' => Str::random(10),
        // ];
        return [
            'name' => $faker->name($gender),
            'email' => $faker->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'gender' => $genders[$gender],
            'country' => $faker->country(),
            'state' => $faker->state(),
            'city' => $faker->city(),
            'address' => $faker->address()
        ];
    }

    private function users() {
        DB::disableQueryLog();
        $faker = \Faker\Factory::create();
        $start = microtime(true);

        $list = new LinkedList();
        $list_chunks = new IterateEloquent(range(1, 5000));
        foreach ($list_chunks as $list_chunk) {
            $list->insertAtFront($this->user_definition($faker));
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Generated user records" . " \n";
        
        $data = $list->printNode();
        $chunks = array_chunk($data, 5000);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Chunked array size: " . count($chunks) . " \n";
        
        $chunks = new IterateEloquent($chunks);
        foreach ($chunks as $key => $chunk) {
            DB::table('users')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 3: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)). " \n";
    }
}
