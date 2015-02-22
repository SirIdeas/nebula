/* ========================================================================
 * Nebula Android Lib: NbComErrorEnum v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula.com;

/**
 * Enumeración que lista los diferentes errores que se pueden generar en los
 * diferentes tipos de conexión disponibles.
 * 
 */
public enum NbComErrorEnum{
	
	/* ====================================================
	 * Errores generales de comunicación.
	 * ====================================================
	 * */
	CANT_CLOSE_OUTPUT_STREAM,	// No se pudo cerrar el stream de salida.
	CANT_CLOSE_INPUT_STREAM,	// No se pudo cerrar el stream de entrada.

	/* ====================================================
	 * Errores generales de comunicación Bluetooth.
	 * ====================================================
	 * */
	CANT_CREATE_SOCKET,		// No se pudo crear el socket.
	CANT_CREATE_STREAM,		// No se pudo crear los stream.
	CANT_CLOSE_SOCKET,		// No se pudo cerrar el socket.
	
	/* ====================================================
	 * Errores generales de comunicación con accesorios.
	 * ====================================================
	 * */
	CANT_GET_PARCEL_FILE_DESCRIPTOR,	// No se pudo obtener el descriptor del accesorio
	CANT_CLOSE_PARCEL_FILE_DESCRIPTOR	// No se pudo cerrar el descriptor de archivo.
	
}
