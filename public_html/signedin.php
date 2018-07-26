<?php
    include_once "bootstrap.php";
    include_once RESOURCE_PATH . "/user-session.php";
    include_once RESOURCE_PATH . '/database.php';
 ?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/base.css?v21"/>
    <title>Homepage</title>
</head>
<body>

<?php
  if (!isset($_SESSION['signed-in']) && isset($_COOKIE['PHPSESSID'])) {
      $cond = "true";
      $_SESSION['signed-in'] = "1";
  } else
      $cond = "false";

echo '<script> 
    if (' . $cond . ' ) {
        parent.location.reload();
    }
</script>';


if (null !== getLastUserlId()) {
    $firstName = getSignedInFirstMame();
    echo "<p style='display:block;width:auto;position:fixed;left:0;right:0;text-align:center;top:40%;'>
                Welcome back {$firstName}! 
                </p>
              <ul class='bar-nav' style='list-style: none;margin:0;padding:0;display:block;width:auto;position:fixed;left:0;right:0;text-align:center;top:50%'>
                <li class='header-btn' ><a href='profile-edit.php'>Edit Profile</a></li>
                <li class='header-btn' ><a href='signout.php'>Sign out</a></li>
              </ul>";
} else {
    echo "<p>Sorry, you are currently not logged in. Please <a href='signin.php'>Sign in</a>";
    echo "<p> Not a member ? <a href='signup.php'>signup</a></p>";
}
?>

</body>
</html>