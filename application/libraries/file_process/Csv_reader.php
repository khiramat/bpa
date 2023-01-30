<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csv_reader {
    private $fields;
    private $separator = ',';
    private $enclosure = '"';
    private $escape = '\\';
    private $max_row_size = 4096;
    private $csv_reader;
    
    function set_csv($filepath)
    {
//         $csv = array_map('str_getcsv', file($filepath));
        $csv = array_map(function($d) {
            return str_getcsv($d, ',', '"', '\\');
        }, file($filepath));
        $this->csv_reader = $csv;
    }
    
    function get_count() {
        return count($this->csv_reader);
    }
    function get_line($line_number) {
        return $this->csv_reader[$line_number];//zero-base
    }
    function get_all_lines() {
        return $this->csv_reader;
    }
    
}