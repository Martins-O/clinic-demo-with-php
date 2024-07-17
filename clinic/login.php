<?php
ob_start();
session_start();
include("db_connect.php");

if (isset($_POST['login_button'])) {
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['password']);

    $usql = "SELECT * FROM tbl_users WHERE Email='$user_email' AND Password='$user_password'";
    $uresult = mysqli_query($db, $usql) or die("database error:" . mysqli_error($db));
    if (mysqli_num_rows($uresult) <= 0) {
        echo "Incorrect Email or Password";
        exit;
    }
    $urow = mysqli_fetch_assoc($uresult);

    $userid = $urow['id'];

    if ($urow['Password'] == $user_password) {
        setcookie("userid", $user_password, time() + (60 * 60 * 24 * 7));
        setcookie("useremail", $user_email, time() + (60 * 60 * 24 * 7));

        $ldate = date('d-m-Y h:i:s A', time());

        $alphabet = '1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 7; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $sname = implode($pass);
        $fa = substr($sname, 0, 1);
        $fb = substr($sname, 1, 1);
        $fc = substr($sname, 2, 1);
        $fd = substr($sname, 3, 1);
        $fe = substr($sname, 4, 1);
        $ff = substr($sname, 5, 1);

        $alphabet2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass2 = array();
        $alphaLength2 = strlen($alphabet2) - 1;
        for ($i2 = 0; $i2 < 7; $i2++) {
            $n2 = rand(0, $alphaLength2);
            $pass2[] = $alphabet2[$n2];
        }
        $sname2 = implode($pass2);
        $fa2 = substr($sname2, 0, 1);
        $fb2 = substr($sname2, 1, 1);
        $fc2 = substr($sname2, 2, 1);
        $fd2 = substr($sname2, 3, 1);
        $fe2 = substr($sname2, 4, 1);
        $ff2 = substr($sname2, 5, 1);

        $mac = $fa2 . $fa . '-' . $fb2 . $fb . '-' . $fc2 . $fc . '-' . $fd2 . $fd . '-' . $fe2 . $fe . '-' . $ff2 . $ff;

        // Include the Activities field
        $activities = 'Login'; // or set to an appropriate default value
        $enter = "INSERT INTO tbl_userlogs (Login, Machineip, Userid, Count, Logout, Activities) 
                  VALUES ('$ldate', '$mac', '$userid', '0', 'default_logout_value', '$activities')";
        $db->query($enter) or die('Error, query failed to upload');

        $time = time();

        $queryz = "UPDATE tbl_users SET Online='Online', Time='$time' WHERE Password='$user_password'";
        $db->query($queryz) or die('Error, query failed to upload');

        setcookie("login", $ldate, time() + (60 * 60 * 24 * 7));
        setcookie("user", $userid, time() + (60 * 60 * 24 * 7));

        echo "ok";
    } else {
        echo "email or password does not exist.";
    }
}
?>
