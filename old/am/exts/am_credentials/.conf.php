<?php

return array(
  
  "files" => array(
    "AmCredentials.class",
    "AmCredentialsHandler.class"
  ),

  "requires" => array(
    "core/am_session/",
  ),

  "mergeFunctions" => array(
    "credentials" => "merge_r_if_are_array_and_snd_first_not_false",
  )

);