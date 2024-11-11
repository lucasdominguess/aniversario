<?php 
namespace App\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require __DIR__.'../../../vendor/autoload.php'; // Necessario caso a classe seja ultilizada como um serviço 
// require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
// require '../../vendor/phpmailer/phpmailer/src/Exception.php';


class Email 
{ 
/**
 * @method mandar_email  metodo responsavel por fazer o envio de email 
 * OBS: caso essa classe esteja fora da aplicação (como serviço) buscar com require "caminho_seuprojeto/vendor/autoload.php"
 * @param string|array $email email do usuario , podendo passar um array com varios emails 
 * @param string $subject Titulo do email 
 * @param string $body Corpo do email 

 *  
 * */
public function mandar_email(string |array $email,$name='',string $subject,string $body)
    {
        global $env ; 
        $mail = new PHPMailer(true);
       
        // config $env (Pode nao funcionar caso esta classe seja ultilizada como serviço) 
    //    $email = $env['email_log'] ?? "lucasdomingues25.dev@gmail.com";
      
    //    $password = $env['password'] ?? '';
    //    $smtp = $env['smtp'] ?? "smtpcorp.prodam";
    //    $port= $env['portemail'] ?? 25;
    //    $sender = $env['sender'] ?? 'smsdtic@prefeitura.sp.gov.br';
    //    $auth = $env['auth'] ?? false;

        $host_stmt = $env['host_stmt'] ?? 'smtp.email.sa-saopaulo-1.oci.oraclecloud.com';
        $SMTPAuth = $env['SMTPAuth']?? true;
        $username= $env['username'] ?? "ocid1.user.oc1..aaaaaaaan43kkrz5frw6cwd555mulm7v6ylztb2xmasnnzpcw7be3nrnj34a@ocid1.tenancy.oc1..aaaaaaaai7a72s5jgxce6rik7qna2nx2j3flclxvvrkg3mojvxmrjz3hmf6q.jo.com";
        $password = $env['password_email'] ?? 'MkVFPT-w4(3(xuy_2J9r';
        $SMTPSecure=$env['SMTPSecure'] ?? 'ssl';
        $SMTPAutoTLS=$env['SMTPAutoTLS'] ?? true;
        $port_email=$env['port_email'] ?? 587;
        $sender = $env['sender'];
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
            $mail->isSMTP();                                            
            $mail->Host       = $host_stmt ; 
            $mail->SMTPAuth   = $SMTPAuth;  //prodam = false                              
            $mail->Username   =  $username; 
            $mail->Password   = $password;                             
            $mail->SMTPSecure = $SMTPSecure;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
            $mail->SMTPAutoTLS = $SMTPAutoTLS;
            $mail->Port  = $port_email;         
            
            // $mail->isSMTP();                                            //Send using SMTP
            // $mail->Host       = 'smtp.email.sa-saopaulo-1.oci.oraclecloud.com';                     //Set the SMTP server to send through
            // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            // $mail->Username   = 'ocid1.user.oc1..aaaaaaaan43kkrz5frw6cwd555mulm7v6ylztb2xmasnnzpcw7be3nrnj34a@ocid1.tenancy.oc1..aaaaaaaai7a72s5jgxce6rik7qna2nx2j3flclxvvrkg3mojvxmrjz3hmf6q.jo.com';                     //SMTP username
            // $mail->Password   = 'MkVFPT-w4(3(xuy_2J9r';                               //SMTP password
            // $mail->SMTPSecure = 'ssl';//importante explicitar estes dois itens
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            // $mail->SMTPAutoTLS = true; //importante explicitar estes dois itens
            // $mail->Port       = 587;     
            //Recipients
            //
            
            
            $recipients = is_array($email) ? $email : [$email];
            
            foreach ($recipients as $recipient) {
                $mail->setFrom($sender);
                $mail->addAddress($recipient, $name); // Adiciona o destinatário
    
                //Content
                $mail->isHTML(true);                               
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $mail->CharSet ='UTF-8';

                $mail->send();
            }

            

        
        } catch (\Exception $e) {
            throw new \Exception("Não foi possivel enviar email {$mail->ErrorInfo}");
        }
    }

   

}