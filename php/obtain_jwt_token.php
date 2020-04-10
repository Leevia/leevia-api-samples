<?php
  $api_key = 'your_api_key';
  $api_secret = 'your_api_secret';
  $today_date = new DateTime("now", new DateTimeZone('Europe/Rome'));
  $nowtime = time($today_date->date);
  $to_hash = $api_key.".".$nowtime;
  $signature = hash_hmac("sha256", $to_hash, $api_secret);
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://app.leevia.com/api/v1/campaigns/YOUR_CAMPAIGN_ID/authenticate");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept:application/vnd.leevia.api.v1+json',
    'App-Key:'.$api_key,
    'Content-Type:application/json',
    'Signature:'.$signature,
    'Timestamp:'.$nowtime
  ));

  $content = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }
  else{
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, true);
    $info = curl_getinfo($ch);
    print_r($info);
  }

  curl_close($ch);
