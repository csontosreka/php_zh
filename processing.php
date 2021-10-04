<?php
    $file = fopen("password.txt", "r") or die("No such file or directory");
    $wholefile=fread($file, filesize("password.txt"));  // beolvasom a teljes txt állományt
    //echo "$wholefile";
    fclose($file);

    $counter = 1;
    $decoded_pwd = "";

    for ($ch = 0; $ch <= filesize("password.txt"); $ch++) {
        $ascii = substr($wholefile, $ch, 1); //a teljes fájl adott karaktere
        $decimal = ord($ascii); //ascii karakter átváltása decimálisra
        //echo $ch . ": " . $decimal . "<br>";

        if ($decimal == 10){
            $decoded_pwd .= "|"; //ha elválasztó karaktert találok ezt a karaktert fűzöm a dekódolt stringhez
            $counter = 1;
        } else {
            switch ($counter) {
                case 1:
                    $counter++;
                    $decimal = $decimal - 5;
                    break;
                case 2:
                    $counter++;
                    $decimal = $decimal + 14;
                    break;
                case 3:
                    $counter++;
                    $decimal = $decimal - 31;
                    break;
                case 4:
                    $counter++;
                    $decimal = $decimal + 9;
                    break;
                case 5:
                    $counter = 1;
                    $decimal = $decimal - 3;
                    break;
            }
        }

        $decoded_ascii = chr($decimal); // visszaváltás decimálisról ascii-re
        $decoded_pwd .= $decoded_ascii; // hozzáfűzés a dekódolt szöveget tartalmazó stringhez
      }

      echo $decoded_pwd;
?>