/* ========================================================================
 * Nebula Android Lib: NbComMsgEnum v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula.com;

/**
 * Enumeraci�n que lista los diferentes mensajes con los que se puede
 * generar en una comunicaci�n.
 * 
 */
public enum NbComMsgEnum{
	
	/* ====================================================
	 * Mensajes generales.
	 * ====================================================
	 * */	
	STATE_CHANGED,      // Indica un cambio de estado de la conexion
	CONNECTING,			// Inici� el inteneo de conexi�n
	CONNECTED,			// Conexi�n
	DATA_WRITED,		// Se enviaron datos
	DATA_RECEIVED,		// Se recibieron datos
	DISCONNECT,			// Desconexi�n
	CONNECTION_FAILED,	// Fall� la conexi�n
	CONNECTION_LOST,	// Conexi�n perdida
	ERROR,				// Se gener� un error en la conexi�n
	INIT_CONNECTION,	// Inicio la conexi�n. Marca la primera iteraci�n de los
						// hilos manejadores de las comunicaciones
	
	/* ====================================================
	 * Mensajes para conexi�nes bluetooth.
	 * ====================================================
	 * */
	BT_FIRST_MESSAGE,			// Se recibi� el primer mensaje de un dispositivo bluetooth
	BT_ANY_PAIRED_DEVICE,		// Existe al menos un dispositivo Bluetooth
	BT_NO_PAIRED_DEVICES,		// No existen dispositivos Bluetooth pareados
	BT_DO_DISCOVERY_START,		// Inici�n el escaneo de dispositivos Bluetooth
	BT_DO_DISCOVERY_FINISH,		// Finaliz� un escaneo de despositivos Bluetooth
	BT_NO_NEW_DEVICES_FOUND,	// No se encontraton dispositivos Bluetooth durante el escaneo
	BT_ADD_PAIRED_DEVICE,		// Se encontr� un dispositivo Bluetooth pareado
	BT_ADD_NEW_DEVICE,			// Se encontr� un dispositivo Bluetooth durante el escaneo
	BT_SELECTED_DEVICE			// Se selecion� un dispositivo bluetooth para la conectarse
	
}
