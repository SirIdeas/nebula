/**
 * Proyecto N�bula
 *
 * @author Alex J. Rond�n <arondn2@gmail.com>
 * 
 */

package com.nebula.com;

/**
 * Enumeraci�n que lista los diferentes estados que pueden tener conexiones.
 * 
 * @version 1.0
 */
public enum NbComStateEnum{
	
	STATE_DISCONNECTED,	// No conectado - Sin conexi�n - Sin estado.
	STATE_CONNECTED,	// Conectado.
	STATE_CONNECTING	// Estableciendo conexi�n.
	
}