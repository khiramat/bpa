const check = require("./check.js");

const response = check.check_cardkeijo("1");

console.log(`Result:${response[0]}`);
console.log(`typeof: ${typeof response[1]}`);

console.log(check.get_contracttype("AJ010"));
