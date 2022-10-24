<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Blacklist Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">

    <?php
        // Config
        $GLOBALS["foldername"] = "numbers";
    ?>

    <style>
        body {
            background-color: #232323;
            color: #e2e2e2;
            font-family: 'Titillium Web', sans-serif;
            font-size: large;
        }

        input, h3 {
            margin-left: 3vw;
            color: #e2e2e2;
        }

        .blstatus {
            border-radius: 2px;
            border-style: none;
            box-sizing: border-box;
            color: #e2e2e2;
            display: inline-block;
            font-size: 25px;
            font-weight: 900;
            line-height: 3;
            margin: 0;
            max-width: none;
            min-height: 44px;
            min-width: 10px;
            outline: none;
            padding: 9px 20px 8px;
            position: relative;
            text-align: center;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            width: 100%;
            height: 100%;
        }

        .btn {
            background-color: #333333;
            border-radius: 2px;
            border-style: none;
            box-sizing: border-box;
            color: #e2e2e2;
            cursor: pointer;
            display: inline-block;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.5;
            margin: 0;
            max-width: none;
            min-height: 44px;
            min-width: 10px;
            outline: none;
            overflow: hidden;
            padding: 9px 20px 8px;
            position: relative;
            text-align: center;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            width: 100%;
        }

        .btn:hover,
        .btn:focus {
            opacity: .75;
        }

        .txt {
            background-color: #474747;
            border-radius: 2px;
            border-style: none;
            box-sizing: border-box;
            color: #e2e2e2;
            display: inline-block;
            font-size: 20px;
            font-weight: 500;
            line-height: 1.5;
            margin: 0;
            max-width: none;
            min-height: 44px;
            min-width: 10px;
            outline: none;
            overflow: hidden;
            padding: 5px 10px 4px;
            position: relative;
            text-align: center;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            width: 100%;
        }

        #blactive {
            background-color: #319737
        }

        #blinactive {
            background-color: #e83b3b
        }

        #error {
            color: #e83b3b;
        }

        #itemcount {
            color: #939393;
            margin-left: 1.5vw;
        }

        #numout {
            margin-top: 2vh;
            margin-left: 3vw;
        }

    </style>
</head>
<body>

    <script>
        function reload() {
            window.location.href = window.location.pathname;
        }
    </script>


    <?php
    if(array_key_exists('togglebl', $_GET)) {
        $error = toggleBL();
    }else if(array_key_exists('newbl', $_GET)) {
        $error = newBLnum($_GET['newblnumber']);
    }else if(array_key_exists('delbl', $_GET)) {
        $error = delBLnum($_GET['delblnumber']);
    }

    // Toggles the Blacklist
    function toggleBL() {
        if( file_exists("DISABLED") ) {
            // Delete File
            $deletefile = unlink("DISABLED") or die("Unable to delete DISABLED file!");
        } else{
            // Create File
            $myfile = fopen("DISABLED", "w") or die("Unable to create DISABLED file!");
            fwrite($myfile, "If this file exists the BL is disabled\n");
            fclose($myfile);
        }

        return $error;
    }


    function newBLnum($number) {

        //Remove Spaces first
        $number = preg_replace('/\s+/', '', $number);

        // Create a BL-Number File if the folder exists and the input is valid
        if(preg_match('/^\+*(\d|\s)+$/', $number)){
            if(is_dir($GLOBALS["foldername"])){
                $myfile = fopen($GLOBALS["foldername"]."/".$number, "w") or die("Unable to create BL-File \"$number\"!");
                fwrite($myfile, "BL\n");
                fclose($myfile);
            }
        }else{
            $error = "[ERROR] The input must only consist of numbers and can optionally start with a \"+\"";
        }

        return $error;
    }


    function delBLnum($number) {

        //Remove Spaces first
        $number = preg_replace('/\s+/', '', $number);

        // Remove BL Number from folder if it exists and the input is valid
        if(preg_match('/^\+*(\d|\s)+$/', $number)){
            if(file_exists($GLOBALS["foldername"]."/".$number)){
                $deletefile = unlink($GLOBALS["foldername"]."/".$number) or die("Unable to delete BL-File \"$number\"!");
            }else{
                $error = "[ERROR] This number is not Blacklisted";
            }
        }else{
            $error = "[ERROR] The input must only consist of numbers and can optionally start with a \"+\"";
        }

        return $error;
    }

    ?>



    <table>
        <tr>
            <td>
                <input id="reloadbtn" class="btn" type="button" onclick="reload()" value="Reload">
            </td>
            <td rowspan="2">
                <?php
                if( file_exists("DISABLED") ) {
                    echo("<p id=\"blinactive\" class=\"blstatus\">Blacklist inactive</p>");
                } else {
                    echo("<p id=\"blactive\" class=\"blstatus\">Blacklist active</p>");
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <form method="get">
                    <input id="toggle" class="btn" type="submit" name="togglebl" class="button" value="Toggle Blacklist" />
                </form>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Add to Blacklist</h3>
                <form method="get">
                    <table>
                        <tr>
                            <td>
                                <input id="addbltxt" class="txt" type="text" name="newblnumber"/>
                            </td>
                            <td>
                                <input id="addblbtn" class="btn" type="submit" name="newbl" value="Add" />
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
            <td>
                <h3>Remove from Blacklist</h3>
                <form method="get">
                    <table>
                        <tr>
                            <td>
                                <input id="delbltxt" class="txt" type="text" name="delblnumber"/>
                            </td>
                            <td>
                                <input id="delblbtn" class="btn" type="submit" name="delbl" value="Remove" />
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
        <?php
        if ($error){
            echo "<tr><td colspan=\"2\"><p id=\"error\">$error</p></td></tr>";
        }
        ?>
        <tr>
            <td colspan="2">
                <?php
                // Get all Blacklsited Numbers
                $blnums = glob($GLOBALS["foldername"]."/*");

                echo "<p id=\"itemcount\">".Count($blnums)." blacklisted numbers</p>";

                echo "<table id=\"numout\">";

                // Print each Numer
                foreach($blnums as $num){
                    echo "<tr><td>".substr($num, 8)."</td></tr>";
                }

                echo "<table>";
                ?>
            </td>
        </tr>
    </table>


</body>
</html>
