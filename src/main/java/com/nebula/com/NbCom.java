/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.com;

import java.util.Vector;

import com.nebula.NbBytes;
import com.nebula.NbTrace;
import com.nebula.performer.NbPerformer;
import com.nebula.performer.NbPerformerHandler;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Message;

/**
 * Clase para comunicación Bluetooth.
 * Superclase para las implementaciones básicas de la comunicación con microcontroladores. En esta clases se implementan gran
 * parte de los métodos para comunicarse con microcontroladores. Dispone de una lista de manejadores de eventos, métodos ya
 * tributos para consultar el estado, Hilos manejadores de conexión y métodos de lectura y escritura.
 * 
 */
public class NbCom {
	
    /**
     * ArrayList de Handleres controladores de comunicaión.
     * Estos son los manejadores que reciben los mensajes enviados por la comunicación.
     */
	private final Vector<NbComHandler> mHandlers = new Vector<NbComHandler>();
	
	/**
	 * Estado de la comunicación.
	 * Es del tipo <code>ComunicationStateEnum</code> que contiene las constantes que identifica cada uno de los posibles
	 * estados en que se puede encontrar una conexión.
	 */
	private NbComStateEnum mState = NbComStateEnum.STATE_DISCONNECTED;
	
	/**
	 * Hilo que se ejecuta mientras que la conexión esta activa.
	 */
	private NbConnectedThread mConnectedThread;
	
	/**
	 * 
	 */
    private NbPerformerHandler mPerformerHandler = null;
	
	/**
	 * Tiempo de espera en milisegundos entre el envio de un paquete y otro.
	 * Si es igual a 0, se enviaran todos los paquetes en un solo
	 */
	private long mWait = 0;
	
	
	public NbCom(){
		this(0);
	}
	/**
	 * Constructor principal de la clase.
	 * 
	 * @param wait	Tiempo de espera entre envios de paquetes (en milisegundos)
	 */
	public NbCom(long wait){
		mWait = wait;
	}
	
	/**
	 * Linkea un interprete a la comunicacion
	 * 
	 * @param com	Instancia de comunicación  a asignar.
	 */
	public void setPerformer(NbPerformer performer){
		if(mPerformerHandler == null){
			mPerformerHandler = new NbPerformerHandler(performer);
			addHandler(mPerformerHandler);
		}else{
			mPerformerHandler.setPerformer(performer);
		}
	}
	
	/**
	 * Agregar <code>ComunicationHandler</code> a la lista de manejadores.
	 * Permite agregar un nuevo <code>ComunicationHandler</code> a la lista de manejadores que son llamados cuando se genera
	 * un evento. El orden de llamado es el mimo orden en que se agregan los handleres a la instancia de esta clase.
	 * 
	 * @param handler 	<code>ComunicationHandler</code> que se agregará.
	 */
	public void addHandler(NbComHandler handler){
		mHandlers.add(handler);
	}
	
	/**
	 * Indica si la instancia de la conexión esta activa.
	 * 
	 * @return 	Retorna <code>true</code> si esta conectado <code>false</code> de lo contrario.
	 */
	public boolean isConnect(){
    	return mState == NbComStateEnum.STATE_CONNECTED;
    }
	
	/**
	 * Indica si la instancia está desconectada
	 * 
	 * @return 	Retorna <code>true</code> si esta conectado <code>false</code> de lo contrario.
	 */
	public boolean isConnecting(){
    	return mState == NbComStateEnum.STATE_CONNECTING;
    }
	
	/**
	 * Indica si la instancia está desconectada
	 * 
	 * @return 	Retorna <code>true</code> si esta conectado <code>false</code> de lo contrario.
	 */
	public boolean isDisconnect(){
    	return mState == NbComStateEnum.STATE_DISCONNECTED;
    }
    
	/**
	 * Obtiene el estado de la conexión.
	 * 
	 * @return	Retorna el valor de la conexión. 
	 */
    protected NbComStateEnum getState(){
    	return mState;
    }
    
    /**
     * Cambia el estado de la conexión.
     * 
     * @param state	Estado al que se cambiará la conexión.
     */
    public void setState(NbComStateEnum state){
    	
    	// Enviar mensaje si cambio el estado
    	if(mState != state){
    		
    		// Cambiar estado
    		mState = state;
    		
    		// Enviar un mensaje indicando el cambio de estado
    		sendMessage(NbComMsgEnum.STATE_CHANGED, state.ordinal(), -1, null, null);
    		
    	}
    	
    }
    
    /**
     * Devuelve el tiempo en milisegundos de espera entre el envio de un paqute y otro.
     */
    public long getWait(){
    	return mWait;
    }
	
    /**
     * Permite enviar un mensaje a los manejadores para que funcione como un evento.
     * Para realizar el llamado de un evento de la comunicación se envia un mensaje con el valor ordinal de la constante del
     * enviada. La lista de posibles mensajes que se puede enviar esta listada en la <code>ComunicationMessageEnum</code>.
     * 
     * @param msg 		Constante de <code>ComunicationMessageEnum</code> que se desea eviar.
     * 					El c�digo de mensaje enviado ser� el valor ordinal de esta constante.
     * @param data1 	Primero valor de dato <code>int</code>. Su valor depende del valor de <code>msg</code>.
     * @param data1 	Segundo valor de dato <code>int</code>. Su valor depende del valor de <code>msg</code>.
     * @param dataObj 	Objeto enviado en el mensaje. Su contenido depende del valor de <code>msg</code>.
     * @param data 		<code>Bundle</code> con par�metros extras que se desee enviar en el mensaje.
     * 					Su contenido depende del valor de <code>msg</code>.
     */
    public void sendMessage(NbComMsgEnum msg, int data1, int data2, Object dataObj, Bundle data){
    	
    	// Recorrer cada manejador.
    	for(NbComHandler h : mHandlers){
    		
    		// obtener el mensaje con los parametros data1,data2 y dataObj.
        	Message m = h.obtainMessage(msg.ordinal(), data1, data2, dataObj);
        	
        	m.setData(data);	// Agregar el bunlde de data.
        	m.sendToTarget();	// Enviar el mensaje.
        	
    	}
    	
    }
    
    /**
     * M�todo que se llama cuando falla el intento de conexión.
     * Se encarga de relizar el trazado correspondiente, realizar el llamado del evento y cambiar el estado del disposiivo a
     * desconectado.
     */
    public void connectionFailed() {
    	NbTrace.d(this, "connectionFailed()");
    	
    	// Cambiar estado a desconectado.
        setState(NbComStateEnum.STATE_DISCONNECTED);
    	
    	// Enviar mensaje.
    	sendMessage(NbComMsgEnum.CONNECTION_FAILED, -1, -1, null, null);
        
    }
    
    /**
     * Cancela la conexión actual.
     * Este m�todo permite cancelar la conexión actual. Si no esta conectado y existe un intendo de conexión entonces tambien
     * es cancelado. Tambien se cambia el estado de la conexión y se genera el evento correspondiente.
     */
    public void disconnect(){
    	NbTrace.d(this, "disconnect()");
    }
    
    /**
     * Inicia una conexión.
     * 
     */
    public void connect(){
    	NbTrace.d(this, "connect()");
    }

    /**
     * M�todo que es llamado cuando se pierde la conexión.
     * Se encarga de relizar el trazado correspondiente, realizar el llamado del evento y cambiar el estado del disposiivo a
     * desconectado.
     */
    public void connectionLost() {
    	NbTrace.d(this, "connectionLost()");
    	
    	// Cambiar estado a desconectado.
        setState(NbComStateEnum.STATE_DISCONNECTED);
    	
    	// Enviar mensaje.
    	sendMessage(NbComMsgEnum.CONNECTION_LOST, -1, -1, null, null);
        
    }
    
    /**
     * Iniciar un nuevo <code>BaseConnectedThread</code>.
     * Este m�todo se encarga simplemente de iniciar un nuevo <code>BaseConnectedThread</code>. Este tipo de hilos son
     * ejecutados mientras se mantiene la conexión activa y encargan de leer los datos enviados y escribir datos de salida. 
     * 
     * @param connectedThread 	Hilo que se desea iniciar.
     */
    protected void startNewConnectedThread(NbConnectedThread connectedThread){
    	NbTrace.d(this, "startNewConnectedThread()");
    	
    	mConnectedThread = connectedThread;
    	mConnectedThread.start();
    }
    
    /**
     * Cancela el <code>BaseConnectedThread</code>.
     * Este m�todo es llamado cuando se desea cancelar y resetear el <code>BaseConnectedThread</code> ya sea por una
     * desconexión o por parte de la aplicaci�n.
     */
    protected void cancelConnectedThread(){
    	NbTrace.d(this, "cancelConnectedThread()");
    	
        // Cancela cualquier hilo actualmente corriendo una connexi�n.
        if (mConnectedThread != null) {mConnectedThread.cancel(); mConnectedThread = null;}
    	
    }
    
    /**
     * Procesamiento de los mensajes recibidos por los <code>BroadcastReciver</code>
     * que se puedan asignar a la instancia de la comunicación.
     * Recibe los mismo par�metros que un corriente <code>BroadcastReceiver</code>. Este m�todo se debe sobreescribir en las
     * especificaciones de esta clase que requieran BroadcastReceiver para permitir una mejor organizaci�n.
     * 
     * @param context	Par�metro recibido por el m�todo <code>BroadcastReceiver#onRecibe</code>.
     * @param intent 	Par�metro recibido por el m�todo <code>BroadcastReceiver#onRecibe</code>.
     */
    public void onReciveBroadcastReciver(Context context, Intent intent){}
    
    /**
     * Enviar un array de bytes por el buffer de salida.
     * 
     * @param out Array de bytes (<code>byte[]</code>) que se enviar�n.
     */
    public void write(byte[] out) {
    	if(out.length==0) return;
    		
    	// Crear un hilo de conexión temporal.
        NbConnectedThread r;
        
        // Sincronizar la copia de BaseConnectedThread.
        synchronized (this) {
        	// Si la conexión no está activa, entonces no se puede escribir datos.
            if (!isConnect()) return;
            r = mConnectedThread;
        }
        
        // La escritura el ebuffer de salida se realiza dentro de Thread.
        r.write(out);
        
        // Se genera un evento que indica que se escribieron los datos.
        sendMessage(NbComMsgEnum.DATA_WRITED, -1, -1, new NbBytes(out), null);
        
    }
    
    /**
     * Permite enviar un <code>VectorBytes</code> al buffer de salida. 
     * Esta funci�n solo se encarga de convertir el <code>VectorBytes</code> en un array de bytes (<code>byte[]</code>) para
     * luego realizar el env�o.
     * 
     * @param data	VectorBytes que contiene la lista de bytes a enviar.
     */
    public void write(NbBytes data) {
    	write(data.getArray());
    }
    
    /**
     * Clase para implementar los <code>BroadcastReceiver</code> que enviar�n mensajes a la instancias de la comunicación.
     * 
     */
    public final class BaseComunicationBroadcastReceiver extends BroadcastReceiver {
    	
    	/**
    	 * Instancia de comunicación que recibir� los mensajes.
    	 */
    	private NbCom mCom;
    	
    	/**
    	 * Constructor.
    	 * En el constructor se debe indicar la instancia de comunicación que recibir� los mensajes de
    	 * <code>BroadcastReceiver</code>.
    	 * 
    	 * @param com 	Instancia de <code>BaseComunication</code> que recibir� los mensajes.
    	 */
    	public BaseComunicationBroadcastReceiver(NbCom com){
    		mCom = com;
    	}
    	
    	/**
    	 * Procesamiento del mensaje.
    	 * Cuando el <code>BroadcastReceiver</code> recibe un mensaje este se encargar de desplazar el mensaje a la
    	 * comunicación.
    	 */
        @Override
        public void onReceive(Context context, Intent intent) {
            mCom.onReciveBroadcastReciver(context, intent);
        }
        
    }
	
}
