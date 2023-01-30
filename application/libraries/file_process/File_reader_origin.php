<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

interface iFile_reader {
    
    function get_count();
    function get_number();
    function get_property();
}

class Xml_readerAdaptor implements iFile_reader
{
    protected $xml_reader;
    function __construct(Xml_reader $xml_reader) {
        $this->xml_reader = simplexml_load_file('upload/test_xml.xml');
    }
    function get_count() {
        return $this->xml_reader->count();
    }
    function get_number() {
       $this->xml_reader->children();
    }
    function get_property() {
        return $this->xml_reader->children()->children();
    }
}

class Csv_readerAdaptor implements iFile_reader
{
//     protected $csv_handle = fopen("test.csv", "r");
    function get_count() {
        
    }
    function get_number() {
        ;
    }
    function get_property() {
        ;
    }
}

class Xml_reader {
    function get_count() {
        return $this->xml_reader->count();
    }
    function get_number() {
        $this->xml_reader->children();
    }
    function get_property() {
        return $this->xml->children()->children();
    }
}


class Csv_reader {
//     ;
}
