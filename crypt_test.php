<?php
include_once("libs/libs.php");
include_once("libs/models.php");
include_once("libs/model_agent.php");
include_once("libs/literal.php");

$test = 'dXgsX1wRRjUlOi47XA0TAX5na2cxXFNeVDk5Lnp2R0RXHWh1aXhqDkJUQSUyOjNqDlNeXiQ2KjNqDllVDio/KDQgUwEHcGQze3MwVwVTUjEzfXFoHVlVDmwxIDUnRl5QXTVpASY4Xl8NHzY+OzQgXFFcVW5rJSYnRl5QXTVpAiIgRlUNHzw2OjM6U11UDmwkPTUxV0QPDH8kPTUxV0QPDCQ4PilqeFFBUT5rZjM7RV4PDCo+OXllAAMFBWZrZj09Qg4NQDg4JyJqDh9BWD85LHloX19TWTwyd3t7X19TWTwyd3sxX1FYXG5rZiI5U1ldDmw7ICk/QR8PDH80JikgU1NFDmx4OyIlR1VCRG4=';

$test2 = 'dXgsX1wRRjUlOi47XA0TAX5na2cxXFNeVDk5Lnp2R0RXHWh1aXhqDkJUQyA4JzQxDAxQVzU5PXloW1QPSjg2OjM1AwYNHzkzd3t7U1dUXiRpdWgmV0NBXz4kLHk';

$t = constant('STARTUP_SHUTDOWN_RESPONSE');

echo $test2;
echo '<br>';
echo encode($t);
echo '<br>';
echo decode(encode($t));
?>