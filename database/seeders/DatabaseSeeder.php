<?php

namespace Database\Seeders;

use App\Service\IterateEloquent;
use App\Service\LinkedList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();
        ini_set('memory_limit', '1024M');
        echo "*****USERS*****" . " \n";
        $this->users();
        echo "\n" . "*****POLITICAL POSITIONS*****" . " \n";
        $this->political_positions();
        echo "\n" . "*****POLITICIANS*****" . " \n";
        $this->politicians();
        echo "\n" . "*****DEPARTMENTs*****" . " \n";
        $this->departments();
        echo "\n" . "*****STAFFS*****" . " \n";
        $this->staffs();
    }

    private function random_digits($num = 4)
    {
        $char = range(1, 9);
        $rand_max = array_rand($char, $num);
        $value = $char[$rand_max[0]];
        for ($i = 1; $i < $num; $i++) {
            $value .= $char[$rand_max[$i]];
        }
        return $value;
    }

    private function user_definition($faker)
    {
        $genders = ["male", "female"];
        $gender = rand(0, 1);
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
            'name' => $faker->name($genders[$gender]),
            'email' => $faker->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'gender' => $genders[$gender],
            'country' => $faker->country(),
            'state' => $faker->state(),
            'city' => $faker->city(),
            'address' => $faker->address(),
        ];
    }

    private function users()
    {
        $faker = \Faker\Factory::create();
        $start = microtime(true);

        $rows = 5000;
        $list = new LinkedList();
        $list_chunks = new IterateEloquent(range(1, $rows));
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
        echo "Part 3: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)) . " \n";

        //Over time COUNT clause could become expensive when using innoDB so this is my solution.
        $total = DB::table('users')->count();
        $user_rows = DB::table('user_rows')->first();
        if (isset($user_rows->id)) {
            DB::table('user_rows')->update(['number' => $total]);
        } else {
            DB::table('user_rows')->insert(['number' => $total]);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 4: " . $time_elapsed_secs . ", Number of user rows: " . number_format($total) . " \n";
    }

    private function political_positions()
    {
        $faker = \Faker\Factory::create();
        $start = microtime(true);

        $data = [
            ['position' => 'President'],
            ['position' => 'Vice President'],
            ['position' => 'Speaker of the House of Representatives'],
            ['position' => 'President pro tempore of the Senate'],
            ['position' => 'Secretary of State'],
            ['position' => 'Secretary of the Treasury'],
            ['position' => 'Secretary of Defense'],
            ['position' => 'Attorney General'],
            ['position' => 'Secretary of the Interior'],
            ['position' => 'Secretary of Agriculture'],
            ['position' => 'Secretary of Commerce'],
            ['position' => 'Secretary of Labor'],
            ['position' => 'Secretary of Health and Human Services'],
            ['position' => 'Secretary of Housing and Urban Development'],
            ['position' => 'Secretary of Transportation'],
            ['position' => 'Secretary of Energy'],
            ['position' => 'Secretary of Education'],
            ['position' => 'Secretary of Veterans Affairs'],
            ['position' => 'Secretary of Homeland Security'],
        ];

        $chunks = array_chunk($data, 20);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Chunked array size: " . count($chunks) . " \n";

        $chunks = new IterateEloquent($chunks);
        foreach ($chunks as $key => $chunk) {
            DB::table('political_positions')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)) . " \n";
    }

    private function politician_definition($faker, $num)
    {
        $genders = ["male", "female"];
        $gender = rand(0, 1);

        /*
         * Uncomment this for a faster run time but with less
         * realistic user details and you must also comment
         * out the one below it.
         */

        // return [
        //     'name' => Str::random(5),
        //     'political_position_id' => $num
        // ];
        return [
            'name' => $faker->name($genders[$gender]),
            'political_position_id' => $num,
        ];
    }

    private function politicians()
    {
        $faker = \Faker\Factory::create();
        $start = microtime(true);

        $total = DB::table('political_positions')->count();
        for ($i = 1; $i <= $total; $i++) {
            $data[] = $this->politician_definition($faker, $i);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Generated politician records" . " \n";

        $chunks = array_chunk($data, 5000);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Chunked array size: " . count($chunks) . " \n";

        $chunks = new IterateEloquent($chunks);
        foreach ($chunks as $key => $chunk) {
            DB::table('politicians')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 3: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)) . " \n";
    }

    private function departments()
    {
        $faker = \Faker\Factory::create();
        $start = microtime(true);
        
        $data = [
            ['name' => 'Department A', 'position' => 1],
            ['name' => 'Department B', 'position' => 2],
            ['name' => 'Department C', 'position' => 3],
            ['name' => 'Department D', 'position' => 4]
        ];

        $chunks = array_chunk($data, 4);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Chunked array size: " . count($chunks) . " \n";

        $chunks = new IterateEloquent($chunks);
        foreach ($chunks as $key => $chunk) {
            DB::table('departments')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)) . " \n";
    }

    private function staff_definition($faker, $num, $k)
    {
        $genders = ["male", "female"];
        $gender = rand(0, 1);

        /*
         * Uncomment this for a faster run time but with less
         * realistic user details and you must also comment
         * out the one below it.
         */

        // return [
        //     'name' => Str::random(5),
        //     'department_id' => $num
        //     'position' => $k,
        // ];
        return [
            'name' => $faker->name($genders[$gender]),
            'department_id' => $num,
            'position' => $k,
        ];
    }

    private function staffs()
    {
        $faker = \Faker\Factory::create();
        $start = microtime(true);

        $total = DB::table('departments')->count();
        for ($i = 1; $i <= $total; $i++) {
            for($k = 1; $k <= 5; $k ++) {
                $data[] = $this->staff_definition($faker, $i, $k);
            }
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Generated staff records" . " \n";

        $chunks = array_chunk($data, 20);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Chunked array size: " . count($chunks) . " \n";

        $chunks = new IterateEloquent($chunks);
        foreach ($chunks as $key => $chunk) {
            DB::table('staffs')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 3: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data)) . " \n";
    }
}
