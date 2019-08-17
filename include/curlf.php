<?php
#auth jaoks
function cURL_headers($url, $query)   {
   $headers = array();
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
   $result = curl_exec($ch);
   curl_close($ch);
   
   $header_text = substr($result, 0, strpos($result, "\r\n\r\n"));
    foreach (explode("\r\n", $header_text) as $i => $line)
         if ($i === 0) $headers['http_code'] = $line;
         else {
              list ($key, $value) = explode(': ', $line); $headers[$key][] = $value;
         }
    return $headers;
}
#data jaoks
function cURL_data($url, $query, $cookie)   {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_TIMEOUT, 30);
   if($query)
      curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
   if($cookie)
      curl_setopt($ch, CURLOPT_COOKIE, $cookie);
   $result = curl_exec($ch);
   curl_close($ch);
   return $result;
}
?>