<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blacklist Log</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="shortcut icon" href="assets/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">

    <?php
        // Config
        $GLOBALS["logsfolder"] = "logs";
        $GLOBALS["colorcode"] = [["Blacklist wurde aktiviert", "#319737"], ["Blacklist wurde deaktiviert", "#e83b3b"]];
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

        .action{
            padding-left: 10px;
        }

        #back{
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

    <?php

    function getURL($file) {
        if (!empty($file)){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $url = "https://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/")+1).$file;
            else
                $url = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/")+1).$file;
        }else{
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $url = "https://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], ".php")+4);
            else
                $url = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], ".php")+4);
        }

        return $url;
}

    ?>

    <h2>Blacklist Log</h2>

    <?php
        echo '<input id="back" class="btn" type="button" onclick="window.location.href=\''.getURL("blacklist.php").'\'" value="Zurück zur Blacklist">';
    ?>

    <table class="inset">
        <?php

        $readlog = fopen($GLOBALS["logsfolder"]."/latest.log", "r");
        if ($readlog) {
            $lines = explode("\n", fread($readlog, filesize($GLOBALS["logsfolder"]."/latest.log")));
        }
        fclose($readlog);

        // Sort by creation date
        $lines = array_reverse($lines);

        // Print Table-Header
        echo "<tr><th>Datum</th><th>Aktion</th></tr>";

        // Print each Log entry
        foreach($lines as $line){
            $val = explode(";", $line);
            if (!empty($val[0]) and !empty($val[1])){

                $colorfound = false;
                foreach($GLOBALS["colorcode"] as $rule){
                    if (strpos($val[1], $rule[0]) !== false) {
                        echo "<tr><td>[".$val[0]."]</td><td class='action' style='color:".$rule[1].";'>".$val[1]."</td></tr>";
                        $colorfound = true;
                    }
                }

                if (!$colorfound) {
                    echo "<tr><td>[".$val[0]."]</td><td class='action'>".$val[1]."</td></tr>";
                }
            }
        }
        ?>
    </table>


</body>
</html>
