<?php
namespace App\Service;

use App\Service\Node;
use Iterator;

class IterateEloquent implements Iterator {
    public $ary;
    protected $data;
    private $key = 0;
    
    public function __construct($array) {
        $this->ary = $array;
        $this->key = 0;
        if (!is_array($this->ary)) throw new Exception();
    }
    public function __destruct() {
        unset($this->ary);
    }
    public function current() {
        return $this->ary[$this->key];
    }
    public function key() {
        return $this->key;
    }
    public function next() {
        $this->key ++;
    }
    public function rewind() {
        $this->key = 0;
    }
    public function valid() {
        return isset($this->ary[$this->key]);
    }
}