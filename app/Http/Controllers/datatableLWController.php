<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class datatableLWController extends Controller
{
        /**
         * Table Header | Footer columns
         */
        private $columns = [
            ['name' => 'name', 'sort' => 'name'],
            ['name' => 'email', 'sort' => 'email'],
            ['name' => 'phone', 'sort' => 'phone'],
            ['name' => 'gender', 'sort' => 'gender'],
            ['name' => 'country', 'sort' => 'country'],
            ['name' => 'state', 'sort' => 'state'],
            ['name' => 'city', 'sort' => 'city'],
            ['name' => 'address', 'sort' => 'address'],
        ];

        /**
         * ['column', 'true - asc|false - desc'] is effective if private $sort is set to columns
         * To prevent SQL injection for this example I've whitelisted the necessary columns
         * by including them into a $white_list array within the liveWire class
         */
        private $order_by = [];

        /**
         * Dropdown options for the number of rows that can be fetched
         */
        private $page_options = [10, 15, 25, 50, 100];

        /**
         * Default page_options value
         */
        private $fetch = "";

        /**
         * Sort Table
         * -----------
         * columns | not recommended for large records exceeding 5k,
         * latest | speed is very good,
         * null | speed is the fastest
         */
        private $sort = 'columns';

        /**
         * Max allowed for numbered paginator | switch to simple paginator
         */
        private $maxP = 5000;

        /**
         * Cache Pages | boolean
         */
        private $cache = true;

        /**
         * Max allowed time for cached query to last
         */
        private $cache_time = 300; // 5 minutes

        public function __construct() {
            $this->order_by = [$this->columns[0]['sort'], true];
            $this->fetch = $this->page_options[0];
        }

        public function table() {
            return view('datatable', [
                'columns' => $this->columns,
                'order_by' => $this->order_by,
                'page_options' => $this->page_options,
                'fetch' => $this->fetch,
                'sort' => $this->sort,
                'maxP' => $this->maxP,
                'cache' => $this->cache,
                'cache_time' => $this->cache_time,
            ]);
        }
}
