<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
    class Db_reader {
        private $separator = ',';
        private $enclosure = '"';
        private $escape = '\\';
        private $max_row_size = 4096;
        private $db_reader;
        private $CI;
        
        function __construct() {
            $this->CI = & get_instance();
            $this->CI->load->model("bpa_acs_request_model");
        }

        function set_db($filepath)
        {
//                 $this->db_reader = ;
        }
        
        function get_count() {
            return $this->CI->get_count();
        }
        function get_line($line_number) {
            return $this->db_reader[$line_number];//zero-base
        }
        function get_all_lines() {
            return $this->db_reader;
        }
        function get_line_first() {
            return $CI->bpa_acs_request_model->get_first();
        }
    }