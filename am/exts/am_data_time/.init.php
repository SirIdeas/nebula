<?php


$timezone = Am::getAttribute("timezone");

if(!empty($timezone))
  date_default_timezone_set($timezone);
