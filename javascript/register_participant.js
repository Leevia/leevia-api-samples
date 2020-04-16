var CAMPAIGN_ID = 999 // TODO: replace 999 with your campaign id
var API_KEY = 'xxx'; // TODO: replace xxx with your api key
var API_SECRET = 'yyy'; // TODO: replace yyy with your api secret
var END_POINT = "https://app.leevia.com/api/v1/campaigns" + CAMPAIGN_ID + "/authenticate";

timestamp = + new Date();
message = KEY + "." + timestamp;
signature = CryptoJS.HmacSHA256(message, API_SECRET).toString(CryptoJS.enc.Hex);

var request = new XMLHttpRequest();
request.open("GET", END_POINT, false);
request.setRequestHeader('Accept', 'application/vnd.leevia.api.v1+json');
request.setRequestHeader('Content-Type', 'application/json');
request.setRequestHeader('App-Key', API_KEY);
request.setRequestHeader('Timestamp', timestamp);
request.setRequestHeader('Signature', signature);
request.send(null);

console.log(request.getResponseHeader("authorization"));
