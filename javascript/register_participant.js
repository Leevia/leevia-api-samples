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
  var authorization = res.headers['authorization'];
  console.log(`HEADERS: ${res.headers['authorization']}`);
  do_participant_request(authorization)
});

req.on('error', (e) => {
  console.error(`problem with request: ${e.message}`);
});

req.end();

function do_participant_request(authorization) {
  var participant_data = {
    first_name: 'Mario',
    last_name: 'Rossi',
    email: 'mario.rossi@example.com',
    registration_ip: '192.168.0.4',
    custom_data: {
      date_of_birth: '1989-10-03'
    },
    acceptances: {
      rules: true,
      newsletter: false
    }
  };

  var participant_data_string = JSON.stringify(participant_data)

  const options = {
  hostname: HOST,
  path: '/api/v1/campaigns/instant_wins/' + CAMPAIGN_ID + '/participants',
  method: 'POST',
  headers: {
    "Accept": 'application/vnd.leevia.api.v1+json',
    'Content-Type': 'application/json',
    'Content-Length': participant_data_string.length,
    "Authorization": authorization
    }
  };
  const req = https.request(options, (res) => {
    console.log(`STATUS: ${res.statusCode}`);
    res.on('data', (chunk) => {
      console.log(`BODY: ${chunk}`);
    });
  });
  req.on('error', (e) => {
    console.error(`problem with request: ${e.message}`);
  });

  req.write(participant_data_string);
  req.end();
}
