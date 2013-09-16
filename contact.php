<?php 

// Set email variables
$email_to = 'me@me.ca';
$email_subject = 'Contact Form';

// Set required fields
$required_fields = array('fullname','email','comment');

// set error messages
$error_messages = array(
	'fullname' => 'Please enter a name to proceed.',
	'email' => 'Please enter a valid email address to continue.',
);


//set non required fields
$non_required_fields = array(
		'Are you working with a REALTOR?' => 'realtor',
		'If so, who is your REALTOR?' => 'realtor_name',
		'Phone:' =>'phone',
		'Have you ever been a client of our developers before?' =>'developer', 
		'What are your current housing arrangements?'  =>'housing', 
		'Will you wish to list and sell your current home as part of a purchase with Artisan Park?' =>'sell', 
		'Do you have a particular model or floor plan in mind?' =>'model', 
		'What is your anticipated time frame?' =>'time_frame', 
		'Would you like our assistance in accessing funding for mortgage purposes?' =>'mortgage', 
		'May we continue to contact you for future phases?' =>'future', 
		'How would you like us to contact you?' =>'contact'),
		'Anything else?' => 'comment';

// Set form status
$form_complete = FALSE;

// configure validation array
$validation = array();

// check form submittal
if(!empty($_POST)) {
	// Sanitise POST array
	foreach($_POST as $key => $value) $_POST[$key] = remove_email_injection(trim($value));

	// Loop into required fields and make sure they match our needs
	foreach($required_fields as $field) {		
		// the field has been submitted?
		if(!array_key_exists($field, $_POST)) array_push($validation, $field);

		// check there is information in the field?
		if($_POST[$field] == '') array_push($validation, $field);

		// validate the email address supplied
		if($field == 'email') if(!validate_email_address($_POST[$field])) array_push($validation, $field);
	}
	
	
	// basic validation result
	if(count($validation) == 0) {
		// Prepare our content string
		$email_content = 'New Website Comment: ' . "\n\n";

		// simple email content
		foreach($_POST as $key => $value) {
			foreach ($non_required_fields as $non_req_key => $non_req_v) {
				if ($key == $non_req_v) {
					s = $value;
					unset($_POST[$key]);
				}
			}
		}	
	
		foreach($_POST as $key => $value) {
			if($key != 'submit') $email_content .= $key . ': ' . $value . "\n\n";
		}
	
		foreach ($non_required_fields as $non_req_key => $non_req_v) {
			if($non_req_key != 'submit') $email_content .= $non_req_key . " \n"  . $non_req_v . "\n\n";
		}
	
		
		// if validation passed ok then send the email
		mail($email_to, $email_subject, $email_content);

		// Update form switch
		$form_complete = TRUE;
	}
}

function validate_email_address($email = FALSE) {
	return (preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $email))? TRUE : FALSE;
}

function remove_email_injection($field = FALSE) {
   return (str_ireplace(array("\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:","to:","cc:"), '', $field));
}

?>



<html>

<head>
	<link rel="stylesheet" type="text/css" href="ssi/contact.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools-yui-compressed.js"></script> 
    <script type="text/javascript" src="ssi/validation.js"></script>
    
	<script type="text/javascript">
		var nameError = 'Please enter a Name to proceed.';
		var emailError = 'Please enter a valid Email Address to continue.'; 
		var commentError = 'Please enter your Message to continue.';

		function MM_preloadImages() { //v3.0
		  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
			}
    </script>

</head>
<body onload="MM_preloadImages('img/x.png')">


	<div id="contact">

		<div class="contactform">
    	<h2>Please contact us today!</h2>
  
    	<div id="form">
        <form method="post" action="contact.php" id="comments_form">
        <?php if($form_complete === FALSE): ?>
    		<div class="row">
            	<div class="label">Your name</div><!-- end .label -->
				<div class="input">
                	<input type="text" id="fullname" class="detail" name="fullname" value="<?php echo isset($_POST['fullname'])? $_POST['fullname'] : ''; ?>" /><?php if(in_array('fullname', $validation)): ?><span class="error"><?php echo $error_messages['fullname']; ?></span><?php endif; ?>
                    <div class="context">
                    	e. g. George Stromboulolpolous.
                    </div> <!--end .context-->
                </div><!--end .input-->
            </div><!-- .end row -->
            
            <div class="row">
            	<div class="label">Your Email</div><!-- end .label -->
				<div class="input">
                	<input type="text" id="email" class="detail" name="email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ''; ?>" /><?php if(in_array('email', $validation)): ?><span class="error"><?php echo $error_messages['email']; ?></span><?php endif; ?>
                    <div class="context">
                    	I will not show your email to anyone else or send spam.
                    </div> <!--end .context-->
                </div><!--end .input-->
            </div><!-- .end row -->
            
			<!-- relephant section -->
			
			
			<div class="row">
            	<div class="label">Your Phone Number</div><!-- end .label -->
				<div class="input">
						<input type="text" class="detail" name="phone" id="phone" value="<?php print $_POST['phone']; ?>" class="form_input" />
                </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<div class="row">
            	<label for="realtor">Are you working with a REALTOR&reg;?</label>
				<div class="input">
					
						<input name="realtor" id="realtor" type="radio" value="Yes" 
							<?php echo !empty($_POST) && $_POST['realtor'] == "Yes" ? "checked=\"\"" : ""; ?> /> Yes	
						<input name="realtor" id="realtor" type="radio" value="No" 
							<?php echo !empty($_POST) && $_POST['realtor'] == "No" ? "checked=\"\"" : ""; ?> /> No
					
			   </div><!--end .input-->
            </div><!-- .end row -->
           
			
			<div class="row">
            	<label for="realtor_name">If so, who is your REALTOR&reg;?:</label>
				<div class="input">
					
						<input type="text" class="detail" name="realtor_name" id="realtor_name" value="<?php print $_POST['realtor_name']; ?>" class="form_input">
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			<div class="row">
            	<label for="developer">Have you ever been a client of our developers before?</label>
				<div class="input">
					
						
						<input name="developer" id="developer" type="radio" value="Yes" 
							<?php echo !empty($_POST) && $_POST['developer'] == "Yes" ? "checked=\"\"" : ""; ?> /> Yes	
						<input name="developer" id="developer" type="radio" value="No" 
							<?php echo !empty($_POST) && $_POST['developer'] == "No" ? "checked=\"\"" : ""; ?> /> No
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<div class="row">
				<label for="housing">What are your current housing arrangements?</label>
				<div class="input">
					
						<input type="text" class="detail" name="housing" id="housing" value="<?php print $_POST['housing']; ?>" class="form_input" />
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<div class="row">
					<label for="sell">Will you wish to list and sell your current home as part of a purchase in Artisan Park?</label>				
					<div class="input">
					
						<input type="text" class="detail" name="sell" id="sell" value="<?php print $_POST['sell']; ?>" class="form_input" />
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<div class="row">
					<label for="model">Do you have a particular model or floor plan in mind?</label>				
				<div class="input">
					
						<input type="text" class="detail" name="model" id="model" value="<?php print $_POST['model']; ?>" class="form_input" />
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			<div class="row">
					<label for="time_frame">What is your anticipated time frame?</label>				
				<div class="input">
					
						<input type="text" class="detail" name="time_frame" id="time_frame" value="<?php print $_POST['time_frame']; ?>" class="form_input" />
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			<div class="row">
					<label for="mortgage">Would you like our assistance in accessing funding for mortgage purposes?</label>			
				<div class="input">
					
						<input type="text" class="detail" name="mortgage" id="mortgage" value="<?php print $_POST['mortgage']; ?>" class="form_input" />
					
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			<div class="row">
					<label for="future">May we continue to contact you for future phases?</label>		
				<div class="input">

						<input type="text" class="detail" name="future" id="future" value="<?php print $_POST['future']; ?>" class="form_input" />

			   </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<div class="row">
					<label for="contact">How would you like us to contact you?</label>
				<div class="input">
						<select class="detail" name="contact" size="1" id="contact">
						  <?php
							$options = array("Phone", "Post (please put your address below)", "Email");
							foreach ($options as $option) {
								$selected = "";
								if (!strcmp($_POST['contact'], $option)) {
									$selected = "selected=\"\"";
								}
						?>
						<option value="<?php echo $option; ?>" <?php echo $selected; ?> ><?php echo $option; ?></option>
						<?php } ?>
						</select>
			   </div><!--end .input-->
            </div><!-- .end row -->
			
			
			<!-- end of relephant section -->
			
            <div class="row">
            	<div class="label">Anything else?</div><!-- end .label -->
				<div class="input2">
                	<textarea id="comment" name="comment" class="message"><?php echo isset($_POST['comment'])? $_POST['comment'] : ''; ?></textarea><?php if(in_array('comment', $validation)): ?><span class="error"><?php echo $error_messages['comment']; ?></span><?php endif; ?>
                </div><!--end .input-->
            </div><!-- .end row -->
            <div class="submit">
            	<input type="submit" id="submit" name="submit" value="Send Message"/>
            </div><!-- end .submit -->
		
            </form>
			
			
			<!-- thank you message -->
			<?php else: ?>
				<h4 style="margin-left:40px; margin-top:40px">Thank you for your Message!</h4>
              	
              	<script type="text/javascript">
					setTimeout('ourRedirect()', 1000)
					
					function ourRedirect() {
						location.href='index.php'
					}
					
				</script>
			<?php endif; ?>
            
        </div> <!-- end #form -->
    </div>
 </div>

</body>

</html>
