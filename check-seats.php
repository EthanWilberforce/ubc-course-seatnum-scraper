<?php
//Get Arrays of emails, urls, and course names from files
//$urls_read = fread(fopen("urls","r"), 34217728);
//$emails_read = fread(fopen("emails","r"), 34217728);
//$courses_read = fread(fopen("courses","r"), 34217728);
$urls = explode("\n", fread(fopen("urls","r"), 34217728));
$emails = explode("\n", fread(fopen("emails","r"), 34217728));
$courses = explode("\n", fread(fopen("courses","r"), 34217728));
unset($urls[sizeof($urls) - 1]);
unset($emails[sizeof($emails) - 1]);
unset($courses[sizeof($courses) - 1]);

$headers = "From: ubcsc@ethanw.ca" . "\r\n" ;
//Check through $urls array for classes with available spots
for ($x = 0; $x < sizeof($urls); $x++) {
  //get html contents
  $html = file_get_contents($urls[$x]);

  //get spots left
  $a = explode("Total Seats Remaining:</td><td align=left><strong>", $html);
  $b = explode("</strong>", $a[1])[0];
  $spots = intval($b);

  //Check if spots > 0
  if ($spots > 0){
    //send email saying space is available
    mail($emails[$x],
      $courses[$x] . " has seats available!",
      "Click the link to claim your seat.\n" . $urls[$x] . "\n\n\n" .
      "Do not reply to this email, this is an unatended mailbox.",
      $headers);

    //unset elements from array
    unset($urls[$x]);
    unset($emails[$x]);
    unset($courses[$x]);
    
    //Delete and create new emails, courses, and course names files
    unlink("urls");
    unlink("emails");
    unlink("courses");
    $newurls = fopen("urls","x+");
    $newemails = fopen("emails","x+");
    $newcourses = fopen("courses","x+");
    //Set file permissions
    chmod("urls", 0666);
    chmod("emails", 0666);
    chmod("courses", 0666);

    //Write unused data to files
    foreach ($urls as $url){
      if (!($urls[$x] == $url)){
        fwrite(fopen("urls","a+"), $url . "\n");
      }
    }
    foreach ($emails as $email){
      if (!($urls[$x] == $url)){
        fwrite(fopen("emails","a+"), $email . "\n");
      }
    }
    foreach ($courses as $course){
      if (!($urls[$x] == $url)){
        fwrite(fopen("courses","a+"), $course . "\n");
      }
    }
  }
}
?>
