<?php
  $campaign_id = 999; // Replace with your campaign id
  $api_key = 'xxx'; // replace with your api key
  $api_secret = 'yyy'; // replace with your api_secret
  $host_name = 'app.leevia.com';

  $today_date = new DateTime("now", new DateTimeZone('Europe/Rome'));
  $nowtime = time($today_date);
  $to_hash = $api_key.".".$nowtime;
  $signature = hash_hmac("sha256", $to_hash, $api_secret);
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_URL, "https://".$host_name."/api/v1/campaigns/".$campaign_id."/authenticate");
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
  else {
    $info = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    print_r('response http code: '.$info.'<br>');

    // Print header
    foreach (explode("\r\n", $content) as $hdr) {
      $header_field = explode(': ', $hdr);
      if ($header_field[0] == 'Authorization') {
        $token = $header_field[1];
      }
    }

    print_r($token.'<br>');
  }

  curl_close($ch);

  $participant = new \stdClass();
  $participant->first_name = "Mario";
  $participant->last_name = "Rossi";
  $participant->email = "mario.rossi@example.come";
  $participant->registration_ip = "192.168.0.4";

  $custom_data = new \stdClass();
  $custom_data->date_of_birth = '1989-10-03';
  $participant->custom_data = $custom_data;

  $acceptances = new \stdClass();
  $acceptances->rules = true;
  $acceptances->newsletter = false;
  $participant->acceptances = $acceptances;
  // uncomment the line below and replace YOUR_PATH with the file you want to upload
  //$participant->file_field = json_encode(file('YOUR_PATH'));

  $participant_json = json_encode($participant);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://".$host_name."/api/v1/campaigns/instant_wins/".$campaign_id."/participants");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept:application/vnd.leevia.api.v1+json',
    'Content-Type:application/json',
    "Authorization:".$token,
    "Content-Length:".strlen($participant_json)
  ));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $participant_json);

  $content = curl_exec($ch);

  if (curl_errno($ch)) {
    print_r('Error:'.curl_error($ch));
  }
  else {
    $info = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    print_r('response http code: '.$info.'<br>');
    print_r($content);
  }

  curl_close($ch);
