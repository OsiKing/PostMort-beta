<?php
// include event class 
include_once 'class/Newsletter.php';
// create obj
$subscribe = new Newsletter();
// post method
$post = $_POST;
$get = $_GET;
// define array
$json = array();	

$site_url = 'https://techarise.com/demos/php/newsletter-email-subscription-with-php-mysql/';
$site_name = 'TechArise'; 
$site_email = 'info@techarise.com'; 

// create record in database
if(!empty($post['action']) && $post['action']=="create") {
  $name = $post['name'];
  $email = $post['email'];
  // generate verification code
  $verification_code = md5(uniqid(mt_rand())); 

  if(empty(trim($name))){
      $json['error']['name'] = 'Please enter first name';
  }

  if(empty(trim($email))){
      $json['error']['email'] = 'Please enter email address';
  }

  if (validateEmail($email) == FALSE) {
      $json['error']['email'] = 'Please enter valid email address';
  }
  $subscribe->setEmail($email);

  // exist Row
  $existRow = $subscribe->getRow();
  if($existRow > 0) { 
    $json['error']['email'] = 'Your email already exists in our subscribers list.'; 
  }

  if(empty($json['error'])){
  	$subscribe->setName($name);
    $subscribe->setEmail($email);
  	$subscribe->setVerifyCode($verification_code);
  	$subscribe->setIsVerified(0);
    $subscribe->setStatus(0);
  	$status = $subscribe->create();

    // Verification email configuration 
    $subscribe = urlencode(base64_encode($verification_code.'|subscribe'));
    $subscribeLink = $site_url.'action.php?subscribe='.$subscribe; 

    // unsubscribe link
    $unsubscribe = urlencode(base64_encode($verification_code.'|unsubscribe'));
    $unsubscribeLink = $site_url.'action.php?unsubscribe='.$unsubscribe; 

    $subject = 'Confirm Subscription'; 

    $bodyMsg = '<h1 style="font-size:20px;margin:20px 0 0;padding:0;text-align:left;color:#273E4A">Email Confirmation!</h1> 
    <p style="color:#616471;text-align:left;padding-top:15px;padding-right:40px;padding-bottom:30px;padding-left:40px;font-size:15px">Thank you for signing up with '.$site_name.'! Please confirm your email address by click the link below.</p> 
    <p style="text-align:center;"> 
        <a target="_blank" href="'.$subscribeLink.'" style="border-radius:5px;background-color:#273E4A;font-weight:bold;min-width:170px;font-size:14px;line-height:100%;padding-top:15px;padding-right:30px;padding-bottom:15px;padding-left:30px;color:#ffffff;text-decoration:none" rel="noopener noreferrer">Confirm Email</a> 
    </p> 
    <br><p><strong>'.$site_name.' Team</strong></p>
    <br><p><a style="color:#ccc; font-size:12px;" target="_blank" href="'.$unsubscribeLink.'" rel="noopener noreferrer">Unsubscribe</a></p>'; 
     
    $headers = "MIME-Version: 1.0" . "\r\n";  
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";  
    $headers .= "From: $site_name"." <".$site_email.">"; 
     
    // Send verification email 
    $mailStatus = mail($email, $subject, $bodyMsg, $headers); 
     
    if($mailStatus) { 
      $json['status'] = 'mail_sent';
    }

    $json['msg'] = 'success';
    $json['newsletter_id'] = $status;
  } else {
    $json['msg'] = 'failed';
    $json['newsletter_id'] = '';
  }
	header('Content-Type: application/json');
  echo json_encode($json);
}

// update record in database
if(!empty($get['subscribe'])) {
  $getURIData = explode('|', urldecode(base64_decode($get['subscribe'])));
  $verification_code = $getURIData[0];
	$subscribe->setVerifyCode($verification_code);
  $subscribe->setIsVerified(1);
  $subscribe->setStatus(1);
	$status = $subscribe->update();
	if(!empty($status)){
		$json['msg'] = 'Your email address has been verified successfully';
	} else {
		$json['msg'] = 'Some problem occurred on verifying your account, please try again';
	}

  include('templates/header.php'); ?>
  <section class="showcase">
     <div class="container">
      <div class="text-center">
        <h1 class="display-3">Thank You!</h1>
        <?php if(!empty($json['msg'])) { ?>
          <p class="lead"><?php print $json['msg']; ?></p>
        <?php } ?>
        <hr>
        <p>
          Having trouble? <a href="mailto:info@techarise.com">Contact us</a>
        </p>
        <p class="lead">
          <a class="btn btn-primary btn-sm" href="<?php print $site_url;?>" role="button">Continue to homepage</a>
        </p>
      </div>
      </div>
  </section>
  <br><br><br><br><br><br>
  <?php include('templates/footer.php');;
}

// delete record from database
if(!empty($get['unsubscribe'])) {
  $getURIData = explode('|', urldecode(base64_decode($get['unsubscribe'])));
  $verification_code = $getURIData[0];
	$subscribe->setVerifyCode($verification_code);
	$status = $subscribe->delete();
	if(!empty($status)){
		$json['msg'] = 'Newsletter unsubscribe successfully';
	} else {
		$json['msg'] = 'Some problem occurred on verifying your account, please try again';
	}
	
  include('templates/header.php'); ?>
  <section class="showcase">
     <div class="container">
      <div class="text-center">
        <h1 class="display-3">Unsubscribe</h1>
        <?php if(!empty($json['msg'])) { ?>
          <p class="lead"><?php print $json['msg']; ?></p>
        <?php } ?>
        <hr>
        <p>
          Having trouble? <a href="mailto:info@techarise.com">Contact us</a>
        </p>
        <p class="lead">
          <a class="btn btn-primary btn-sm" href="<?php print $site_url;?>" role="button">Continue to homepage</a>
        </p>
      </div>
      </div>
  </section>
  <br><br><br><br><br><br>
  <?php include('templates/footer.php');
}

// check email validation
function validateEmail($email) {
  return preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)?TRUE:FALSE;
}
?>