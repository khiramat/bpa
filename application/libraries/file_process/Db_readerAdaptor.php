<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Db_readerAdaptor implements File_readerInterface
{
    private $db_reader;
    
    function set_csv_reader(Db_reader $db_reader) {
        $this->db_reader = $db_reader;
    }
    function get_count() {
        return $this->db_reader->get_count();
    }
    function get_line($line_number) {
        return $this->db_reader->get_line($line_number);
    }
    function get_all_lines() {
        return $this->db_reader->get_property();
    }
}