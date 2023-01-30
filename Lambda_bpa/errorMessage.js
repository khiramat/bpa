// errorMessage.js
//const if_name = "BPA Receive API"

//const ERROR_MESSAGE_500 = {
//  status: 500,
//  message: "当社システムにてリカバリが必要なエラーが発生しています。当社側にて調査・リカバリを行います。",
//  errcode: "FXXXX"
//}

// 外部より指定できるエラーメッセージ
//context
//arrow
//if_name

// ログ仕様
exports.view_error_messages_to_log = (values) => {
  // 表示するエラー（インフォメーション）の値を取得する
  const context = typeof(values.context) !== "undefined" ? values.context : this.context
  const order_no = typeof(values.order_no) !== "undefined" ? values.order_no : this.order_no
  const tenant_id = typeof(values.tenant_id) !== "undefined" ? values.tenant_id : this.tenant_id
  const tenant_name = typeof(values.tenant_name) !== "undefined" ? values.tenant_name : ""
  const error_code = typeof(values.error_code) !== "undefined" ? values.error_code : "11999" // デフォルトは11999
  const error_level = typeof(values.error_level) !== "undefined" ? values.error_level : "ERROR" // デフォルトは"ERROR"
  const arrow = typeof(values.arrow) !== "undefined" ? values.arrow : this.arrow
  const if_name = typeof(values.if_name) !== "undefined" ? values.if_name : this.if_name
  const message = typeof(values.message) !== "undefined" ? values.message : this.message
  // AWSで環境変数が指定されているか判定し指定されていなければ"ERROR"を指定する
  const debug_mode = typeof(process.env.debug_mode) !== "undefined" ? process.env.debug_mode : "ERROR" // デフォルトは"ERROR"

  if ( context == "") {
    console.log("エラーメッセージ生成関数のパラメータ(context)が足りません")
    console.trace()
  }

  //　環境変数により指定しているエラー表示モードから動作を判定する
  let output_error_log_flg = false
  if (
    debug_mode === "ERROR"
    && error_level == "ERROR"
  ) {
    output_error_log_flg = true
  }
  if (
    debug_mode == "WRAN"
    && (
      error_level == "WRAN"
      || error_level == "ERROR"
    )
  ) {
    output_error_log_flg = true
  }
  if (
    debug_mode == "INFO"
    && (
      error_level == "INFO"
      || error_level == "WRAN"
      || error_level == "ERROR"
    )
  ) {
    output_error_log_flg = true
  }
  if (
    debug_mode == "DEBUG"
    && (
      error_level == "DEBUG"
      || error_level == "INFO"
      || error_level == "WRAN"
      || error_level == "ERROR"
    )
  ) {
    output_error_log_flg = true
  }
  if (!output_error_log_flg) {
    // エラーレベルが合わないためエラーログ表示を中止
    return
  }

  // エラーの表示
  let log_message = ""

  const dt = new Date()
  const year = ("0000" + dt.getFullYear()).slice(-4)
  const month = ("00" + (dt.getMonth() + 1)).slice(-2)
  const day = ("00" + dt.getDate()).slice(-2)
  const hour = ("00" + dt.getHours()).slice(-2)
  const minute = ("00" + dt.getMinutes()).slice(-2)
  const millisecond = ("000" + dt.getMilliseconds()).slice(-3)

  log_message += error_level + " " // ログレベル
  log_message += year + "/" + month + "/" + day + " " // 日付
  log_message += hour + ":" + minute + "." + millisecond + " " // 時刻(JST)
  log_message += context.awsRequestId + " " // AWSリクエストID
  log_message += error_code + " " // エラーコード
  log_message += if_name + " " // I/F名
  log_message += tenant_name + " " // 事業者名
  log_message += order_no + " " // オーダ番号
  log_message += tenant_id + " " // トランザクションID
  log_message += arrow + " " // 通信方向
  log_message += message // 詳細情報

  if (error_level === "ERROR") {
    console.error(log_message)
  } else if (error_level === "WRAN") {
    console.warn(log_message)
  } else if (error_level === "INFO") {
    console.info(log_message)
  } else if (error_level === "DEBUG") {
    console.debug(log_message)
  } else {
    console.log(log_message)
  }
  if (error_level === "DEBUG") {
    console.trace()
  }
  return
}
