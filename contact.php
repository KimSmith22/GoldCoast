<?php

// configure
$from = 'Demo contact form <clubgcdetroit@gmail.com>';
$sendTo = 'Demo contact form <clubgcdetroit@gmail.com>';
$subject = 'New message from Accident Angels contact form';
$fields = array('name' => 'Name', 'surname' => 'Surname', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message'); // array variable name => Text to appear in email
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please try again later';

// let's do the sending

try
{
  $emailText = "You have new message from contact form\n=============================\n";

  foreach ($_POST as $key => $value) {

      if (isset($fields[$key])) {
          $emailText .= "$fields[$key]: $value\n";
      }
  }

  mail($sendTo, $subject, $emailText, "From: " . $from);

  $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
  $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  $encoded = json_encode($responseArray);

  header('Content-Type: application/json');

  echo $encoded;
}
else {
  echo $responseArray['message'];
}

$(function () {

  $('#contact-form').validator();

  $('#contact-form').on('submit', function (e) {
      if (!e.isDefaultPrevented()) {
          var url = "contact.php";

          $.ajax({
              type: "POST",
              url: url,
              data: $(this).serialize(),
              success: function (data)
              {
                  var messageAlert = 'alert-' + data.type;
                  var messageText = data.message;

                  var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                  if (messageAlert && messageText) {
                      $('#contact-form').find('.messages').html(alertBox);
                      $('#contact-form')[0].reset();
                  }
              }
          });
          return false;
      }
  })
});

?>
