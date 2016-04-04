package com.nebula.sketch;

import java.util.Vector;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;
import com.nebula.com.NbCom;
import com.nebula.performer.NbPerformer;
import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.core.NbCmpVector;
import com.nebula.sketch.cmp.in.NbCmpIn;
import com.nebula.sketch.cmp.in.NbCmpInAnalog;
import com.nebula.sketch.cmp.in.NbCmpInDigital;
import com.nebula.sketch.cmp.interfaces.NbAnalog;
import com.nebula.sketch.cmp.interfaces.NbDigital;
import com.nebula.sketch.cmp.interfaces.NbOut;
import com.nebula.sketch.cmp.interfaces.NbPin;

import android.os.Bundle;
import android.os.Message;

/**
 * Grupo de dispositivos conectados.
 * 
 * @version 1.0
 *
 */
public class NbSketch extends NbCmpVector implements NbPerformer{
	
	// Autogenerado
	private static final long serialVersionUID = -1882053216387613732L;
	
	// Posibles retornos de preproccess.
	public static enum CmdResult{
		COMPLETE,		// Comando completado
		INCOMPLETE,		// Comando no completado
		UNKNOW			// Comando desconocido
	};
	
	
	/**
	 * Indica si el sketch esta en ejecución o pausado.
	 */
	private boolean pause = true;

	/**
	 * Indica que sedebe sincronizar las salidas cuando cuando se llame el método play;.
	 */
	private boolean mSyncWhenPlay = false;
	
	/**
	 * Instancia de la comunicación del Skecth.
	 */
	private NbCom mCom = null;
	
	/**
	 * Bytes de configuracion de usuario. Contiene los bytes de configuración personalizados
	 */
	private NbBytes mSetupUserBytes = new NbBytes();
	
	/**
	 * Instancia del listener para eventos del sketch.
	 */
	private Vector<NbSketchHandler> mHandlers = new Vector<NbSketchHandler>();
	
	/**
	 * Constructor.
	 * Inicializa los parámetros requeridos.
	 * 
	 * @param com	Instancia de comunicación del Sketch.
	 */
	public NbSketch(){}
	
	/**
	 * Obtiene la la instancia de comunicación del sketch.
	 * 
	 * @return Instancia de comunicación del sketch.
	 */
	public NbCom getCom() {
		return mCom;
	}
	
	/**
	 * Asigna la instancia de comunicación del sketch.
	 * 
	 * @param com	Instancia de comunicación  a asignar.
	 */
	public void setCom(NbCom com){
		mCom = com;
		com.setPerformer(this);
	}
	
	/**
	 * Busca un componente con un determinado ID dentro de los componentes conectados al Sketch.
	 * 
	 * @param id	Id del dispositivo a buscar.
	 * @return		Componente con el id buscado o<code>null</code> si no se encuentra un dispositivo.
	 */
	public NbCmp getComponentById(int id){
		for (NbCmp device : this) {
			if(device.getId() == id) return device;
		}
		// No se encontró ningún componente.
		return null;
	}
	
	/**
	 * Agregar Bytes al buffer de salida de la comunicación.
	 * 
	 * @param data: Lista de bytes a agregar
	 */
	public void write(NbBytes data){
		if(mCom!=null)
			mCom.write(data);
	}
	
	/**
	 * Obtiene un vector de componentes de in tipo específico relacionados a un pin específico.  
	 * 
	 * @param pin	Pin del que se desea obtener los compoentens relacionados.
	 * @param cls	Clase por la que se quiere filtrar los componentes.
	 * @return		Vector de componentes relacionados.
	 */
	public NbCmpVector getComponentsByPin(int pin, Class <?> cls){
		NbCmpVector ret = new NbCmpVector();
		for (NbCmp device : this) {
			// Si el componente esta relacionado a un pin.
			if(device instanceof NbPin && cls.isInstance(device)){
				if(pin == ((NbPin)device).getPin()) ret.add(device);					
			}
		}
		return ret;
	}
	
	/**
	 * Devuele la lista de bytes de configuracion del usuario. Puede ser
	 * manejada a conveniencia.
	 * @return Lista de bytes de configuracion personalizada
	 */
	public NbBytes getSetupUserBytes(){
		return mSetupUserBytes;
	}
	
	/**
	 * Agrega un byte de configuracion del usuario
	 * @param b:	byte a agregar
	 */
	public void addSetupByte(int b){
		mSetupUserBytes.add(b);
	}
	
	/**
	 * Conecta un componente con el Sketch.
	 * Agrega el componente a la lista de componentes de conectados del Sketch y configura el mismo.
	 * 
	 * @param component		Componente a conectar.
	 */
	public void connect(NbCmp component){
		add(component);
		component.configure(this);
	}
	
	/**
	 * Conecta un grupo de componentes
	 * 
	 * @param components: Array de componentes a conectar
	 */
	public void connect(NbCmp[] components){
		for(int i=0; i<components.length; i++)
			connect(components[i]);
	}
	
	/**
	 * Envia la configuración de inicial de los componentes y sus valores iniciales de todos los componentes.
	 */
	public void setup(){
		NbBytes setup = null,
				cmdBytes = new NbBytes();
		
		for(NbCmp component : this){
			// Si estan cargadas las librerias necesarias
			// Se va agregando los bytes de configuración de cada dispositivo al vector.
			setup = component.getSetupBytes();
			if(setup != null)
				cmdBytes.addAll(setup);
		}
		
		cmdBytes.addAll(getSyncronizeBytes(true));
		cmdBytes.addAll(getSetupUserBytes());
		cmdBytes.add(NbDialect.MSG_FINISH);
		write(cmdBytes);
		play();
	}
	
	/**
	 * Envia los bytes para sincronizar las salidas que que hayan cambiado.
	 */
	protected void syncronizeOutputs(){
		syncronizeOutputs(false);
	}
	
	/**
	 * Envia los Bytes para sincronizar los componentes de salidas.
	 * 
	 * @param force		Si <code>force==true</code> también se incluirá el bytes para sincronizar de los componentes de salida
	 * 					que no hayan cambiado.
	 */
	protected void syncronizeOutputs(boolean force){
		mSyncWhenPlay = false;
		NbBytes cmdBytes = new NbBytes();
		cmdBytes.addAll(getUserBytes());
		cmdBytes.addAll(getSyncronizeBytes(force));
		cmdBytes.add(NbDialect.MSG_FINISH);
		write(cmdBytes);
	}
	
	public NbBytes getUserBytes(){
		return null;
	}
	
	/**
	 * Devuelve un vector con los bytes para sincronizar los componentes de salida que hayan cambiado de valor.
	 * Además se cambia el estado de los dispositivo de salida a sincronizado para evitará que sean tomados en cuenta en futuras
	 * sincronizaciones a menos que vuelvan a cambiar de valor.
	 * 
	 * @param force		Si force <code>force==true</code> entonces se incluirá tambien los dispositivos que no hayan cambiado
	 * 					de valor.
	 * @return			Vector con los bytes que deben ser enviados para sincronizar los componentes que hayan cambiado de
	 *					valor.
	 */
	protected NbBytes getSyncronizeBytes(boolean force){
		NbBytes data = new NbBytes();
		for(NbCmp device : this){
			// Solo tomar componentes de salida.
			if(device instanceof NbOut){
				// Si el dispositivo esta activo y estan cargadas las librerias necesarias.
				if(device.isActive()){
					data.addAll(((NbOut)device).getSyncronizeBytesIfChangeOrForce(force));
					((NbOut)device).synchronize();
				}
			}
			// Si el dispositivo es un entrada y cambió su estado de activación.
			if(device instanceof NbCmpIn && ((NbCmpIn)device).changedActiveState()){
				data.addAll(((NbCmpIn)device).getSyncronizeBytesActiveState());
				((NbCmpIn)device).synchronizeActiveState();
			}
		}
		return data;
	}
	
    /**
     * Permite enviar un mensaje a los manejadores para que funcione como un evento.
     * Para realizar el llamado de un evento del Sketch se envia un mensaje con el valor ordinal de la constante del
     * enviada. La lista de posibles mensajes que se puede enviar esta listada en la <code>SketchMessageEnum</code>.
     * 
     * @param msg 		Constante de <code>ComunicationMessageEnum</code> que se desea eviar.
     * 					El código de mensaje enviado será el valor ordinal de esta constante.
     * @param data1 	Primero valor de dato <code>int</code>. Su valor depende del valor de <code>msg</code>.
     * @param data1 	Segundo valor de dato <code>int</code>. Su valor depende del valor de <code>msg</code>.
     * @param dataObj 	Objeto enviado en el mensaje. Su contenido depende del valor de <code>msg</code>.
     * @param data 		<code>Bundle</code> con parámetros extras que se desee enviar en el mensaje.
     * 					Su contenido depende del valor de <code>msg</code>.
     */
    public void sendMessage(NbSketchMessageEnum msg, int data1, int data2, Object dataObj, Bundle data){
    	
    	// Recorrer cada manejador.
    	for(NbSketchHandler h : mHandlers){
    		
    		// obtener el mensaje con los parametros data1,data2 y dataObj.
        	Message m = h.obtainMessage(msg.ordinal(), data1, data2, dataObj);
        	
        	m.setData(data);	// Agregar el bunlde de data.
        	m.sendToTarget();	// Enviar el mensaje.
        	
    	}
    	
    }
	
	/**
	 * Agregar <code>SketchHandler</code> a la lista de manejadores.
	 * Permite agregar un nuevo <code>SketchHandler</code> a la lista de manejadores que son llamados cuando se genera
	 * un evento. El orden de llamado es el mimo orden en que se agregan los handleres a la instancia de esta clase.
	 * 
	 * @param handler 	<code>ComunicationHandler</code> que se agregará.
	 */
	public void addHandler(NbSketchHandler handler){
		mHandlers.add(handler);
	}
	
	/**
	 * Inicia la ejecución el Sketch.
	 */
	public void play(){
		pause = false;
		if(mSyncWhenPlay) syncronizeOutputs();
	}
	
	/**
	 * Pausa la ejecución del Sketch.
	 */
	public void pause(){
		pause = true;
	}
	
	/**
	 * Indica si el Skecth esta pausa o no.
	 * @return	Retorna <code>true</code> si el Skecth esta pausado de lo contrario <code>false</code>.
	 */
	public boolean isPaused(){
		return pause;
	}

	/**
	 * Indica si el Skecth esta corriendo o no.
	 * @return	Retorna <code>true</code> si el Skecth esta conrriendo de lo contrario <code>false</code>.
	 */
	public boolean isRuning(){
		return !pause;
	}
	
	/**
	 * Método llamado antes de comenzar a evaluar los datos recividos. La finalidad es procesar
	 * los datos entrates antes de ser evaluados por el sketchs, con la finalidad de interceptar
	 * los mensajes que se desee.
	 * 
	 * @param cmd						Comando recibido
	 * @param data 						Buffer con los datos de entrada.
	 * @throws NbBuffer.ReadException	Se generó un error cuando se intente obtener datos
	 * 									de data sin que estos existan.
	 * @return							El retorn indica si el comando se completo, si no se completo
	 * 									o si simplemente no se reconoció.
	 */
	protected CmdResult preproccess(int cmd, NbBuffer data) throws NbBuffer.ReadException{
		return CmdResult.UNKNOW;
	}
	
	/**
	 * Callback para comandos desconocidos. Esta destinado para dar respuesta a los comandos
	 * desconocidos que se reciban.
	 * 
	 * @param cmd						Comando desconocido recibido
	 * @param data						Buffer de bytes de la cola de mensaje
	 * @throws NbBuffer.ReadException	Se generó un error cuando se intente obtener datos
	 * 									de data sin que estos existan.
	 * @return							Retorna si se reconoció y proceso el comando satisfactoriamente.
	 */
	public boolean unknowCmd(int cmd, NbBuffer data) throws NbBuffer.ReadException{
		return false;
	}
	
	/**
	 * Método que se ejecuta cada vez despues de terminar el procesamiento de la cola de mensajes
	 * y antes de enviar la respuesta. 
	 */
	public void loop(){}
	
	/**
	 * Se encarga de procesar los bytes recibidos por la comunicación.
	 * Este método se se tomar los bytes del buffer y procesaros. Deberá retornar <code>true</code> si culmina el
	 * procesamiento exitosamente. En el caso de que el buffer se haya quedado vacío antes de terminar el procesamiento
	 * (hicieron faltas bytes) entonces este método debera retornar <code>false</code>.
	 * 
	 * @param data	Buffer de entrada.
	 * @return		Retorna <code>true</code> si completó el procesamiento correctamente de lo contrario retorna falso.
	 */
	public boolean received(NbBuffer data){
		
		long value;
		int id, pin, cmd;
		boolean state;
		NbCmp component;
		NbCmpVector components;
		Class <?> cmpClass;
		NbSketchMessageEnum msg;
		
		// Lee el comando.
		try{
			
			cmd = data.read();
			
			// Evaluar la
			CmdResult cmdRet = preproccess(cmd, data);
			
			// Si el comando culminó satisfactoriamente retornar verdadero.
			if(cmdRet == CmdResult.COMPLETE)
				return true;
				
			// Si el comando no se completó retornara falso.
			if(cmdRet == CmdResult.INCOMPLETE)
				return false;
			
			// El comando es desconocido para preprocess.
			
			switch (cmd) {
			
				// Indica el final del los datos recividos.
				case NbDialect.MSG_FINISH:
					
					loop();
					// Si el Sketch no esta pausado entonces se deberá sincronizar las componentes de salidas.
					if(!isPaused())
						syncronizeOutputs();
					else
						mSyncWhenPlay = true;
					
					return true;
				
				// Se envio un cambio de estado de un componente digital/analogico:
				//	byte 0: pin del dispositivo.
				//  byte 1: stado enviado (0 == false - !0 == true).
				case NbDialect.MSG_SET_STATE_DIGITAL:
				case NbDialect.MSG_SET_STATE_ANALOG:
					
					cmpClass = null;
					msg = null;

					// Dependiendo del cmd se debe indicar el tipo de componente a buscar y el mensaje a enviar
					switch (cmd) {
						
						case NbDialect.MSG_SET_STATE_DIGITAL:
							cmpClass = NbDigital.class;
							msg = NbSketchMessageEnum.CHANGED_DIGITAL_STATE;
							break;
							
						case NbDialect.MSG_SET_STATE_ANALOG:
							cmpClass = NbAnalog.class;
							msg = NbSketchMessageEnum.CHANGED_ANALOG_STATE;
							break;
							
					}
					
					pin = data.read();	
					state = data.read() != 0;
				
					// Buscar los entradas digitales relacionadas al pin.
					components = getComponentsByPin(pin, cmpClass);
					components.setState(state);
					
					sendMessage(msg, pin, state? 1 : 0, components, null);
					
					return true;
				
//				// Se envio un cambio de estado de un componente objeto:
//				//	int 0: id del dispositivo.
//				//  byte 1: stado enviado (0 == false - !0 == true).
//				case NbDialect.setState_object:
//						
//						// Leer parámetros del comando
//						id = data.readInt();
//						state = data.read() != 0;
//					
//						// Buscar el componente por el ID
//						component = getComponentById(id);
//						
//						// Cambiar el estado del dispositivo si se encontro el dispositivo
//						if(component != null){
//							// Asignar el estado
//							component.setState(state);
//							sendMessage(NbSketchMessageEnum.CHANGED_OBJECT_STATE, id, state? 1 : 0, component, null);
//						}else{
//							sendMessage(NbSketchMessageEnum.OBJECT_NO_FOUND, id, -1, null, null);
//						}
//						
//						return true;
					
				// Cambio una entrada digital/analógica:
				//	byte 0: Pin que cambió.
				//  byte 1: Nuevo valor.
				case NbDialect.MSG_DIGITAL_IN_READ:
				case NbDialect.MSG_ANALOG_IN_READ:
					
					cmpClass = null;
					msg = null;
					
					// Leer parámetros del comando
					pin = data.read();
					value = 0;
					
					// Dependiendo del cmd se debe indicar el tipo de componente a buscar y el mensaje a enviar
					switch (cmd) {
						
						case NbDialect.MSG_DIGITAL_IN_READ:
							cmpClass = NbCmpInDigital.class;
							msg = NbSketchMessageEnum.CHANGED_DIGITAL_VALUE;
							value = data.read()!=0? 1 : 0;
							break;
							
						case NbDialect.MSG_ANALOG_IN_READ:
							cmpClass = NbCmpInAnalog.class;
							msg = NbSketchMessageEnum.CHANGED_ANALOG_VALUE;
							value = data.readInt();
							break;
							
					}
					
					// Buscar los entradas digitales relacionadas al pin.
					components = getComponentsByPin(pin, cmpClass);
					components.setValue(value);
					
					sendMessage(msg, pin, (int)value, components, null);
					
					return true;
				
				// Cambio una entrada objeto:
				//	int  0: Id del componente que cambio.
				//  long 1: valor de la lectura.
				//  bytes...: data extra correspondiente al dispositivo.
				case NbDialect.MSG_OBJECT_CMD:
					id = data.readInt();
				
					// Buscar el componente por el ID
					component = getComponentById(id);
	
					// Cambiar el valor del componente si se encontro el dispositivo
					if(component != null){
						// Dejar que el componente realize la lectura de la data extra.
						((NbCmpIn)component).readData(data);
						sendMessage(NbSketchMessageEnum.CHANGED_OBJECT_VALUE, id, -1, component, null);
					}else{
						sendMessage(NbSketchMessageEnum.OBJECT_NO_FOUND, id, -1, null, null);
					}
					
					return true;
					
				default:
					
					// Procesar comando desconocido
					return unknowCmd(cmd, data);
					
			}
				
		}catch (NbBuffer.ReadException e) {
			return false;	// Indica que no se culminó la lectura
		}
		
	}
	
}
