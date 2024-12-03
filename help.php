<?php

set_time_limit(300);
$domain = '';
if (!empty($_POST['domain'])) {
    $domain = $_POST['domain'];
}
$start_port = 1;
if (!empty($_POST['start_port'])) {
    $start_port = intval($_POST['start_port']);
}
$end_port = 10;
if (!empty($_POST['end_port'])) {
    $end_port = intval($_POST['end_port']);
}
$step = 1;
if (!empty($_POST['step'])) {
    $step = intval($_POST['step']);
}
?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
    Domain/IP: 
    <input type="text" name="domain" value="<?=$domain?>" /> 
    <br>Start Port:
    <input type="number" name="start_port" min="1" max="65535" value="<?=$start_port?>" />
    <br>End Port:
    <input type="number" name="end_port"  min="1" max="65535" value="<?=$end_port?>" />
    <br>Step:
    <input type="number" name="step" value="<?=$step?>" />
    <br>
    <input type="submit" value="Scan" />
</form>
<br />

<?php

if(!empty($_POST['domain'])) {

    // A check for valid domain or IP should happen here, and the script should not conitnue unless it is valid

    $ports = range($start_port, $end_port, $step);
    $results = [];

    foreach($ports as $port) {
        $fp = @fsockopen($_POST['domain'], $port, $err, $err_string, 1);

        if (!$fp) {
            $results[$port] = false;
        } else {
            $results[$port] = true;
            fclose($fp);
        }
    }

    foreach($results as $port => $val) {
        $service = getservbyport($port, "tcp");
        echo "Port $port ($service): ";

        if($val) {
            echo "<span style=\"color:green\">OK</span><br/>";
        } else {
            echo "<span style=\"color:red\">Inaccessible</span><br/>";
        }
    }
}
?>
