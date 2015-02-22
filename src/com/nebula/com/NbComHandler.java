/* ========================================================================
 * Nebula Android Lib: NbComHandler v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula.com;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;

import android.os.Handler;
import android.os.Message;

/**
 * Manejador de eventos de comuniaci�n.
 * Recibe los mensajes generados en la comunicaci�n para luego recuperar los argumentos adecuadamente y llamar a la funcci�n
 * correspondiente.
 */
public class NbComHandler extends Handler{
	
	/**
	 * Implementaci�n de <code>handleMessage</code>.
	 * Llama la funcion correspondiente dependiendo del mensaje recibido. 
	 */
	@Override
    public void handleMessage(Message msg) {
		
		if(msg.what == NbComMsgEnum.STATE_CHANGED.ordinal()){
			
			// Cambi� el estado de la conexi�n
			stateChanged(NbComStateEnum.values()[msg.arg1]);
			
		}else if(msg.what == NbComMsgEnum.CONNECTING.ordinal()){
			
			// Inici� el intento de conexi�n
			connecting(msg.obj);
			
		}else if(msg.what == NbComMsgEnum.CONNECTED.ordinal()){
			
			// Se conect� al accesorio
			connected(msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DATA_WRITED.ordinal()){
		 
			// Se escribi� en el buffer de salida.
			// Los Bytes enviados se reciven como un VectorBytes en el par�metro objeto del mensaje.
			dataWrite((NbBytes)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DATA_RECEIVED.ordinal()){
			
			// Se recibi� datos desde el dispositivo bluetooth.
			// Los bytes en el buffer de entrada se reciben en el par�metro objeto del mensaje.
			dataReceived((NbBuffer)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.DISCONNECT.ordinal()){
			
			// Se desconect� del dispositivo bluetooth.
			disconnect();
			
		}else if(msg.what == NbComMsgEnum.CONNECTION_FAILED.ordinal()){
			
			// No se pudo conectar al dispositivo bluetooth.
			connectionFailed();
			
		}else if(msg.what == NbComMsgEnum.CONNECTION_LOST.ordinal()){
			
			// Se perdi� la conexi�n con el dispositivo bluetooth.
			connectionLost();
			
		}else if(msg.what == NbComMsgEnum.ERROR.ordinal()){
			
			// Se gener� el error.
			// El error generado es recivido en el parametro entero 1.
			error(NbComErrorEnum.values()[msg.arg1]);
			
		}else if(msg.what == NbComMsgEnum.INIT_CONNECTION.ordinal()){
			
			// Indica que se debe enviar la configuraci�n.
			initConnection();
			
		}
		
    }

	/**
	 * Funci�n para cuando se escriba en el buffer de salida.
	 * 
	 * @param data	<code>VectorBytes</code> con los bytes enviados.
	 */
	public void dataWrite(NbBytes data){}
	
	/**
	 * Funci�n para cuando reciva datos desde el dispositivo bluetooth.
	 * 
	 * @param data	<code>BufferList</code> con los bytes que existente actualmente en el buffer.
	 */
	public void dataReceived(NbBuffer data){}
	
	/**
	 * Cambi� el estado de la conexi�n
	 */
	public void stateChanged(NbComStateEnum state){}
	
	/**
	 * Funci�n para cuando se inicie la conexion.
	 */
	public void connecting(Object obj){}
	
	/**
	 * Funci�n para cuando conecte stisfactoriamente.
	 */
	public void connected(Object obj){}
	
	/**
	 * Funci�n para cuando se desconecte el dispositivo bluetooth.
	 */
	public void disconnect(){}
	
	/**
	 * Funci�n para cuando falle el intento de conexi�n con el dispositivo bluetooth.
	 */
	public void connectionFailed(){}
	
	/**
	 * Funci�n para cuando se pierda la conexi�n con el dispositivo bluetooth.
	 */
	public void connectionLost(){}
	
	/**
	 * Funci�n para cuando se genere un error.
	 *  
	 * @param error Valor ordinal dentro de <code>NbComErrorEnum</code> del error generado.
	 */
	public void error(NbComErrorEnum error){}
	
	/**
	 * Funci�n para cuando se inicia la conexi�n.
	 */
	public void initConnection(){}
	
}