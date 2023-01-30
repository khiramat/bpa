<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package CodeIgniter
 * @author ExpressionEngine Dev Team
 * @copyright Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license http://codeigniter.com/user_guide/license.html
 * @link http://codeigniter.com
 * @since Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class for Japanese
 *
 * @package
 * @subpackage Libraries
 * @category Validation
 * @author Copyright (c) 2011, AIDREAM.
 * @link
 */
class MY_Form_validation extends CI_Form_validation {

    /**
     * Constructor
     */
    public function __construct($rules = array())
    {
        parent::__construct($rules);
    }

    /**
     * Validationデータを初期化する(set_value関数などの履歴データ初期化)
     * 
     * @return
     */
    public function clear_field_data()
    {
        $this->_field_data = array ();
        return $this;
    }

    // --------------------------------------------------------------------
    
    /**
     * 半角チェック
     * @param
     * @return bool
     */
    function hankaku($str)
    {
        if (empty($str))
        {
            return TRUE;
        }
        return (strlen($str) != mb_strlen($str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * 全角チェック
     * @param
     * @return bool
     */
    function zenkaku($str)
    {
        if (empty($str))
        {
            return TRUE;
        }
        $ratio = (mb_detect_encoding($str) == 'UTF-8') ? 3 : 2;
        return (strlen($str) != mb_strlen($str) * $ratio) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * ひらがな チェック
     * @param
     */
    function hiragana($str)
    {
        if (empty($str))
        {
            return TRUE;
        }
        $str = mb_convert_encoding($str, 'UTF-8');
        return (!preg_match("/^(?:\xE3\x81[\x81-\xBF]|\xE3\x82[\x80-\x93]|ー)+$/", $str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * 全角カタカナ チェック
     * @param
     */
    function katakana($str)
    {
        if (empty($str))
        {
            return TRUE;
        }
        $str = mb_convert_encoding($str, 'UTF-8');
        return (!preg_match("/^(?:\xE3\x82[\xA1-\xBF]|\xE3\x83[\x80-\xB6]|ー)+$/", $str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * 半角カタカナ チェック
     * @param
     */
    function hankaku_katakana($str)
    {
        if (empty($str))
        {
            return TRUE;
        }
        $str = mb_convert_encoding($str, 'UTF-8');
        return (!preg_match("/^(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])+$/", $str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * 日付チェック
     * @param $date_str
     * @param $format
     * @return bool
     */
    function valid_date($date_str, $format)
    {
        if (empty($date_str))
        {
            return TRUE;
        }
        $d = DateTime::createFromFormat($format, $date_str);
        return $d && $d->format($format) == $date_str;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * 電話番号チェック
     * @param $tel
     * @return bool
     */
    function valid_tel($tel)
    {
        if (empty($tel))
        {
            return TRUE;
        }
        return preg_match("/^0\d{9,10}$/", $tel) ? TRUE : FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * 
     * @param $date_str
     * @param $format
     * @return bool
     */
    function remove_slash($str)
    {
        if (empty($str))
        {
            return '';
        }
        return str_replace('/', '', $str);
    }
    
    // --------------------------------------------------------------------
}