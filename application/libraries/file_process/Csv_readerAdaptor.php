<?php

class Csv_readerAdaptor implements File_readerInterface
{
    private $csv_reader;

    function set_csv_reader(Csv_reader $csv_reader) {
        $this->csv_reader = $csv_reader;
    }
    function get_count() {
        return $this->csv_reader->get_count();
    }
    function get_line($line_number) {
        return $this->csv_reader->get_line($line_number);
    }
    function get_all_lines() {
        return $this->csv_reader->get_property();
    }
}