<?php

    require "PHPMailer/Exception.php";
    require "PHPMailer/OAuth.php";
    require "PHPMailer/PHPMailer.php";
    require "PHPMailer/POP3.php";
    require "PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null; 
        public $status = array('codigo_status' => null, 'descricao_status' => '');

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            } 

            return true;
        }
    }

    $mensagem = new Mensagem();
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    if (!$mensagem->mensagemValida()){
        echo "A mensagem não é válida";
        header('Location: index.php');
    } 

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = false;                             
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';                     
        $mail->SMTPAuth = true;                              
        $mail->Username = '';     //Email remetente                           
        $mail->Password = '';     //Senha remetente    
        $mail->SMTPSecure = 'tls';                           
        $mail->Port = 587;                                   

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';


        $mail->setFrom('', ''); 
        $mail->addAddress($mensagem->__get('para'));     
  
        $mail->isHTML(true);                                 
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');

        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';

    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possível enviar este e-mail! Por favor, tente novamente mais tarde.' . $mail->ErrorInfo;

    }
?>

<html>
    <head>
        <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>

    <body>
        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                        if($mensagem->status['codigo_status'] == 1){
                            echo '<div class="container">
                                    <h1 class="display-4 text-success">Sucesso<h1>
                                   </div>
                                   
                                   <p>' . $mensagem->status['descricao_status'] . '</p>'
                                   ;
                        } 

                    ?>
                </div>
            </div>
        </div>
    </body>

</html>