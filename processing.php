<?php
    $givenUsername = $_POST['u_name'];
    $givenPassword = $_POST['pwd'];

    $file = fopen("password.txt", "r") or die("No such file or directory");
    $wholefile=fread($file, filesize("password.txt"));  // beolvasom a teljes txt állományt
    fclose($file);

    $decodedPasswords = decodePasswordFile($wholefile);

    $command = "SELECT username, titkos FROM `tabla` WHERE `username` like '$givenUsername';";
    $datas = connectToDatabase($command);

    //select eredményét eltárolom két változóban
    foreach ($datas as ["username" => $username, "titkos" => $color]) {
        $correctPassword = findPassword($username, $decodedPasswords);
        if ($correctPassword == $givenPassword){
            echo $color;
        }
        else {
            //echo $correctPassword;
            echo "<script type='text/javascript'>
                    alert('Invalid password!'); 
                    setTimeout(function(){
                        window.location.href='http://www.police.hu/';
                        }, 3000);
                </script>";
                echo "<p>Web page redirects after <span id='redirecttimer'>3 </span> seconds.</p>
                
                    <script type='text/javascript'>
                        var timeleft = 3;
                        var redirectTimer = setInterval(function(){
                            timeleft--;
                            document.getElementById('redirecttimer').textContent = timeleft;
                            if(timeleft <= 0)
                                clearInterval(redirectTimer);
                            },1000);
                    </script>";
                
            exit;

        }
    }

    function decodePasswordFile($pwdfile){

        $counter = 1;
        $decoded_pwd = "";

        for ($ch = 0; $ch <= strlen($pwdfile); $ch++) {
            $ascii = substr($pwdfile, $ch, 1); //a teljes fájl adott karaktere
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

        return $decoded_pwd;
    }

    function connectToDatabase($command){
        
        $conn = new mysqli("localhost", "root", "", "adatok");
		$result = mysqli_query($conn, $command);
        if (!$result) { 
            die("Query Failed."); 
        }

        $rows = array();
        if (mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result))
            {
                $rows[] = $row;
            }
        } else {
            echo "<script type='text/javascript'> 
                    alert('Invalid username! Please try again!'); 
                    location.href='login.php'; 
                </script>";
        }
        mysqli_close($conn);
        
        return $rows;
    }

    function findPassword($username,  $pwdfile){

        $pos = strpos($pwdfile, $username);
        $substrHelper = substr($pwdfile, $pos, strlen($pwdfile));
        $separatorIndex = strpos($substrHelper, '|');

        $userData = substr($substrHelper, 0, $separatorIndex);
        
        $startIndexOfPwd = strpos($userData, '*');
        $correctPassword = substr($userData, $startIndexOfPwd + 1, $separatorIndex);

        return $correctPassword;
    }
    
?>
