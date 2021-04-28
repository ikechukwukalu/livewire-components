<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Service\LinkedList;
use Database\Factories\UserFactory;

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

    private function users() {
        DB::disableQueryLog();
        $list = new LinkedList();
        $faker = new UserFactory();
        $start = microtime(true);

        foreach (range(1, 1000) as $i) {
            $list->insertAtFront($faker->definition());
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Generated user records" . " \n";
        
        $data = $list->printNode();
        $chunks = array_chunk($data, 5000);
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Data inserted: " . number_format(count($data) ). " \n";
    }
}
