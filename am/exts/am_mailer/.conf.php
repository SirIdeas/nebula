<?php

return array(
  
  "files" => array(
    "php_mailer/PHPMailerAutoload",
    "AmMailer.class",
  ),

  "mergeFunctions" => array(
    "smtp" => "merge_r_if_are_array",
    "mails" => "array_merge_recursive",
  )

);