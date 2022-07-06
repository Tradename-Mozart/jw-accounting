<?php

  $email = 'test@test.comw3';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format";
}
   
echo $emailErr;

echo null ?: 'B';?>