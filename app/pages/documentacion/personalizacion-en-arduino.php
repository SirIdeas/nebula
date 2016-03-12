(:: parent:views/content.php :)
(:: set:title="Personalización en Arduino" :)
(:: set:pagina="documentacion" :)
(:: set:paso="personalizacion-en-arduino" :)

<h1>Personalización en Arduino</h1>
<p>
  A pesar de que el objetivo principal de nébula es llevar a si mínima expresión la programación en Arduino, la librería en Arduino dispone de ciertos aspectos que permiten personalizar la forma de comunicación hasta cierto punto. Esta personalizacion se realiza a través de la definicón de callbacks.
</p>


<h4>Comandos desconocidos</h4>
<p>
  La comunicación predeterminada en Nébula se basa en un grupo de comandos principales descritos en la sección de <a href="<?php Am::eUrl() ?>/documentacion/sketchs#estructura-de-mensajes">Comunicación</a>. Si se desea agregar comandos extras a estos, se puede utiliza el callback de procesamiento de comandos desconocidos.
</p>
<p>
  A continuación se presenta un ejemplo donde se agregan dos comandos:
</p>
<div class="row">
  <div class="col-sm-6">
    <p><strong>Arduino</strong></p>



<p>
  Se define las constantes para identificar cada comando.
</p>
<pre>
#define CMD_1 (__NB_LAST_MSG_CODE + 0x1)
#define CMD_2 (__NB_LAST_MSG_CODE + 0x2)
</pre>


<p>
  Se define una funcion que maneje los comandos desconocidos. Esta función recibe como parámetro un char que representa el comando recibido y retorna verdader o falso, depeniendo si completo o no el comando enviado satisfactoriamente. Si el comando recibido sigue siendo desconocido debe retornarn falso.
</p>
<pre>
bool cmd_else(char cmd){
  switch(cmd){
  case CMD_1:
    // Hacer algo tarea comando 1
    return true;
  case CMD_2:
    // Hacer algo tarea comando 2
    if(com.available(1)){
      int valor = com.readInt();
      // valor == 3420
      return true;
    }
    break;
  }
  return false;
}
</pre>


<p>
  Se asignar el callback al la instancia de comunicación.
</p>
<pre>
void setup(){
  ...
  com.setCallbackUnk(cmd_unk);
}
</pre>

  </div>
  <div class="col-sm-6">
    <p><strong>Android</strong></p>
<p>
  Se definen las constantes para los comandos
</p>
<pre>
int CMD_1 = NbDialect.__LAST_MSG_CODE + 1;
int CMD_2 = NbDialect.__LAST_MSG_CODE + 2;
</pre>

<p>
  Se instancia el sketch reescribiendo el método getUserBytes.
</p>
<pre>
NbSketch sketch = new NbSketch(){
  protected void getUserBytes(){
    
    NbBytes data = new NbBytes();

    data.add(CMD_1);
    data.add(CMD_2);
    data.addInt(3420);

    return data;

  }
}
</pre>
  </div>
</div>


<h4>Comandos a Componentes Objetos</h4>
<p>
  Cuando se esta enviando comandos correspondientes a objetos personalizados, se deben implementar como en el siguiente ejemplo:
</p>

<div class="row">
  
  <div class="col-sm-6">
    <p><strong>Android</strong></p>

<p>
  Para definir componentes objeto, debe heredar la clase <?php docEnlace("NbCmpOutObj") ?>. Cada vez que esta desee enviar datos al microcontrolador se debe utilizar el método <?php docEnlace("NbCmpOutObj.addCmd") ?>, el cual recibe un array de bytes con los datos que se desea enviar.
</p>
<p>
  En el ejemplo actual se enviarán el valor del componente cada vez que se setee el mismo.
</p>
<pre>
public class NbSetVar extends NbCmpOutObj{
  
  public NbSetVar(int id) {
    super(id);
  }
  
  @Override
  public void setValue(long value) {
    if(getValue() == value) return;

    super.setValue(value);
    
    NbBytes data = new NbBytes();
    data.add(value);
    addCmd(data);
    
  }

}
</pre>
<p>
  Por último, se crean instancias de este objeto con identificadores diferentes. Si el componente es conectado a un sketch este se encargará de enviar los comandos cada que corresponda.
</p>
<pre>
// Definir identificadores
int ID_1 = 1;
int ID_2 = 2;

// Crear instancia
NbSetVar var1 = new NbSetVar(ID_1);
NbSetVar var2 = new NbSetVar(ID_2);

// Setear values
var1.setValue(32);
var2.setValue(76);
</pre>
  </div>

<div class="col-sm-6">
    <p><strong>Arduino</strong></p>


<p>
  Se define las constantes de los identidicadores de cada objeto.
</p>
<pre>
#define ID_1 1
#define ID_2 2
</pre>
<p>
  Declarar las variables que contendrán los valores
</p>
<pre>
int var1 = 0;
int var2 = 0;
</pre>
<p>
  Se define una funcion para manejar los mensajes a objetos. Esta función recive un entero que representa el identificador del objeto al que se envió el mensaje. Además esta función retorna verdadero si logra completar la tarea enviada al objeto, de lo contrario retorna falso.
</p>
<pre>
bool cmd_object(int id){

  switch(id){

  case ID_1:
    // Tarea objeto 1
    if(com.available(1)){
      var1 = com.read();
      return true;
    }
    break;
  
  case ID_2:
    // Tarea objeto 2
    if(com.available(1)){
      var2 = com.read();
      return true;
    }
    break;

  }
  return false;
}
</pre>
<p>
  Finalemente se debe asignar la función a la instancia de comunicación como si indaca a continuación:
</p>
<pre>
void setup(){
  ...
  com.setCallbackObject(cmd_object);
}
</pre>
  </div>

</div>

<h4>Enviar datos de Arduino a Android</h4>
<p>
  De igual forma puede existir la necesidad de enviar datos desde el microcontrolador a la aplicación en Android. Existen dos formas de enviar información a la aplicación:
</p>
<p>
  <strong>Enviar información a un Componente Objeto</strong>: Se envia datos a un Componente Objeto específico mediante el identificador del mismo.
</p>
<p>
  <strong>Enviar información a un directa</strong>: Consiste en eviar datos mediante comandos especiales.
</p>
<div class="row">
  <div class="col-sm-6">
    <p><strong>Arduino</strong></p>
<p>
  Para la demostración se define un identificador para enviar información a un objeto
  y un comando personalizado.
</p>
<pre>
#define ID_1 1
#define CMD_1 (__NB_LAST_MSG_CODE + 0x1)
</pre>

<p>
  Para enviar datos desde el microcontrolador se debe definir una función sin parámetros y sin valor de retorno. Se enviará la lectura del pin analógico 0 a un objeto con un determinado identificador, y la lectura del pin analógico 1 por un mensaje personalizado.
</p>
<pre>

int lect0 = 0;
int lect1 = 0;

void cmd_send(void){
  
  int newLect;

  // Enviar datos a un componte objeto
  newLect = analogRead(0);
  if (newLect != lect0){
    com.setObjectValue(ID_1);  // ID del componente
    com.addInt(newLect);
    lect0 = newLect;
  }

  // Enviar datos por mensaje
  newLect = analogRead(1);
  if (newLect != lect1){
    com.add(CMD_1);
    com.addInt(newLect);
    lect1 = newLect;
  }

}
</pre>
<p>
  Por ultimo, asignamos esta función como callback para enviar datos personalizados a la instancia de comunicación:
</p>
<pre>
void setup(){
  ...
  com.setCallbackSend(cmd_send);  
}
</pre>
  </div>
  <div class="col-sm-6">
    <p><strong>Android</strong></p>
<p>
  Declaramos el identificador del objeto y el comando personalizado.
</p>
<pre>
int ID_1 1;
int CMD_1 = NbDialect.__LAST_MSG_CODE + 1;
</pre>
<p>
  Para recibir el los datos en un componente objeto podemos definir una clase que herede de la clase <?php docEnlace("NbCmpOutObj") ?> y reescribir el método <?php docEnlace("NbCmpOutObj.readData") ?> para leer los datos correspondientes de la misma forma en que fueron enviados
</p>
<pre>
public class NbGetVar extends NbCmpOutObj{
  
  public NbGetVar(int id) {
    super(id);
  }
  
  // Cuando se reciba un mensaje a un
  // objeto con el mismo identificador
  // de la instancia se llama este método
  public void readData(NbBuffer data)
    throws NbBuffer.ReadException {
    // data: representa el buffer de bytes de entrada.
    
    // Leer el valor enviado después del identificador
    setValue(data.readInt());
  }

}

// Instanciar un objeto con el identificador usado
NbGetVar analog0 = new NbGetVar(ID_1);
</pre>
<p>
  Por otro lado, para recibir los datos enviados por mensaje personalizado se puede instanciar el sketch rescribiendo el método <?php docEnlace("NbCmpOutObj.unknowCmd") ?>. Este método es llamado cada vez que se lee un mensaje o desconocido. Si esta función logra ejecutar el comando satisfactoriamente deberá retornar verdadero, de lo contrario debe retornar falso.
</p>
<pre>

// variable para almacenar la lectura recibida.
int analog1 = 0;

NbSketch sketch = new NbSketch(){
  public boolean unknowCmd(int cmd, NbBuffer data){
    // cmd: Comando recibido.
    // data: buffer de entrada.
    if(cmd == CMD_1){
      analog1 = data.readInt();
      return true;
    }
  }
}
</pre>
  </div>
</div>

<h4>Preprocesamiento de datos</h4>
<p>
  Asímismo, se puede procesar los datos de entrada antes de que estos sean evaluados en el algoritmo predeterminado establecido en Nébula, y posteriormente decidir si se desea retormar este. Esto se puede hacer de la siguiente forma:
</p>
<div class="row">
  <div class="col-sm-6">
    <p><strong>Arduino</strong></p>
<p>
  Se define una función que recibirá todos los comandos leídos antes de ser procesados por el agloritmo. Esta función recibe dos parámetros:
</p>
<p>
  <strong><code>cmd</code></strong>: Representa el siguiente comando leído.
  <strong><code>unk</code></strong>: parámetro por referencia. Si el comando es un comando desconocido deberá asignarsele falso (<code>unk=false;</code>). En este caso la comunicación sguirá evaluando el comando con el algoritmo predeterminado siempre y cuando la función retorne verdadero.
</p>
<p>
  Por último, este callback deberá retornar verdadero si logra concretar una tarea satisfactoriamente. Si la cola de mensaje no tiene todos los bytes esperados para ejecutar la tarea correspondiente la función deberá retornar falso.
</p>
<pre>
bool cmd_pre(char cmd, bool& unk){
  ...
  // Comando desconocido. Seguir evaluando con
  // algoritmo clásico si retorna la función true.
  unk = false;
  ...
  // Tarea no completada por falta de datos
  return false;
  ...
  // Tarea ejecutado satisfactoriamente
  return true;
}
</pre>
<p>
  Por último se debe asignar el callback a la instancia de la comunicación.
</p>
<pre>
void setup(){
  ...
  com.setCallbackPre(cmd_pre);  
}
</pre>
  </div>
  <div class="col-sm-6">
    <p><strong>Android</strong></p>
<p>
  Para interceptar los mensajes en la aplicación Android, se debe sobreescribir el método <?php docEnlace("NbSketch.preproccess") ?> del sketch. Esta función recibe el comando leído en el primer parámetro como un entero, y un buffer con los datos entrantes en el segundo parámetro. Además esta función puede retornar 3 posibles valores:
</p>
<p><strong><code>CmdResult.COMPLETE</code></strong>: Indica que la tarea para el comando se completo satisfactoriamente.</p>
<p><strong><code>CmdResult.INCOMPLETE</code></strong>: Indica que la tarea para el comando no se culminó correctamente.</p>
<p><strong><code>CmdResult.UNKNOW</code></strong>: Indica que no se reconoció el comando.</p>
<pre>
NbSketch sketch = new NbSketch(){
  protected CmdResult
    preproccess(int cmd, NbBuffer data)
    throws NbBuffer.ReadException{
    // cmd: Siguiente comando a evaluar.
    // data: Buffer de bytes de entrada.
    
    // Comando reconocido ejecutado staisfactoriamente.
    return CmdResult.COMPLETe;
    
    // Comando reconocido pero no ejecutado.
    return CmdResult.UNKNOW;
    
    // Comando desconocido.
    return CmdResult.UNKNOW;

  }
}
</pre>
  </div>
</div>

<h5>Cola de mensajes</h5>
<p>
  Para poder procesaro la cola de mensaje de forma personalizada, se debería conocer también la estrucutra de la misma.
</p>
<p>
  La cola de mensaje está conformada por una serie de mensajes. Cada mensaje tiene Byte de que indica el comando seguido de los bytes necesarios para ejecutar el mismo. Justo después del final del mensaje comienza sl siguiente mensaje. La cola de mensaje culmina con el mensaje FINISH que indica que debe pasar a dar respuesta a su contraparte.
</p>
<p>
  En la siguiente tabla se indica los bytes utilizado por cada comando.
</p>
<table class="rejilla proceso-com">
  <thead>
    <tr>
      <th rowspan="2">Comando</th>
      <th class="text-center" colspan="11">Descripción de bytes</th>
    </tr>
    <tr>
      <th class="byte">1</th>
      <th class="byte">2</th>
      <th class="byte">3</th>
      <th class="byte">4</th>
      <th class="byte">5</th>
      <th class="byte">6</th>
      <th class="byte">7</th>
      <th class="byte">8</th>
      <th class="byte">9</th>
      <th class="byte">10</th>
      <th class="byte">11</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th><code>MSG_FINISH</code></th>
      <td class="obb">CMD</td>
    </tr>
    <tr>
      <th><code>MSG_DIGITAL_IN_CONF</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
    </tr>
    <tr>
      <th><code>MSG_PIN_MODE_OUT</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
    </tr>
    <tr>
      <th><code>MSG_ANALOG_WRITE</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb">Valor</td>
    </tr>
    <tr>
      <th><code>MSG_ANALOG_IN_CONF</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
    </tr>
    <tr>
      <th><code>MSG_ANALOG_IN_READ</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb" colspan="2">Lectura</td>
    </tr>
    <tr>
      <th><code>MSG_DIGITAL_WRITE</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb">Valor</td>
    </tr>
    <tr>
      <th><code>MSG_OBJECT_CMD</code></th>
      <td class="obb">CMD</td>
      <td class="even" colspan="2">Id</td>
      <td colspan="4" style="border-right-style:dashed">Datos (la cantidad de bytes depende del objeto)</td>
    </tr>
    <tr>
      <th><code>MSG_DIGITAL_IN_READ</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb">Lectura</td>
    </tr>
    <tr>
      <th><code>MSG_MOTORDC_WRITE</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin EN</td>
      <td class="obb">Pin IN1</td>
      <td class="even">Pin IN2</td>
      <td class="obb">Valor EN</td>
      <td class="even">Valor IN1</td>
      <td class="obb">Valor IN2</td>
    </tr>
    <tr>
      <th><code>MSG_STEPTOSTEP_MOVE</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin A</td>
      <td class="obb">Pin B</td>
      <td class="even">Pin C</td>
      <td class="obb">Pin D</td>
      <td class="even" colspan="2">Pasos</td>
      <td class="obb" colspan="2">Velocidad</td>
      <td class="even">Dirección</td>
      <td class="obb">Actual</td>
    </tr>
    <tr>
      <th><code>MSG_SET_STATE_DIGITAL</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb">Estado</td>
    </tr>
    <tr>
      <th><code>MSG_SET_STATE_ANALOG</code></th>
      <td class="obb">CMD</td>
      <td class="even">Pin</td>
      <td class="obb">Estado</td>
    </tr>
  </tbody>
</table>
