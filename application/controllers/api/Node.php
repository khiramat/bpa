<?php

//use Restserver\Libraries\REST_Controller;
// require(APPPATH.'/libraries/REST_Controller.php');
require APPPATH . '/libraries/REST_Controller.php';

class Node extends \Restserver\Libraries\REST_Controller
{
  public function g_get()
  {
    // Display all books
    $this -> response(array("just get" => "success!"));
  }

  public function p_post()
  {
    // Create a new book
    $this -> response(array("just post" => "success!", "in" => "13.113.94.65", "can't write log" => "sure"));
    try {
      log_message("info", "from p_post");
    }
    catch(Exception $e) {
      print_r($e);
    }
  }

  /**
   * @desc 5.1回線契約の新規開通・MNP転入受付開通依頼に対する処理結果をリクエストで送ります。→　開通
   */
  public function open_post()
  {
    ;
  }

  /**
   * @desc 5.2サービスプランや暗証番号等の変更依頼、SIMカードの再発行依頼に対する処理結果をリクエストで送ります。→　変更
   */
  public function modify_post()
  {
    ;
  }

  /**
   * @desc 5.3回線契約の解約依頼に対する処理結果をリクエストで送ります。→　契約・解約
   */
  public function contract_post()
  {
    ;
  }

  /**
   * @desc 5.4MNP可否や、WORLDWING累積額、WORLDCALL累積額の照会依頼に対する処理結果をリクエストで送ります。→　照会
   */
  public function search_post()
  {
    ;
  }


}
