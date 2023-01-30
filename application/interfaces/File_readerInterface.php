<?php

interface File_readerInterface {
    
    function get_count();
    function get_line($line_number);
    function get_all_lines();
    
}