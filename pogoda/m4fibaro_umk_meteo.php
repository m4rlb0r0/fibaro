<?php

$fg_admin = "admin%40gmail.com"; // zamieniamy @ na %40
$fg_pass = "haslo!F";

$url = "http://www.home.umk.pl/~vaisala/";

$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {

    $html = explode("<table class=\"glowna\">",$html);
    $html = "<table class=\"glowna\">" . $html[1];
    $html = explode("<tr>",$html);
    $html2 = array();   
    foreach  ($html as $tr) {
        $html2[] = explode("<td>",$tr);
    }
   
    /* [1] Temperatura powietrza (200 cm) °C */
    $temperatura200 = $html2[1][0];
    $temperatura200 = explode("\n",$temperatura200);
    echo $temperatura200[1] . ": " .  $temperatura200[2] . $temperatura200[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("temperatura200",$temperatura200[2],$fg_admin,$fg_pass);
    echo "<hr>";

    /* [2] Wilgotność względna powietrza	% */
    $wilgotnosc = $html2[2][0];
    $wilgotnosc = explode("\n",$wilgotnosc);
    echo $wilgotnosc[1] . ": " .  $wilgotnosc[2] . $wilgotnosc[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("wilgotnosc",$wilgotnosc[2],$fg_admin,$fg_pass);
    echo "<hr>";
   
    /* [3] Ciśnienie atmosferyczne rzeczywiste hPa */  
    $cisnienie = $html2[3][0];
    $cisnienie = explode("\n",$cisnienie);
    echo $cisnienie[1] . ": " .  $cisnienie[2] . $cisnienie[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("cisnienie",$cisnienie[2],$fg_admin,$fg_pass);
    echo "<hr>";

    /* [4] Ciśnienie atmosferyczne na poziomie morza hPa */
    $cisnienieM = $html2[4][0];
    $cisnienieM = explode("\n",$cisnienieM);
    echo $cisnienieM[1] . ": " .  $cisnienieM[2] . $cisnienieM[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("cisnienieM",$cisnienieM[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [5] Opad atmosferyczny za ostatnią godzinę mm */
    $opad1 = $html2[5][0];
    $opad1 = explode("\n",$opad1);
    echo $opad1[1] . ": " .  $opad1[2] . $opad1[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("opad1",$opad1[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [6] Opad atmosferyczny za ostatnie 24 h mm */
    $opad24 = $html2[6][0];
    $opad24 = explode("\n",$opad24);
    echo $opad24[1] . ": " .  $opad24[2] . $opad24[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("opad24",$opad24[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [7] Temperatura powietrza (5 cm)	°C */
    $temperatura5 = $html2[7][0];
    $temperatura5 = explode("\n",$temperatura5);
    echo $temperatura5[1] . ": " .  $temperatura5[2] . $temperatura5[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("temperatura5",$temperatura5[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [8] Temperatura gruntu (0 cm) °C */
    $temperatura0 = $html2[8][0];
    $temperatura0 = explode("\n",$temperatura0);
    echo $temperatura0[1] . ": " .  $temperatura0[2] . $temperatura0[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("temperatura0",$temperatura0[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [16] Prędkość wiatru	m/s */
    $wiatrS = $html2[17][0];
    $wiatrS = explode("\n",$wiatrS);
    echo $wiatrS[1] . ": " .  $wiatrS[2] . $wiatrS[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/ 
    updateGlobalVariables("wiatrS",$wiatrS[2],$fg_admin,$fg_pass);
    echo "<hr>";
     
    /* [17] Kierunek wiatru	deg */    
    $wiatrK = $html2[18][0];
    $wiatrK = explode("\n",$wiatrK);
    echo $wiatrK[1] . ": " .  $wiatrK[2] . $wiatrK[3]; /* [1] - nazwa [2] - wartosc [3] - jednostka*/
    updateGlobalVariables("wiatrK",$wiatrK[2],$fg_admin,$fg_pass); 
    echo "<hr>";     

} else echo "błąd połączenia - nie znaleziono strony lub hc2";

function updateGlobalVariables($zmienna, $wartosc, $admin, $pass) {

    $fg_HC2_IP = "192.168.1.100";

    $url = "http://$admin:$pass@$fg_HC2_IP:80/api/globalVariables/$zmienna";
    $ch = curl_init();
    $data = array('value' => str_replace(" ","",strip_tags($wartosc)));
    $data_json = json_encode($data);
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    $html = curl_exec($ch);
    curl_close($ch);
}

?>