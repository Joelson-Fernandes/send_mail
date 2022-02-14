<?php

    require "./biblioteclas/PHPMailer/Exception.php";
    require "./biblioteclas/PHPMailer/OAuth.php";
    require "./biblioteclas/PHPMailer/PHPMailer.php";
    require "./biblioteclas/PHPMailer/POP3.php";
    require "./biblioteclas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo-status' => null, 'descricao_status' => '');

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem();
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    if(!$mensagem->mensagemValida()) {
        echo "A mensagem não é valida";
        header('Location: index.php');
    } 
    
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'EMAIL';                     //SMTP username
            $mail->Password   = 'SENHA';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('', '');
            $mail->addAddress($mensagem->__get('para'));     //Add a recipient
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $mensagem->__get('assunto');
            $mail->Body    = $mensagem->__get('mensagem');
            $mail->AltBody = 'é necessario utilizar um client que suporte HTML para ter acesso total ao conteudo dessa mensagem';

            $mail->send();
            $mensagem->status['codigo_status'] = 1;
            $mensagem->status['descricao_status'] = 'Sua mensagem foi enviada com sucesso!';
       
        } catch (Exception $e) {
            $mensagem->status['codigo_status'] = 2;
            $mensagem->status['descricao_status'] = "Não foi possivel enviar este Email. Por favor, tente novamente. Detalhes do erro:" . $mail->ErrorInfo;

            
        }   

?>

<html lang="pt-br">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

	<body>

        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="img/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <?php if($mensagem->status['codigo_status'] == 1) { ?> 
                        <div class="container">
                            <h1 class="display-4 text-success text-center">Sucesso!</h1>
                            <p class="text-center"><?= $mensagem->status['descricao_status'] ?></p>
                            <div class="text-center"><a href="index.php" class="btn btn-success btn-lg mt-2 text-white">Voltar</a></div> 
                        </div>
                    <?php } ?>

                    <?php if($mensagem->status['codigo_status'] == 2) { ?> 
                        <div class="container">
                            <h1 class="display-4 text-danger text-center">Ops!</h1>
                            <p class="text-center"><?= $mensagem->status['descricao_status'] ?></p>
                            <div class="text-center"><a href="index.php" class="btn btn-danger btn-lg mt-2 text-white text-center">Voltar</a></div>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>

    </body>
</html>