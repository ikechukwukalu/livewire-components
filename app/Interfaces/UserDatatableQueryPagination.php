<?php

namespace App\Interfaces;

interface UserDatatableQueryPagination {
    public function query_users_table();
    public function fetch_users_table();
    public function search_query($query);
    public function implement_numbered_paginator(): object;
    public function no_search_numbered_paginator(): object;
    public function with_search_numbered_paginator(): object;
    public function implement_simple_paginator(): object;
    public function no_search_simple_paginator(): object;
    public function with_search_simple_paginator(): object;
}