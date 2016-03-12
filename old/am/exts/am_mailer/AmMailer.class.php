<?php

/**
 * Clase para el envío de E-mails
 */

// Interfaz para clases que puedan enviar y recibir correso
interface AmAddress{
  public function getMail();
  public function getName();
}

class AmMailer extends PHPMailer{

  // Nombre del SMTP
  protected
    $isHTML = false,  // Indica si el contenido es o no HTML
    $dir = "mails/",  // Directorio donde se buscara la vista a renderizar
    $template = null, // Configuracion STMP. Si es null entonces no se enviará por smtp
    $with = array();  // Variables a utilizar en las vistas

  // Constructor
  public function __construct($name = null, $options = array()) {
    parent::__construct();

    if(isset($name)) $this->template("$name.mail.php");

    // Asignar configuracion de cada parametros
    if(isset($options["smtp"]))       $this->smtpConf($options["smtp"]);
    if(isset($options["charset"])){   $this->charset($options["charset"]); }
    if(isset($options["template"])){  $this->template($options["template"]); }
    if(isset($options["wordWrap"])){  $this->wordWrap($options["wordWrap"]); }
    if(isset($options["altBody"])){   $this->altBody($options["altBody"]); }
    if(isset($options["subject"])){   $this->subject($options["subject"]); }
    if(isset($options["isHtml"])){    $this->isHTML($options["isHtml"]); }
    if(isset($options["body"])){      $this->body($options["body"]); }
    if(isset($options["with"])){      $this->with($options["with"]); }
    if(isset($options["dir"])){       $this->dir($options["dir"]); }

    // Asignación de remitente del correo
    if(isset($options["from"])){
      $address = $options["from"];
      if($address instanceof AmAddress){
        $from = $address->getMail();
        $fromName = $address->getName();
      }elseif(is_array($address)){
        $from = isset($address["user"])? $address["user"] : null;
        $fromName = isset($address["as"])? $address["as"] : null;
      }else{
        $from = $fromName = $address;
      }
      $this->from($from, $fromName);
    }

    // Recorrer cada uno de los tipos de destinatarios
    foreach(array(
      "replyTo" => "addReplyTo",
      "address" => "addAddress",
      "cc" => "addCC",
      "bcc" => "addBCC",
    ) as $key => $fn){

      // Si las opciones tienen nombrado elemento
      if(isset($options[$key])){
        // Si no se puede agregar la direccion y el valor es un array
        if(!$this->parseAndAdd($fn, $options[$key]) && is_array($options[$key])){
          // Recorrer el array para agregar cada posicion como una direccion
          foreach($options[$key] as $address){
            $this->parseAndAdd($fn, $address);
          }
        }
      }
    }

  }

  // Para agregar un destinatario con un determinado método
  public function parseAndAdd($addMethod, $addresses){
    // Si es un array y tiene un elemento "user"
    if($addresses instanceof AmAddress){
      $this->$addMethod($addresses->getMail(), $addresses->getName());
    }elseif(isset($addresses["user"])){
      // El parametro es un array con la direccion a enviar y el nombre
      $to = $addresses["user"];
      $toName = isset($addresses["as"])? $addresses["as"] : $to;
      $this->$addMethod($to, $toName);
      return true;
    }elseif(is_string($addresses)){
      // El parametro e la direccion de email destino
      $to = $toName = $addresses;
      $this->$addMethod($to, $toName);
      return true;
    }
    return false;
  }

  // Asignacion de la configuracion SMTP
  public function smtpConf(array $smtp){

    // SMTP Configuration
    $this->isSMTP();
    $this->SMTPAuth = true;
    $this->Host = isset($smtp["host"])? $smtp["host"] : null;
    $this->Username = isset($smtp["user"])? $smtp["user"] : null;
    $this->Password = isset($smtp["pass"])? $smtp["pass"] : null;
    $this->SMTPSecure = isset($smtp["secure"])? $smtp["secure"] : null;

    // Asignar puerto si esta definido
    if(isset($smtp["port"])) $this->Port = $smtp["port"];

    // Asignar remitente
    $this->from($this->Username, isset($smtp["as"])? $smtp["as"] : null);

  }

  // Asiigna el directorio donde se buscará la vista a renderizar
  public function dir($dir){
    $this->dir = $dir;
    return $this;
  }

  // Método para asignar remitente
  public function from($email, $as = null){
    $this->From = $email;
    $this->FromName = $as;
    return $this;
  }

  // Metodo para signar template a renderizar para el mensaje
  public function template($template){
    $this->template = $template;
    return $this;
  }

  // Método set para el charset
  public function charset($charset){
    $this->CharSet = $charset;
    return $this;
  }

  // Método set para el wordWrap
  public function wordWrap($num){
    $this->WordWrap = $num;
    return $this;
  }

  // Método set para el subject
  public function subject($text){
    $this->Subject = $text;
    return $this;
  }

  // Método set para el altBody
  public function altBody($text){
    $this->AltBody = $text;
    return $this;
  }

  // Funcion para asignar el cuerpo del mensaje
  public function body($body){
    $this->Body = $body;
    return $this;
  }

  // Funcion para asignar el cuerpo del mensaje
  public function isHTML($value = null){
    if(isset($value)){
      $this->isHTML = $value;
      parent::isHTML($value);
      return $this;
    }
    return $this->isHTML;
  }

  // Asigna las variables con las que se renderizará el mensaje
  public function with(array $values){
    $this->with = $values;
    return $this;
  }

  // Metodo para agregar direccion destinataria
  public function addAddress($address, $name = "") {
    parent::addAddress($address, $name);
    return $this;
  }

  // Metodo para agregar direccion de respuesta
  public function addReplyTo($address, $name = "") {
    parent::addReplyTo($address, $name);
    return $this;
  }

  // Metodo para agregar direccion con copia
  public function addCC($address, $name = "") {
    parent::addCC($address, $name);
    return $this;
  }

  // Metodo para agregar direccion con copia oculta
  public function addBCC($address, $name = "") {
    parent::addBCC($address, $name);
    return $this;
  }

  //Funcion para obtener la información del último error
  public function errorInfo(){
    return $this->ErrorInfo;
  }

  public function getContent($with = null){

    // Si se reciben variables se asignan al contexto
    if(isset($with))
      $this->with($with);

    // Agregar body alas variables de entorno para el renderizado
    $env = array_merge(array("body" => $this->Body), $this->with);

    ob_start();

    // Renderizar vista mediante un callback
    $ret = Am::call("render.template",
      $this->template,
      array($this->dir),
      array(
        "ignore" => true,
        "env" => $env
      )
    );

    // Obtener contenido renderizado
    $content = ob_get_clean();

    // Si se renderizo la vista con exito
    // se retorna el contenido del renderizado
    return $ret? $content : $this->Body;

  }

  // Método para enviar el mensaje
  public function send($with = null){
    // Renderizar contenido
    $content = $this->getContent($with);

    // Se se devolvió un contenido válido se asigna al body
    if(isset($content)){
      $this->body($content);
    }

    // Enviar
    return parent::send();

  }

  // Obtener una instancia de un Mail con su respectiva configuraion tomada de
  //
  public static function get($name, array $options = array()){

    // Obtener configuraciones de mails
    $mails = Am::getAttribute("mails");

    // Combinar opciones recibidas en el constructor con las
    // establecidas en el archivo de configuracion
    $options = array_merge(
      // Configuración de valores po defecto
      isset($mails["defaults"])? $mails["defaults"] : array(),
      // Configuración de valores del mail
      isset($mails[$name])? $mails[$name] : array(),
      // Parametros locales
      $options
    );

    // Si no es un array se buscará la configuracion en
    // el archivo de configuracion SMTP
    if(!is_array($options["smtp"])){

      // Obtener configuraciones STMP
      $smtpConfs = Am::getAttribute("smtp", array());

      // Si se debe tomar la configuracion por defecto
      if($options["smtp"] === true)
        $options["smtp"] = "default";

      // Asignar configuraio
      $options["smtp"] = $smtpConfs[$options["smtp"]];

    }

    // Crear instancia del mailer
    return new AmMailer($name, $options);

  }

}
