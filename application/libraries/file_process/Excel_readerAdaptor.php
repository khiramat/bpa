<?php

class Excel_readerAdaptor implements File_readerInterface
{
    private $excel_reader;

    function set_reader(Excel_reader $excel_reader) {
        $this->excel_reader = $excel_reader;
    }
    function get_count() {
        return $this->excel_reader->get_count();
    }
    function get_line($line_number) {
        return $this->excel_reader->get_line($line_number);
    }
    function get_all_lines() {
        return $this->excel_reader->get_all_lines();
    }
}