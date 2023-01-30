<?php

// require_once '/Library/WebServer/Documents/bpa/application/libraries/file_process/File_readerInterface';

class Xml_readerAdaptor implements File_readerInterface
{
    private $xml_reader;
//     function __construct(Xml_reader $xml_reader) {
//         $this->xml_adaptor = $xml_reader;
//     }
    
    function set_xml_reader(Xml_reader $xml_reader) {
        $this->xml_reader = $xml_reader;
//         print_r($this->xml_reader);
    }
    
    function get_count() {
        return $this->xml_reader->get_count();
    }
    function get_line($line_number) {
        return $this->xml_reader->get_line($line_number);
    }
    function get_all_lines() {
        return $this->xml_reader->get_property();
    }
}