/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.com;

/**
 * Enumeración que lista los diferentes estados que pueden tener conexiones.
 * 
 * @version 1.0
 */
public enum NbComStateEnum{
	
	STATE_DISCONNECTED,	// No conectado - Sin conexión - Sin estado.
	STATE_CONNECTED,	// Conectado.
	STATE_CONNECTING	// Estableciendo conexión.
	
}