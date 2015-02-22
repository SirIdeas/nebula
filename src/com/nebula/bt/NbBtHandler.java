/* ========================================================================
 * Nebula Android Lib: NbBtHandler v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula.bt;

import com.nebula.com.NbComHandler;
import com.nebula.com.NbComMsgEnum;

import android.annotation.SuppressLint;
import android.bluetooth.BluetoothDevice;
import android.os.Message;

/**
 * Manejador de eventos de comuniaci�n por Bluetooth.
 * Recibe los mensajes generados en la comunicaci�n Bluetooth para luego recuperar los argumentos adecuadamente y llamar a la
 * funcci�n correspondiente.
 * 
 */
@SuppressLint("HandlerLeak")
public class NbBtHandler extends NbComHandler{
	
	/**
	 * Implementaci�n de <code>handleMessage</code>.
	 * Llama la funcion correspondiente dependiendo del mensaje recibido. 
	 */
	@Override
    public void handleMessage(Message msg) {
    	
		if(msg.what == NbComMsgEnum.BT_ANY_PAIRED_DEVICE.ordinal()){

			// Existe al menos un dispositivo bluetooth.
			anyPairedDevices();
			
		}else if(msg.what == NbComMsgEnum.BT_NO_PAIRED_DEVICES.ordinal()){

			// No existen dispositivos bluetooth pareados.
			noPairedDevices();
			
		}else if(msg.what == NbComMsgEnum.BT_DO_DISCOVERY_START.ordinal()){

			// Inici� un escaneo de dispositivos bluetooth.
			doDiscoveryStart();
			
		}else if(msg.what == NbComMsgEnum.BT_DO_DISCOVERY_FINISH.ordinal()){

			// Finaliz� el escaneo de despositivos bluetooth.
			doDiscoveryFinished();
			
		}else if(msg.what == NbComMsgEnum.BT_NO_NEW_DEVICES_FOUND.ordinal()){
			
			// No se encontraron dispositivos bluetooth.
			noNewDevicesFound();
			
		}else if(msg.what == NbComMsgEnum.BT_ADD_PAIRED_DEVICE.ordinal()){

			// Para cuando se este explorando un dispositivo bluetooth.
			// El dispositivo bluetooth explorado es recivido por el par�metro objeto del mensaje.
			addPairedDevices((BluetoothDevice)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.BT_ADD_NEW_DEVICE.ordinal()){
			
			// Se encontr� un dispositivo bluetooth.
			// El dispositivo bluetooth encontrado es recivido por el par�metro objeto del mensaje.
			addNewDevices((BluetoothDevice)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.BT_SELECTED_DEVICE.ordinal()){

			// Se seleccion� un dispositivo bluetooth.
			// El dispositivo bluetooth selecionado es recivido por el par�metro objeto del mensaje.
			selectedDevice((BluetoothDevice)msg.obj);
			
		}else if(msg.what == NbComMsgEnum.BT_FIRST_MESSAGE.ordinal()){
			
			// Se recivi� el primer mensaje del dispositivo bluetooth.
			firstMessage();
			
		}else{
			
			// No correpsonde por espec�fico por comunicaci�n por Bluetooth.
			super.handleMessage(msg);
			
		}
		
    }
	
	/**
	 * Funci�n para cuando se encuentre al menos uno dispositivo bluetooth.
	 */
	public void anyPairedDevices(){}
	
	/**
	 * Funci�n para cuando no se encuentren dispositos pareados.
	 */
	public void noPairedDevices(){}
	
	/**
	 * Funci�n para cuando se inicie el escaneo de despositivos.
	 */
	public void doDiscoveryStart(){}
	
	/**
	 * Funci�n para cuando se termine de escaner los dispositivos bluetooth.
	 */
	public void doDiscoveryFinished(){}
	
	/**
	 * Funci�n para cuando se se encuentre ning�n dispositivo bluetooth.
	 */
	public void noNewDevicesFound(){}
	
	/**
	 * Funci�n para cuando se reciba el primer mensaje.
	 */
	public void firstMessage(){}
	
	/**
	 * Funci�n para recibir cada uno de los dispositivos bluetooth pareados.
	 * 
	 * @param device	Instancia del dispositivo bluetooth en exploraci�n.
	 */
	public void addPairedDevices(BluetoothDevice device){}
	
	/**
	 * Funci�n para recibir cada uno de los dispositivos bluetooth encontrados.
	 * 
	 * @param device	Instancia del dispositivo bluetooth en exploraci�n.
	 */
	public void addNewDevices(BluetoothDevice device){}

	/**
	 * Funci�n para cuando se selecione al menos un dispositivo bluetooth.
	 * 
	 * @param device	Instancia del dispositivo bluetooth selecionado.
	 */
	public void selectedDevice(BluetoothDevice device){}
	
}