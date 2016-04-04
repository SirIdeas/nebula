/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.com;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;

import android.os.Handler;
import android.os.Message;

/**
 * Manejador de eventos de comunicación.
 * Recibe los mensajes generados en la comunicación para luego recuperar los argumentos adecuadamente y llamar el método
 * correspondiente.
 */
public class NbComHandler extends Handler{
	
	/**
	 * Implementación de <code>handleMessage</code>.
	 * Llama la funcion correspondiente dependiendo del mensaje recibido. 
	 */
	@Override
    public void handleMessage(Message msg) {
		
		if(msg.what == NbComMsgEnum.STATE_CHANGED.ordinal()){
			
			// Cambió el estado de la conexión
			stateChanged(NbComStateEnum.values()[msg.arg1]);
			
		}else if(msg.what == NbComMsgEnum.CONNECTING.ordinal()){
			
			// Inició el intento de conexión
			connecting(msg.obj);
			
		}else if(msg.what == NbComMsgEnum.CONNECTED.ordinal()){
			
			// Se conectó al accesorio
			connected(msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DATA_WRITED.ordinal()){
		 
			// Se escribió en el buffer de salida.
			// Los Bytes enviados se reciven como un VectorBytes en el parámetro objeto del mensaje.
			dataWrite((NbBytes)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DATA_RECEIVED.ordinal()){
			
			// Se recibió datos desde el dispositivo bluetooth.
			// Los bytes en el buffer de entrada se reciben en el parámetro objeto del mensaje.
			dataReceived((NbBuffer)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DISCONNECT.ordinal()){
			
			// Se desconectó del dispositivo bluetooth.
			disconnect();
			
		}else if(msg.what == NbComMsgEnum.CONNECTION_FAILED.ordinal()){
			
			// No se pudo conectar al dispositivo bluetooth.
			connectionFailed();
			
		}else if(msg.what == NbComMsgEnum.CONNECTION_LOST.ordinal()){
			
			// Se perdió la conexión con el dispositivo bluetooth.
			connectionLost();
			
		}else if(msg.what == NbComMsgEnum.ERROR.ordinal()){
			
			// Se generó el error.
			// El error generado es recivido en el parametro entero 1.
			error(NbComErrorEnum.values()[msg.arg1]);
			
		}else if(msg.what == NbComMsgEnum.INIT_CONNECTION.ordinal()){
			
			// Indica que se debe enviar la configuración.
			initConnection();
			
		}
		
    }

	/**
	 * Método para cuando se escriba en el buffer de salida.
	 * 
	 * @param data	<code>VectorBytes</code> con los bytes enviados.
	 */
	public void dataWrite(NbBytes data){}
	
	/**
	 * Método para cuando reciva datos desde el dispositivo bluetooth.
	 * 
	 * @param data	<code>BufferList</code> con los bytes que existente actualmente en el buffer.
	 */
	public void dataReceived(NbBuffer data){}
	
	/**
	 * Cambió el estado de la conexión
	 */
	public void stateChanged(NbComStateEnum state){}
	
	/**
	 * Método para cuando se inicie la conexion.
	 */
	public void connecting(Object obj){}
	
	/**
	 * Método para cuando conecte stisfactoriamente.
	 */
	public void connected(Object obj){}
	
	/**
	 * Método para cuando se desconecte el dispositivo bluetooth.
	 */
	public void disconnect(){}
	
	/**
	 * Método para cuando falle el intento de conexión con el dispositivo bluetooth.
	 */
	public void connectionFailed(){}
	
	/**
	 * Método para cuando se pierda la conexión con el dispositivo bluetooth.
	 */
	public void connectionLost(){}
	
	/**
	 * Método para cuando se genere un error.
	 *  
	 * @param error Valor ordinal dentro de <code>NbComErrorEnum</code> del error generado.
	 */
	public void error(NbComErrorEnum error){}
	
	/**
	 * Métod para cuando se inicia la conexión.
	 */
	public void initConnection(){}
	
}