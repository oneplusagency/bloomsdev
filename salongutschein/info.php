<?php 
//$filename ="57847565-frest-clean-minimal-bootstrap-admin-dashboard-template-license.pdf";
//$path = "/var/www/vhosts/hosting110885.a2f45.netcup.net/development/kunden/blooms/1plus/upload/kontakt/";

//$filename ="";
//$path = "";
//echo mail_attachmentinfo($filename,$path ,"maansawebworldphp@gmail.com",'Kontakt Form','info@developservice.de',"Jack",'no-reply@developservice.de',"Testing email");

//function mail_attachmentinfo($filename='', $path='', $mailto='', $subject='', $from_mail='', $from_name='', $replyto='no-reply@developservice.de',  $message='') {
//       
//
//        
//        
//        
//            
//           
//            
//        
//            $file = $path.$filename;
//            $file_size = filesize($file);
//            $handle = fopen($file, "r");
//            $content = fread($handle, $file_size);
//            fclose($handle);
//
//            $content = chunk_split(base64_encode($content));
//            $uid = md5(uniqid(time()));
//            $name = basename($file);
//
//            $eol = PHP_EOL;
//
//            // Basic headers
//            $header = "From: ".$from_name." <".$from_mail.">".$eol;
//            $header .= "Reply-To: ".$replyto.$eol;
//            $header .= "MIME-Version: 1.0\r\n";
//            $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"";
//
//            // Put everything else in $message
//            $message = "--".$uid.$eol;
//            $message .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
//            $message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
//            $message .= $body.$eol;
//            $message .= "--".$uid.$eol;
//            $message .= "Content-Type: application/pdf; name=\"".$filename."\"".$eol;
//            $message .= "Content-Transfer-Encoding: base64".$eol;
//            $message .= "Content-Disposition: attachment; filename=\"".$filename."\"".$eol;
//            $message .= $content.$eol;
//            $message .= "--".$uid."--";
//
//            if (mail($mailto, $subject, $message, $header))
//            {
//                return "mail_success";
//            }
//            else
//            {
//                echo "<pre>"; print_r(error_get_last()['message']); echo "</pre>"; die();
//                
//                return "mail_error";
//            }
//    
//    
//    
//    $header = "From: ".$from_name." <".$from_mail.">\n";
//    $header .= "Reply-To: ".$replyto."\n";
//    $header .= "MIME-Version: 1.0\n";
//    
//    if (mail($mailto, $subject, $message, $header)){
//        return "mail_success";
//    }else{
//        return "mail_error";
//    }
//    
//    }

?>