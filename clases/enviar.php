<?php
include_once("PHPMailer/class.phpmailer.php");  
class Enviar {
function SendMAIL($para,$subject,$body,$altbody,$mailFROM,$mailNameCompany){
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    //$mail->PluginDir = "./phpmailer/";
    $mail->Mailer = "smtp";
    $mail->Host = "smtp.gmail.com"; # Editar el Host smtp
    $mail->SMTPAuth = true;
    
    $mail->Username = "normas@macaws.net";# editar el usuario
    $mail->Password = "normas11"; # Editar el password
    $mail->From = $mailFROM;
    $mail->Port = 465;
    $mail->FromName = $mailNameCompany;
    $mail->Subject = $subject;
    $mail->SMTPSecure = "ssl";
    $email = $para;
    $body = $body;
     
    $mail->Body = $body;
    $mail->AltBody = $altbody;
    $mail->Timeout=20;
    $mail->AddAddress($para);
    $exito = $mail->Send();
         $intentos=1; 
           while((!$exito)&&($intentos<5)&&($mail->ErrorInfo!="SMTP Error: Data not accepted")){
           sleep(5);
                $exito = $mail->Send();
                $intentos=$intentos+1;                
           }
        if ($mail->ErrorInfo=="SMTP Error: Data not accepted") 
           $exito=true;
    return $exito;
}
}
?>