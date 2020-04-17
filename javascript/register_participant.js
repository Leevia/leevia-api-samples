var CryptoJS = require("crypto-js");
const https = require('https');
var fs = require('fs');

var CAMPAIGN_ID = 999 // TODO: replace 999 with your campaign id
var API_KEY = 'xxx'; // TODO: replace xxx with your api key
var API_SECRET = 'yyy'; // TODO: replace yyy with your api secret
var HOST = "app.leevia.com";

timestamp = new Date();
message = API_KEY + "." + timestamp;
signature = CryptoJS.HmacSHA256(message, API_SECRET).toString(CryptoJS.enc.Hex);

const options = {
  hostname: HOST,
  path: '/api/v1/campaigns/' + CAMPAIGN_ID + '/authenticate',
  method: 'GET',
  headers: {
    'Accept': 'application/vnd.leevia.api.v1+json',
    'Content-Type': 'application/json',
    'App-Key': API_KEY,
    'Timestamp': timestamp,
    'Signature': signature
  }
};

const req = https.request(options, (res) => {
  console.log(`STATUS: ${res.statusCode}`);
  console.log(`HEADERS: ${res.headers['authorization']}`);
});

req.on('error', (e) => {
  console.error(`problem with request: ${e.message}`);
});

req.end();
