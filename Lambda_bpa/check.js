const mysql = require("mysql");
const error_message = require("./errorMessage.js");
const con = mysql.createConnection({
  host: process.env.db_host,
  user: process.env.db_user,
  password: process.env.db_pwd,
  database: process.env.db_name
});
const ERROR_MESSAGE_500 = {
  status: 500,
  message: "当社システムにてリカバリが必要なエラーが発生しています。当社側にて調査・リカバリを行います。",
  errcode: "FXXXX"
};
let order_no = ""
let tenant_id = ""
let tenant_name = ""

const TRANSACTIONYPE_ARRAY = ["A2", "M02", "21", "49"];
//const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1", "AJ010": "4", "AJ034": "5", "AJ055": "6"};
//const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1"};
const RYOKINPLAN_CONTRACTTYPE_MAP = {"A1089": "1", "AJ010": "4"}; //jw_kato 20190826
const CARDKEIJO_ARRAY = ["0", "1", "3"];

exports.check_tenant_id = (tenant_id) => {
  const tenant_select_query = `select TENANT_ID, TENANT_NAME, ACCESS_GROUP from TENANT where TENANT_ID = "${tenant_id}" and  DELETE_FLAG = "0"`;
  con.query(tenant_select_query, (err, results) =>{
    if (err) {
      error_message.view_error_messages_to_log({
        order_no: this.order_no,
        tenant_id: this.tenant_id,
        tenant_name: this.tenant_name,
        error_code: "11022",
        error_level: "ERROR",
        message: "バリデーションエラー。テナント情報の取得でSQLのエラーが発生しました。 : " + err.code + " " + tenant_select_query
      })
      //console.log(`Error Code is ${err.code}`);
      const response = ERROR_MESSAGE_500;
      return [true, JSON.stringify(response)];
    }
    if (typeof results === "undefined" || results === null) {
      error_message.view_error_messages_to_log({
        order_no: this.order_no,
        tenant_id: this.tenant_id,
        tenant_name: this.tenant_name,
        error_code: "11023",
        error_level: "ERROR",
        message: "バリデーションエラー。テナント情報がありませんでした。送信されたテナントIDの店舗情報は登録されていません。 : " + results
      })
      const response = {
        status: 422,
        message: "受付できない事業者からの要求です、もしくは事業者コード（トランザクションIDの上2桁）と販売店コードの組み合わせが不正です。",
        errcode: "I0006"
      };
      return [true, JSON.stringify(response)];
    } else {
      // tenant_idのチェックは正常に終了しました。
      return [false, null];
    }
  });
};

exports.check_order_no = (order_no) => {
  if (typeof order_no === "undefined" || order_no === "") {
    error_message.view_error_messages_to_log({
      order_no: order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11063",
      error_level: "ERROR",
      message: "バリデーションエラー。order_noが指定されていません。 : "
    })
    const response = {
      status: 422,
      message: "想定外のエラーが発生。",
      errcode: "E9000"
    };
    return [true, JSON.stringify(response)];
  } else {
    return [false, null];
  }
};

exports.check_transactionTYPE = (transactionTYPE) => {
  if (typeof transactionTYPE === "undefined" || transactionTYPE === "") {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11064",
      error_level: "ERROR",
      message: "バリデーションエラー。トランザクションTYPEが指定されていません。: " + transactionTYPE
    })
    const response = {
      status: 422,
      message: "トランザクションTYPEの設定値が不正です。",
      errcode: "I0021"
    };
    return [true, JSON.stringify(response)];
  } else if (TRANSACTIONYPE_ARRAY.indexOf(transactionTYPE) === -1) {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11065",
      error_level: "ERROR",
      message: "バリデーションエラー。登録されていないトランザクションTYPEが指定されました。: " + transactionTYPE
    })
    const response = {
      status: 422,
      message: "トランザクションTYPEの設定値が不正です。",
      errcode: "I0021"
    };
    return [true, JSON.stringify(response)];
  } else {
    return [false, null];
  }
};

exports.check_ryokinplan = (ryokinplan) => {
  if (typeof ryokinplan === "undefined" || ryokinplan === "") {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11066",
      error_level: "ERROR",
      message: "バリデーションエラー。料金プランが指定されていません。 : " + ryokinplan
    })
    const response = {
      status: 422,
      message: "料金プランの設定値が不正です。",
      errcode: "I0042"
    };
    return [true, JSON.stringify(response)];
  } else if (Object.keys(RYOKINPLAN_CONTRACTTYPE_MAP).indexOf(ryokinplan) === -1) {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11067",
      error_level: "ERROR",
      message: "バリデーションエラー。登録されていない料金プランが指定されました。 : " + ryokinplan
    })
    const response = {
      status: 422,
      message: "料金プランの設定値が不正です。",
      errcode: "I0042"
    };
    return [true, JSON.stringify(response)];
  } else {
    return [false, null];
  }
};

exports.get_contracttype = (ryokinplan) => {
  const contracttype = RYOKINPLAN_CONTRACTTYPE_MAP[ryokinplan];
  return contracttype;
};

exports.check_cardkeijo = (cardkeijo) => {
  if (typeof cardkeijo === "undefined" || cardkeijo === "") {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11068",
      error_level: "ERROR",
      message: "バリデーションエラー。カ一ド形状が指定されていません。 : " + cardkeijo
    })
    const response = {
      status: 422,
      message: "カ一ド形状が未設定です。",
      errcode: "I0029"
    };
    return [true, JSON.stringify(response)];
  } else if (CARDKEIJO_ARRAY.indexOf(cardkeijo) === -1) {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11069",
      error_level: "ERROR",
      message: "バリデーションエラー。カ一ド形状が 0：通常、1：mini、3：nano 以外となっています。 : " + cardkeijo
    })
    const response = {
      status: 422,
      message: "カ一ド形状が 0：通常、1：mini、3：nano 以外となっています。",
      errcode: "I0030"
    };
    return [true, JSON.stringify(response)];
  } else {
    return [false, null];
  }
};

exports.check_denwabango = (denwabango) => {
  if (typeof denwabango === "undefined" || denwabango === "") {
    error_message.view_error_messages_to_log({
      order_no: this.order_no,
      tenant_id: this.tenant_id,
      tenant_name: this.tenant_name,
      error_code: "11070",
      error_level: "ERROR",
      message: "バリデーションエラー。電話番号が指定されていません。 : " + denwabango
    })
    const response = {
      status: 422,
      message: "電話番号が未設定です。",
      errcode: "I0024"
    };
    return [true, JSON.stringify(response)];
  } else {
    const reg = /^0[2|6|7|8|9]0\d{4}\d{4,}$/;
    if (!reg.test(denwabango)) {
      error_message.view_error_messages_to_log({
        order_no: this.order_no,
        tenant_id: this.tenant_id,
        tenant_name: this.tenant_name,
        error_code: "11071",
        error_level: "ERROR",
        message: "バリデーションエラー。電話番号の先頭は020、060、070、080、090で指定してください。: " + denwabango
      })
      const response = {
        status: 422,
        message: "電話番号の体系が不正です。",
        errcode: "I0025"
      };
      return [true, JSON.stringify(response)];
    } else {
      return [false, null];
    }
  }
};
