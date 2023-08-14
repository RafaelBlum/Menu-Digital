<?php


namespace Source\Supports;


use PHPMailer\PHPMailer\PHPMailer;
use Source\Core\Connect;
use source\Supports\Message;

class Email
{
    /**@var array*/
    private $data;

    /**@var PHPMailer*/
    private $mail;

    /**@var Message*/
    private $message;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->message = new Message();

//        "host" => "smtp.hostinger.com",
//        "port" => 465,
//        "user" => CONF_SITE_MAIL,
//        "passwd" => "!@#$%2023Az",
//        "from_name" => CONF_SITE_NAME,
//        "from_email" => "menu-digital@menu-digital.online",
//        "sender" => ["name" => CONF_SITE_NAME, "address" => CONF_SITE_MAIL],
//        "lang" => "br",
//        "html" => true,
//        "auth" => true,
//        "secure" => "ssl",
//        "charset" => "utf-8",
//        "support" => CONF_SITE_MAIL
        /**
         * SETUP MAIL
        */
        $this->mail->isSMTP();
        $this->mail->setLanguage(MAIL["lang"]);
        $this->mail->isHTML(MAIL["html"]);
        $this->mail->SMTPAuth = MAIL["auth"];
        $this->mail->SMTPSecure = MAIL["secure"];
        $this->mail->CharSet = MAIL["charset"];

        /**
         * AUTH DB
         * pt-br: Dado de autenticação de e-mail
         */
        $this->mail->Host = MAIL["host"];
        $this->mail->Port = MAIL["port"];
        $this->mail->Username = MAIL["user"];
        $this->mail->Password = MAIL["passwd"];
    }

    public function bootstrap(string $subject, string $body, string $recipient, string $recipientName): Email
    {
        /**
         * MAIL
         * pt-br: Quem está enviando o e-mail
        */
        $this->data = new \stdClass();
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient; // EMAIL PASSADO
        $this->data->recipient_name = $recipientName; // NOME PASSADO
        return $this;
    }

    public function send(string $from = MAIL["sender"]["address"], string $fromName = MAIL["sender"]["name"]): bool
    {
        if(empty($this->data)){
            $this->message->warning("Error! Verifique seus dados!");
            return false;
        }

        if(!is_email($this->data->recipient_email)){
            $this->message->warning("Error! O destinatário invalido!");
            return false;
        }

        if(!is_email($from)){
            $this->message->warning("Error! O remetente é invalido!");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);

            /**
             * SETANDO OS DADO PARA QUEM ENVIAR
             */
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);

            /**
             * SETANDO OS DADO DE QUEM ENVIOU
            */
            $this->mail->setFrom($from, $fromName);

            if(!empty($this->data->attach)){
                foreach ($this->data->attach as $path => $name){
                    $this->mail->addAttachment($path, $name);
                }
            }


            $this->mail->send();
            return true;

        }catch (\Exception $exception){
            $this->message->warning($exception->getMessage());
            return false;
        }
    }

    public function queue(string $from = MAIL["sender"]["address"], string $fromName = MAIL["sender"]["name"]): bool
    {
        try{
            $smtp = Connect::getInstance()->prepare(
                "INSERT INTO 
                    mail_queue (subject, body, from_email, from_name, recipient_email, recipient_name) 
                    VALUES (:subject, :body, :from_email, :from_name, :recipient_email, :recipient_name)"
            );

            $smtp->bindValue(":subject", $this->data->subject, \PDO::PARAM_STR);
            $smtp->bindValue(":body", $this->data->body, \PDO::PARAM_STR);
            $smtp->bindValue(":from_email", $from, \PDO::PARAM_STR);
            $smtp->bindValue(":from_name", $fromName, \PDO::PARAM_STR);
            $smtp->bindValue(":recipient_email", $this->data->recipient_email, \PDO::PARAM_STR);
            $smtp->bindValue(":recipient_name", $this->data->recipient_name, \PDO::PARAM_STR);

            $smtp->execute();
            return true;

        }catch (\Exception $exception){
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    public function sendQueue(int $perSecond = 5)
    {
        $smtp = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE sent_at IS NULL");
        if($smtp->rowCount()){
            foreach ($smtp->fetchAll() as $send){
                $email = $this->bootstrap(
                    $send->subject,
                    $send->body,
                    $send->recipient_email,
                    $send->recipient_name
                );

                if($email->send($send->from_email, $send->from_name)){
                    usleep(1000000 / $perSecond);
                    Connect::getInstance()->exec("UPDATE mail_queue SET sent_at = NOW() WHERE  id = {$send->id}");
                }
            }
        }
    }

    /** TESTES */
    public function sendNewsQueue(int $perSecond = 5)
    {
        $smtp = Connect::getInstance()->query("SELECT * FROM newslatter");
        if($smtp->rowCount()){
            foreach ($smtp->fetchAll() as $send){
                $email = $this->bootstrap(
                    "SUBJECT",
                    "BODY",
                    $send->email,
                    $send->name
                );

                if($email->send($send->email, $send->name)){
                    usleep(1000000 / $perSecond);
                }
            }
        }
    }

    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    public function message(): Message
    {
        return $this->message;
    }

}