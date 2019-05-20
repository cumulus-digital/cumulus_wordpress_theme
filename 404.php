<?php
header("HTTP/1.1 302 Moved Temporarily");
header("Location: ".get_bloginfo('url'));
exit();
?>