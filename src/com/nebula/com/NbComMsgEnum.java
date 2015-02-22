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
 * Enumeración que lista los diferentes mensajes con los que se puede
 * generar en una comunicación.
 * 
 */
public enum NbComMsgEnum{
	
	/* ====================================================
	 * Mensajes generales.
	 * ====================================================
	 * */	
	STATE_CHANGED,      // Indica un cambio de estado de la conexion
	CONNECTING,			// Inició el inteneo de conexión
	CONNECTED,			// Conexión
	DATA_WRITED,		// Se enviaron datos
	DATA_RECEIVED,		// Se recibieron datos
	DISCONNECT,			// Desconexión
	CONNECTION_FAILED,	// Falló la conexión
	CONNECTION_LOST,	// Conexión perdida
	ERROR,				// Se generó un error en la conexión
	INIT_CONNECTION,	// Inicio la conexión. Marca la primera iteración de los
						// hilos manejadores de las comunicaciones
	
	/* ====================================================
	 * Mensajes para conexiónes bluetooth.
	 * ====================================================
	 * */
	BT_FIRST_MESSAGE,			// Se recibió el primer mensaje de un dispositivo bluetooth
	BT_ANY_PAIRED_DEVICE,		// Existe al menos un dispositivo Bluetooth
	BT_NO_PAIRED_DEVICES,		// No existen dispositivos Bluetooth pareados
	BT_DO_DISCOVERY_START,		// Inición el escaneo de dispositivos Bluetooth
	BT_DO_DISCOVERY_FINISH,		// Finalizó un escaneo de despositivos Bluetooth
	BT_NO_NEW_DEVICES_FOUND,	// No se encontraton dispositivos Bluetooth durante el escaneo
	BT_ADD_PAIRED_DEVICE,		// Se encontró un dispositivo Bluetooth pareado
	BT_ADD_NEW_DEVICE,			// Se encontró un dispositivo Bluetooth durante el escaneo
	BT_SELECTED_DEVICE			// Se selecionó un dispositivo bluetooth para la conectarse
	
}
