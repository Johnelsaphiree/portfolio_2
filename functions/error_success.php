<?php
function error($code)
{
switch($code)
{
case 1:
$msg = "Fields marked with * are rquired!";
break;
case 2:
$msg = "Cant leave field empty!";
break;
case 3:
$msg = "Duplicate entry!";
break;
case 4:
$msg = "No Record!";
break;
case 5:
$msg = "Wrong info!";
break;
case 6:
$msg = "Server error!, try again later";
break;
case 7:
$msg = "Size must be less than 2MB and type must be JPEG!";
break;
case 8:
$msg = "Fields must be numeric!";
break;
case 9:
$msg = "Password did not Match!";
break;
case 10:
$msg = "Wrong Captcha code";
break;
case 11:
$msg = "Username already exist!";
break;
case 12:
$msg = "Email is not valid!";
break;
case 13:
$msg = "Wrong Username/Password!";
break;
case 14:
$msg = "Username does not exist!";
break;
case 15:
$msg = "Invalid GSM number!";
break;
case 16:
$msg = "Insufficient Credit!";
break;
case 17:
$msg = "Max recipient limit exceeded!(100,000 max recipients per message)";
break;
case 18:
$msg = "Select one or more items!";
break;
case 19:
$msg = "Group limit exceeded!(100,000 max contacts per group)";
break;
case 20:
$msg = "Was not uploaded!";
break;
case 21:
$msg = "You dont have the right to perform this operation!";
break;
case 22:
$msg = "You cannot purchace SMS credit";
break;
case 23:
$msg = "This email address does not exist!";
break;
case 24:
$msg = "Invalid Token!";
break;
case 25:
$msg = "Invalid user!";
break;
case 26:
$msg = "Invalid Link!";
break;
case 27:
$msg = "Wrong file format uploaded!";
break;
case 28:
$msg = "Uploaded file must not exceed 2MB!";
break;
case 29:
$msg = "This email address already exist!";
break;
case 30:
$msg = "must be alpha numeric!(use at least one character)";
break;
case 31:
$msg = "You can work on 100,000 numbers at a time! Number count: ";
break;
case 32:
$msg = "Network code already exist!";
break;
case 33:
$msg = " is a banned keyword!";
break;
case 34:
$msg = "This student does not exist in this school!";
break;
case 35:
$msg = "This user does not exist in this school!";
break;
case 36:
$msg = "ID does not exist!";
break;
case 37:
$msg = "Profile FULL!, delete some to add.";
break;
case 38:
$msg = "You have not registered online!";
break;
case 39:
$msg = "You can post only one topic per day!";
break;
case 40:
$msg = "Wrong date format!";
break;
case 41:
$msg = "This partner has been attached to this school!";
break;
case 42:
$msg = "Another partner has been attached to this school!";
break;
case 43:
$msg = "type must be PDF!";
break;
case 44:
$msg = "You can refer one number per day!";
break;
case 45:
$msg = "Mobile network not covered!";
break;
case 46:
$msg = "Number count exceeds amount in database. ";
break;
case 47:
$msg = "Coupon code already exist!";
break;
case 48:
$msg = "This coupon code does not exist in the system!";
break;
case 49:
$msg = "This coupon has already been used by you!";
break;
case 50:
$msg = "This voucher code does not exist in the system!";
break;
case 51:
$msg = "Invalid license key! To enjoy the best features of this mobile marketing application kindly pay for a valid license key from www.mobicomcms.com. You can call 08031977935.";
break;
case 52:
$msg = "Expired license key! To enjoy the best features of this mobile marketing application kindly pay for a valid license key from www.mobicomcms.com. You can call 08031977935.";
break;
case 53:
$msg = "Dear Friend, thanks for trying out the MobicomCMS. It is indeed the best tool ever developed for success driven Mobile Marketers. You have to purchase a License Key to activate the application fully. Go to www.mobicomcms.com or Call us on 08031977935. Thanks";
break;
case 54:
$msg = "SMS API not set!";
break;
}
return $msg;
}

function success($code)
{
	switch($code)
	{
case 1:
$msg = "Successful!";
break;
case 2:
$msg = "You have registered successfully!";
break;
case 3:
$msg = "Your post was succssful!";
break;
case 4:
$msg = "A mail has been sent to your email box!";
break;
case 5:
$msg = "Your password has been reset successfully!";
break;
case 6:
$msg = "Your message has been sent successfully";
break;
case 7:
$msg = "You have scheduled your message successfully to be sent on";
break;
case 8:
$msg = "Your message has been saved as Draft.";
break;
case 9:
$msg = "You have successfully transfered SMS Credit to";
break;
case 10:
$msg = "A mail has been sent to your email box containing a reset link";
break;
case 11:
$msg = "Due to delay in network, your message will be delivered soon";
break;
case 12:
$msg = "Reseller registered successfully";
break;
case 13:
$msg = "Form created successfully!";
break;
case 14:
$msg = "Form submitted successfully!";
break;
case 15:
$msg = "Form edited successfully!";
break;
case 16:
$msg = "Form item deleted successfully!";
break;
case 17:
$msg = "Form deleted successfully!";
break;
case 18:
$msg = "Data imported successfully!";
break;
case 19:
$msg = "Your job has started!";
break;
case 20:
$msg = "You have set a reminder to be sent on";
break;
case 21:
$msg = "Image uploaded successfully!";
break;
	}
	return $msg;
}
?>