<?php

namespace App\Support;

class ArraySorter {
    public $field;

    function __construct($field) {
        $this->field = $field;
    }

    function sort($a, $b) {
        if ($a[$this->field] == $b[$this->field]) return 0;
        return ($a[$this->field] > $b[$this->field]) ? 1 : -1;
    }
}