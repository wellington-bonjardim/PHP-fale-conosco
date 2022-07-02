<?php 
    require "./PHPMailer/Exception.php";
    require "./PHPMailer/OAuth.php";
    require "./PHPMailer/PHPMailer.php";
    require "./PHPMailer/POP3.php";
    require "./PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Chamado {
        private $nome = null;
        private $intencao = null;
        private $sobre = null;
        private $email = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = [
            'intencao_status' => null,
            'resposta_status' => ''
        ];

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if(empty($this->nome) || empty($this->intencao) || empty($this->sobre) || empty($this->email) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }
            return true;
        } 
    }

    $chamado = new Chamado();

    $chamado -> __set('nome', $_POST['nome']);
    $chamado -> __set('intencao', $_POST['intencao']);
    $chamado -> __set('sobre', $_POST['sobre']);
    $chamado -> __set('email', $_POST['email']);
    $chamado -> __set('assunto', $_POST['assunto']);
    $chamado -> __set('mensagem', $_POST['mensagem']);

    if(!$chamado -> mensagemValida()) {
        echo 'MENSAGEM INVÁLIDA';
        header('Location: index.html');
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                                //Send using SMTP
        $mail->Host       = 'smtp-mail.outlook.com';                    //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                       //Enable SMTP authentication
        $mail->Username   = 'phptestewb@outlook.com';  //SMTP username
        $mail->Password   = 'W123456&*';            //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom('phptestewb@outlook.com', $chamado->__get('email'));
        $mail->addAddress('phptestewb@outlook.com', 'Fale Conosco');
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $chamado -> __get('assunto');
        $mail->Body    = $chamado -> __get('mensagem');
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        
        $chamado -> status['intencao_status'] = 1;
        $chamado -> status['resposta_status'] = 'E-mail enviado com sucesso!';
        
    } catch (Exception $e) {

        $chamado -> status['intencao_status'] = 2;
        $chamado -> status['resposta_status'] = "Não foi possível enviar a sua mensagem. Estamos trabalhando para corrigir este erro. Detalhes do erro: {$mail->ErrorInfo}";
        
    }
?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Fale Conosco ✉</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <div class="container">
            <div class="py-3 text-center">
                <img src="assets/logo.png" class="d-block mx-auto mb-2" width="100" height="100">
                <h2 class="font-weight-bold">Fale Conosco</h2>
                <p>Envie para nós suas dúvidas, reclamações ou sugestões!</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php 
                        
                        if($chamado->status['intencao_status'] == 1) {
                    ?>
                        <div class="container">
                            <h1 class="d-block mx-auto text-center display-4 text-success">Sucesso</h1>

                            <p class="text-center font-weight-bold">
                            <?= $chamado->status['resposta_status'] ?>
                            </p>

                            <div class="row">
                                <div class="col-md-4 mx-auto">
                                    <a href="index.html" class="d-block btn btn-success btn-lg text-white">Voltar</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php 
                        if($chamado->status['intencao_status'] == 2) {
                    ?>
                        <div class="container">
                            <h1 class="d-block mx-auto text-center display-4 text-danger">FRACASSO</h1>

                            <p class="text-center font-weight-bold">
                            <?= $chamado->status['resposta_status'] ?>
                            </p>

                            <div class="row">
                                <div class="col-md-4 mx-auto">
                                    <a href="index.html" class="d-block btn btn-danger btn-lg text-white">Voltar</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>