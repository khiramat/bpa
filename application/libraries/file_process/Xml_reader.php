<?php

class Xml_reader {
    
    protected $xml_reader;
//     function __construct() {
    function set_simplexml($file_path) {
//         $this->xml_reader = simplexml_load_file('upload/test_xml.xml');
        $this->xml_reader = simplexml_load_file($file_path);
    }
    function get_count() {
        return $this->xml_reader->count();
    }
    function get_line($line_number) {
        return $this->xml_reader->children()[$line_number];//zero-base
    }
    function get_all_lines() {
        return $this->xml_reader->children()->children();
    }
}