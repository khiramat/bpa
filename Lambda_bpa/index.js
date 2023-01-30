// ・設定
// 関数名 : bpa_receive_api(veri)
// ハンドラ : index.handler
// ランタイム : Node.js 8.10
//
// ・環境変数
// db_host : bpa-db-veri.cdlipyeyk4oq.ap-northeast-1.rds.amazonaws.com（データベースのURL）
// db_name : bpa（データベース名）
// db_pwd : Today123（パスワード）
// db_user : ranger（ユーザ名）
// pool_group : foma01-ingics.com（デフォルトのRADIUSパラメータを指定する）
// pool_group_aj010 : lte16-ingics.com（AJ010時のRADIUSパラメータを指定する）
// TZ : Asia/Tokyoを指定する
// debug_mode : ERROR（ログ表示モード。デフォルトはERROR。ERRORとINFOとWARNとDEBUGを指定する）
// ※debug_modeの実装はerrorMessage.js

const mysql = require("mysql");
const async = require("async");
const check = require("./check.js");
const util = require('util');
const error_message = require("./errorMessage.js");
const pool = mysql.createPool({
  host: process.env.db_host,
  user: process.env.db_user,
  password: process.env.db_pwd,
  database: process.env.db_name
});

//const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1", "AJ010": "4", "AJ034": "5", "AJ055": "6"};
//const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1"};
const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1", "AJ010": "4"}; //jw_kato 20190826

exports.handler = (event, context, callback) => {

  // console.log(`[${LOG_ID}] Start function.`); //---------------------------------------
  // console.log(`[${LOG_ID}] event:${JSON.stringify(event)}`); //---------------------------------------

  // ./check.jsでのエラーログ表示のためcontextを代入
  //check.context = context

  // エラーメッセージの初期化
  error_message.context = context
  error_message.arrow =  "MVNO -> BPA"
  error_message.if_name = "BPA Receive API"

  // タイムゾーン指定チェック
  if (typeof(process.env.TZ) === "undefined") {
    error_message.view_error_messages_to_log({
      error_code: "11001",
      error_level: "WRAN",
      message: 'lambdaの環境変数に"TZ"が設定されていません。lambdaの環境変数に"TZ”を追加し値に"Asia/Tokyo"を指定してください。',
    })
  }
  if (process.env.TZ !== "Asia/Tokyo") {
    error_message.view_error_messages_to_log({
      error_code: "11002",
      error_level: "WRAN",
      message: 'lambdaの環境変数の"TZ"に"Asia/Tokyo"が設定されていません。lambdaの環境変数に"TZ”の値に"Asia/Tokyo"を指定してください。 : ' + process.env.TZ
    })
  }

  error_message.view_error_messages_to_log({
    error_code: "11010",
    error_level: "INFO",
    message: "APIリクエスト処理を開始します。 event : " +  JSON.stringify(event)
  })

  //let forwarded_ipaddress = "";
  //let ipaddress = "";
  //if (typeof event.headers["x-forwarded-for"] !== "undefined" && event.headers["x-forwarded-for"] !== "") {
    //forwarded_ipaddress = event.headers["x-forwarded-for"];
    //ipaddress = forwarded_ipaddress.split(", ")[forwarded_ipaddress - 1];
  //} else if (typeof event.headers["X-Forwarded-For"] !== "undefined" && event.headers["X-Forwarded-For"] !== "") {
    //forwarded_ipaddress = event.headers["X-Forwarded-For"];
    //ipaddress = forwarded_ipaddress.split(", ")[forwarded_ipaddress - 1];
  //} else {
    //const response = {
      //message: "想定外のエラーが発生。",
      //errcode: "E9000"
    //};
    //return callback(JSON.stringify(response));
  //}

  error_message.view_error_messages_to_log({
    error_code: "11020",
    error_level: "DEBUG",
    message: "ヘッダ処理を開始します。"
  })
  let tenant_id = "";
  let tenant_name = "";
  if (typeof event.headers["x-acs-mvno-id"] !== "undefined" && event.headers["x-acs-mvno-id"] !== "") {
    tenant_id = event.headers["x-acs-mvno-id"];
  } else if (typeof event.headers["X-Acs-Mvno-Id"] !== "undefined" && event.headers["X-Acs-Mvno-Id"] !== "") {
    tenant_id = event.headers["X-Acs-Mvno-Id"];
  } else {
    error_message.view_error_messages_to_log({
      error_code: "11024",
      error_level: "ERROR",
      message: "BPA X-Acs-Mvno-Idヘッダが指定されていません。"
    })
    const response = {
      message: "想定外のエラーが発生。",
      errcode: "E9000"
    };
    return callback(JSON.stringify(response));
  }
  // エラーログ表示のためtenant_idを代入
  check.tenant_id = tenant_id
  error_message.tenant_id = tenant_id

  error_message.view_error_messages_to_log({
    error_code: "11021",
    error_level: "DEBUG",
    message: "ヘッダ処理を終了します。"
  })


  error_message.view_error_messages_to_log({
    error_code: "11050",
    error_level: "DEBUG",
    message: "リクエスト内容の取得を開始します。"
  })
  const body = event.body;
  error_message.view_error_messages_to_log({
    error_code: "11051",
    error_level: "INFO",
    message: "リクエスト内容の取得を終了しました : " + util.inspect(body,false,null)
  })
  async.waterfall([
    //(callback) => {
      //pool.getConnection((err, con) => {
        //con.beginTransaction((err) => {
          //if (err) {
            //console.log(`[${LOG_ID}] Connection Error.`);
            //console.log(`Error Code is ${err.code}`);
            //const response = JSON.stringify(ERROR_MESSAGE_500);
            //return callback(response);
          //}
          //const tenant_select_query = `select IPADDRESS from TENANT where TENANT_ID = ${tenant_id}`;
          //con.query(tenant_select_query, (err, results) => {
            //if (err) {
              //console.log(`[${LOG_ID}] Error Code is ${err.code}`);
              //const response = JSON.stringify(ERROR_MESSAGE_500);
              //return callback(response);
            //}
            //if (ipaddress.indexOf(results[0].IPADDRESS) === -1) {
              //const response = {
                //status: 422,
                //message: "受付できない事業者からの要求です、もしくは事業者コード（トランザクションIDの上2桁）と販売店コードの組み合わせが不正です。",
                //errcode: "I0006"
              //};
              //return callback(response);
            //}
          //});
        //});
      //});
    //},

    (callback) => {
      pool.getConnection((err, con) => {
        if (err) {
          error_message.view_error_messages_to_log({
            error_code: "11025",
            error_level: "ERROR",
            message: "店舗名取得時にデータベースの接続エラーが発生しました。 : " + err.code
          })
          con.release()
          const response = JSON.stringify(ERROR_MESSAGE_500)
          return callback(response)
        }
        con.beginTransaction((err) => {
          if (err) {
            error_message.view_error_messages_to_log({
              error_code: "11026",
              error_level: "ERROR",
              message: "店舗名取得時にデータベースのトランザクション開始時にエラーが発生しました。 : " + err.code
            })
            const response = JSON.stringify(ERROR_MESSAGE_500)
            return callback(response)
          }
          const tenant_select_query = `SELECT * FROM TENANT where TENANT_ID = ${tenant_id};`
          con.query(tenant_select_query, (err, results) => {
            if (err) {
              error_message.view_error_messages_to_log({
                error_code: "11027",
                error_level: "ERROR",
                message: "店舗名取得時にSQLエラーが発生しました。 : " + err.code + tenant_select_query
              })
              const response = JSON.stringify(ERROR_MESSAGE_500)
              return callback(response)
            } else {
              // 正常取得
              tenant_name = results[0].TENANT_NAME
              // テナント名をセット
              check.tenant_name = tenant_name
              error_message.tenant_name = tenant_name
            }
          })
        })
        con.release()
        callback(null);
      })
    },

    (callback) => {
      let response = "";
      //console.log(`[${LOG_ID}] Validation Check.`);
      error_message.view_error_messages_to_log({
        error_code: "11060",
        error_level: "DEBUG",
        message: "バリデーションチェックを開始します。"
      })
      async.eachSeries(Object.keys(body), (key, callback) => {
        //console.log(`key: ${key}`);
        check.order_no = key
        response = check.check_order_no(key);
        if (response[0]) {
          callback(response[1]);
        } else {
          const data = body[key]
          const transactionTYPE = data.transactionTYPE
          const denwabango = data.denwabango
          const ryokinplan = data.ryoukinplan
          const cardkeijo = data.cardkeijo

          response = check.check_transactionTYPE(transactionTYPE)
          if (response[0]) {
            return callback(response[1])
          }
          if (transactionTYPE === "A2") {
            response = check.check_ryokinplan(ryokinplan)
            if (response[0]) {
              return callback(response[1])
            }
            response = check.check_cardkeijo(cardkeijo)
            if (response[0]) {
              return callback(response[1])
            }
          } else {
            response = check.check_denwabango(denwabango)
            if (response[0]) {
              return callback(response[1])
            }
          }
          callback(null)
        }
      },
      (err) => {
        if (err) {
          error_message.view_error_messages_to_log({
            error_code: "11062",
            error_level: "ERROR",
            message: "バリデーションエラーが発生しました。"
          })
          callback(err);
        } else {
          callback(null);
        }
      });
      error_message.view_error_messages_to_log({
        error_code: "11061",
        error_level: "DEBUG",
        message: "バリデーションチェックを終了します。"
      })
    },
    (callback) => {
      error_message.view_error_messages_to_log({
        error_code: "11040",
        error_level: "DEBUG",
        message: "データベースに接続します。"
      })
      pool.getConnection((err, con) => {
        con.beginTransaction((err) => {
          if (err) {
            error_message.view_error_messages_to_log({
              error_code: "11047",
              error_level: "ERROR",
              message: "データベース更新時にデータベースのトランザクション開始時にエラーが発生しました。 :" + err.code
            })
            //console.log(`[${LOG_ID}] Connection Error.`);
            //console.log(`Error Code is ${err.code}`); //---------------------------------------
            const response = JSON.stringify(ERROR_MESSAGE_500);
            return callback(response);
          }
          //console.log(`[${LOG_ID}] Connected to mysql.`); //---------------------------------------
          async.eachSeries(Object.keys(body), (key, callback) => {







            //console.log(`key: ${key}`); //---------------------------------------
            const order_no = key;
            const data = body[key];
            const transactionTYPE = data.transactionTYPE;
            const denwabango = data.denwabango;
            const ryokinplan = data.ryoukinplan;
            const cardkeijo = data.cardkeijo;
            let seizobango = "";
            // ./check.jsでのエラーログ表示のためtenant_idを代入
            check.order_no = order_no

            //console.log(`[${LOG_ID}][${transactionTYPE}] order_no: ${order_no}`); //---------------------------------------
            const contracttype = RYOKINPLAN_CONTRACTTYPE_MAP[ryokinplan];
            async.waterfall([
              (callback) => {
                if (transactionTYPE === "A2") {
                  const sim_inventory_select_query = `select SEIZOBANGO from SIM_INVENTORY where USED_BY_OPEN = "0" and DELETE_FLAG = "0" and SIM_TYPE = "${cardkeijo}" order by UPDATE_DATETIME limit 1`;
                  con.query(sim_inventory_select_query, (err, results) => {
                    if (err) {
                      error_message.view_error_messages_to_log({
                        error_code: "11048",
                        error_level: "ERROR",
                        message: "データベース参照時にSQLエラーが発生しました。 : [" + order_no + "][" + transactionTYPE + "][" + err.code + "][" + sim_inventory_select_query * "]"
                      })
                      //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}] Error Code is ${err.code}`); //---------------------------------------
                      const response = JSON.stringify(ERROR_MESSAGE_500);
                      return callback(response);
                    }
                    seizobango = results[0].SEIZOBANGO;
                    //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}] seizobango：${seizobango}`); //---------------------------------------
                    callback(null);
                  });

                  error_message.view_error_messages_to_log({
                    error_code: "11046",
                    error_level: "DEBUG",
                    message: "データベースを参照しました。 : " + sim_inventory_select_query
                  })
                } else {
                  callback(null);
                }
              },
              (callback) => {
                if (transactionTYPE === "A2") {
                  const sim_inventory_update_query = `update SIM_INVENTORY set USED_BY_OPEN = "1", UPDATE_DATETIME = CURRENT_TIMESTAMP where SEIZOBANGO = "${seizobango}"`;
                  con.query(sim_inventory_update_query, (err, results) => {
                    if (err) {
                      error_message.view_error_messages_to_log({
                        error_code: "11049",
                        error_level: "ERROR",
                        message: "データベース更新時にSQLエラーが発生しました。 : " + err.code + sim_inventory_select_query
                      })
                      //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}] Error Code is ${err.code}`); //---------------------------------------
                      const response = JSON.stringify(ERROR_MESSAGE_500);
                      return callback(response);
                    }
                    //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}] Updated Count：${JSON.stringify(results.affectedRows)}`); //---------------------------------------
                    callback(null);
                  });
                  error_message.view_error_messages_to_log({
                    error_code: "11044",
                    error_level: "DEBUG",
                    message: "データベースを更新しました。 : " + sim_inventory_update_query
                  })
                } else {
                  callback(null);
                }
              },
              (callback) => {
                let data_row = {
                  TENANT_ID: tenant_id,
                  transactionTYPE: transactionTYPE,
                  orderbango: order_no,
                  LINE_CNT: 1,
                  INPUT_STATUS: "2",
                  CALL_STATUS: "4",
                  INPUT_KBN: "2", // とりあえずAPIとわかるように2としとく
                  DELETE_FLAG: "0"
                };
                if (transactionTYPE === "A2") {
                  data_row.sousaservice = "0D0021";
                  data_row.ryokinplan = ryokinplan;
                  data_row.cardkeijo = cardkeijo;
                } else {
                  data_row.denwabango = denwabango;
                }
                con.query("insert into INPUT_DATA set ?", data_row, (err, result) => {
                  if (err) {
                    //console.log(`Error Code is ${err.code}`); //---------------------------------------
                    error_message.view_error_messages_to_log({
                      error_code: "11045",
                      error_level: "ERROR",
                      message: "データベースの参照に失敗しました。 : " + err.code + " insert into INPUT_DATA set ?" + "   " + util.inspect(data_row,false,null)
                    })
                    const response = JSON.stringify(ERROR_MESSAGE_500);
                    return callback(response);
                  }
                  //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}][INPUT_DATA]Inserted Count： ${JSON.stringify(result.affectedRows)}`); //---------------------------------------
                  const UID = result.insertId;
                  callback(null, UID);
                });
              },
              (UID, callback) => {
                let list_row ={
                  INPUT_DATA_UID: UID,
                  transactionTYPE: transactionTYPE,
                  transactionKBN: "1",// 画面と一緒にしている
                  CALL_STATUS: "4"
                };
                if (transactionTYPE === "A2") {
                  list_row.SEIZOBANGO = seizobango
                  if (ryokinplan === "AJ010") {
                    list_row.POOL_GROUP = process.env.pool_group_aj010
                  } else {
                    list_row.POOL_GROUP = process.env.pool_group
                  }
                  list_row.cardkeijo = cardkeijo
                  list_row.ryokinplan = ryokinplan
                  list_row.contracttype = contracttype
                  list_row.sousaservice = "0D0021"
                } else {
                  list_row.denwabango = denwabango
                }
                con.query("insert into INPUT_LIST set ?", list_row, (err, result) => {
                  if (err) {
                    //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}] Error Code is ${err.code}`); //---------------------------------------
                    error_message.view_error_messages_to_log({
                      error_code: "11043",
                      error_level: "ERROR",
                      message: "データベースの更新に失敗しました。: " + err.code + " insert into INPUT_LIST set ?" + "   " + util.inspect(list_row,false,null)
                    })
                    const response = JSON.stringify(ERROR_MESSAGE_500);
                    return callback(response);
                  }
                  //console.log(`[${LOG_ID}][${order_no}][${transactionTYPE}][INPUT_LIST]Inserted Count： ${JSON.stringify(result.affectedRows)}`); //---------------------------------------
                  callback(null);
                });
              }
            ],
            (err) => {
              if (err) {
                //データベースエラーのログは上で出力ずみ
                const response = JSON.stringify(ERROR_MESSAGE_500)
                return callback(response)
              }
              callback(null);
            });
          },
          (err) => {
            if (err) {
              // ループエラー
              const response = JSON.stringify(ERROR_MESSAGE_500)
              return callback(response)
            }
            con.commit((err) => {
              if (err) {
                error_message.view_error_messages_to_log({
                  error_code: "11011",
                  error_level: "ERROR",
                  message: "データベースの更新に失敗しました(commit error)。: " + err.code
                })
                //console.log(`[${LOG_ID}] Error Code is ${err.code}`); //---------------------------------------
                con.rollback((err) => {
                  //console.log(`[${LOG_ID}] Error Code is ${err.code}`); //---------------------------------------
                  error_message.view_error_messages_to_log({
                    error_code: "11011",
                    error_level: "ERROR",
                    message: "データベースの更新に失敗しました。(rollback error): " + err.code
                  })
                  const response = JSON.stringify(ERROR_MESSAGE_500)
                  return callback(response)
                });
              }
              //console.log("Update Succeed."); //---------------------------------------
              error_message.view_error_messages_to_log({
                error_code: "11041",
                error_level: "DEBUG",
                message: "データベースから切断します。"
              })
              con.destroy()
              //console.log("Disconnected to mysql."); //---------------------------------------
              callback(null)
            });
          });
        });
      });
    },
  ],
  (err) => {
    if (err) {
      error_message.view_error_messages_to_log({
        error_code: "11042",
        error_level: "ERROR",
        message: " データベース接続でエラーが発生しました。 : " + err.code
      })
      return callback(err)
    } else {
      error_message.view_error_messages_to_log({
        error_code: "11011",
        error_level: "DEBUG",
        message: "APIリクエスト処理を終了しました。"
      })
      const response = {
        message: "処理が正常に終了。",
        code: "00000"
      };
      callback(null, response)
    }
  });
};
