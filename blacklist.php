<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacklist Admin</title>

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

        /* table, tr, th, td {
            border: 1px solid gray;
        } */

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
            vertical-align: middle;
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
            /* width: 100%; */
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
            vertical-align: middle;
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
            /* width: 100%; */
        }

        .inset {
            padding-left: 2vw;
        }

        .flexcontainer{
            display: flex;
            flex-flow: row wrap;
        }

        #reloadbtn, #toggle{
            width: 100%
        }

        #addbltxt, #addbldesc, #addblbtn{
            margin-bottom: 5px;
        }

        #tableinput{
            width: 100%;
        }

        #addbltxt{
            width:20vw;
            min-width:200px;
        }

        #addbldesc{
            width:65vw;
            min-width:550px;
        }

        #addblbtn{
            min-width:100px;
        }

        #blactive {
            background-color: #319737;
        }

        #blinactive {
            background-color: #e83b3b;
        }

        #error {
            color: #e83b3b;
        }

        #itemcount {
            color: #939393;
            margin-left: 1.5vw;
        }

        #del {
            color: #e83b3b;
        }

        #numout {
            margin-top: 2vh;
            margin-left: 1.5vw;
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
        $error = newBLnum($_GET['newblnumber'], $_GET['newbldesc']);
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


    function newBLnum($number, $description) {

        // Remove Spaces first
        $number = preg_replace('/\s+/', '', $number);
        $description = trim($description);
        
        // Sets a description if none is present
        if(strlen($description) == 0){
            $description = "BL";
        }

        // Create a BL-Number File if the folder exists and the input is valid
        if(preg_match('/^\+*(\d|\s)+$/', $number)){
            if(is_dir($GLOBALS["foldername"])){
                $myfile = fopen($GLOBALS["foldername"]."/".$number, "w") or die("Unable to create BL-File \"$number\"!");
                fwrite($myfile, $description);
                fclose($myfile);
            }
        }else{
            $error = "[ERROR] Die Eingabe darf nur aus Zahlen bestehen und darf optional mit einem \"+\" beginnen";
        }

        return $error;
    }


    function delBLnum($number) {

        // Remove Spaces first
        $number = preg_replace('/\s+/', '', $number);

        // Remove BL Number from folder if it exists and the input is valid
        if(preg_match('/^\+*(\d|\s)+$/', $number)){
            if(file_exists($GLOBALS["foldername"]."/".$number)){
                $deletefile = unlink($GLOBALS["foldername"]."/".$number) or die("Unable to delete BL-File \"$number\"!");
            }else{
                $error = "[ERROR] Diese Nummer ist nicht in der Blacklist vorhanden";
            }
        }else{
            $error = "[ERROR] Die Eingabe darf nur aus Zahlen bestehen und darf optional mit einem \"+\" beginnen";
        }

        return $error;
    }

    function getURL() {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $url = "https://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], ".php")+4);
        else  
            $url = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], ".php")+4);   
        
        return $url;
    }

    ?>

    <h2>Innovaphone Blacklist</h2>

    <table>
        <tr>
            <td>
                <input id="reloadbtn" class="btn" type="button" onclick="reload()" value="Neu laden">
            </td>
            <td rowspan="2">
                <?php
                if( file_exists("DISABLED") ) {
                    echo("<p id=\"blinactive\" class=\"blstatus\">Blacklist inaktiv</p>");
                } else {
                    echo("<p id=\"blactive\" class=\"blstatus\">Blacklist aktiv</p>");
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <form method="get">
                    <input id="toggle" class="btn" type="submit" name="togglebl" class="button" value="Blacklist umschalten" />
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Zur Blacklist hinzufügen</h3>
                <form method="get">
                    <div id="container" class="parent">
                        <input id="addbltxt" class="txt" type="text" name="newblnumber" placeholder="Nummer"/>
                        <input id="addbldesc" class="txt" type="text" name="newbldesc" placeholder="Beschreibung"/>
                        <input id="addblbtn" class="btn" type="submit" name="newbl" value="Hinzufügen" />
                    </div>
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

                // Sort by creation date
                array_multisort(array_map('filectime', $blnums), SORT_NUMERIC, SORT_DESC, $blnums);
                
                // Print file count
                echo "<p id=\"itemcount\">".Count($blnums)." blockierte Nummern</p>";

                // Print Table-Header
                echo "<table id=\"numout\">";
                echo "<tr><th></th><th>Erstelldatum</th><th>Nummer</th><th>Beschreibung</th></tr>";


                // Print each Number and Description
                foreach($blnums as $num){
                    $contents = "";

                    if (file_exists($num)) {
                        $date = date("d.m.Y", filectime($num));
                    }
                    
                    // Read the File content
                    $f = fopen($num, 'r');
                    if ($f) {
                        $contents = fread($f, filesize($num));
                        fclose($f);
                    }
                    
                    // Check if file has content otherwise warn use
                    if(strlen(trim($contents)) != 0){
                        if(trim($contents) == "BL"){
                            $contents = "";
                        }
                        ?>
                        <tr>
                            <td>
                                <a title="Nummer löschen" onclick="return confirm('Nummer <?php echo substr($num, 8); ?> löschen?');" href="<?php echo getURL(); ?>?delblnumber=<?php echo substr($num, 8); ?>&delbl=Remove">
                                    <svg id="del" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                                </a>
                            </td>
                            <td class="inset">
                                <?php echo $date; ?>
                            </td>
                            <td class="inset">
                                <?php echo substr($num, 8); ?>
                            </td>
                            <td class="inset">
                                <?php echo $contents; ?>
                            </td>
                        </tr>
                        <?php
                    }else{
                        ?>
                        <tr id="error">
                            <td>
                                <a title="Nummer löschen" onclick="return confirm('Nummer <?php echo substr($num, 8); ?> löschen?');" href="<?php echo getURL(); ?>?delblnumber=<?php echo substr($num, 8); ?>&delbl=Remove">
                                    <svg id="del" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                                </a>
                            </td>
                            <td class="inset">
                                <?php echo $date; ?>
                            </td>
                            <td class="inset">
                                <?php echo substr($num, 8); ?>
                            </td>
                            <td class="inset">[Warnung] Diese Nummer wird nicht blockiert</td>
                        </tr>
                        <?php
                    }
                }

                echo "<table>";
                ?>
            </td>
        </tr>
    </table>


</body>
</html>
