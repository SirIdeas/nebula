package com.nebula.sketch;

/**
 * Interfaz que contiene las constantes de todos los posibles mensajes estándares que se puedan enviar por comunicación.
 * 
 * @version 1.0
 *
 */
public interface NbDialect{
	
	/* ============================================================
	 * Mensajes generales.
	 * ============================================================
	 * */
	
	/**
	 * Indica la finalización del envio o recivo.
	 */
	public static final byte MSG_FINISH 				= 0x01;
	
	/**
	 * Configurar una entrada digital.
	 */
	public static final byte MSG_DIGITAL_IN_CONF 		= 0x02;
	
	/**
	 * Configurar un pin como salida digital o PWM.
	 */
	public static final byte MSG_PIN_MODE_OUT 			= 0x03;
	
	/**
	 * Escribir una salida PWM.
	 */
	public static final byte MSG_ANALOG_WRITE 			= 0x04;
	
	/**
	 * Configurar una entrada analógica.
	 */
	public static final byte MSG_ANALOG_IN_CONF 		= 0x05;
	
	/**
	 * Se recibió el cambio de valor de una entrada analógica.
	 */
	public static final byte MSG_ANALOG_IN_READ 		= 0x06;
	
	/**
	 * Escribir una salida digital.
	 */
	public static final byte MSG_DIGITAL_WRITE 			= 0x07;
	
	/**
	 * Se recibió un comando personalizado.
	 */
	public static final byte MSG_OBJECT_CMD 			= 0x08;

	/**
	 * Se recibió el cambio de valor de una entrada digital.
	 */
	public static final byte MSG_DIGITAL_IN_READ 		= 0x09;
	
	/**
	 * Escribe una velocidad en un motor DC.
	 */
	public static final byte MSG_MOTORDC_WRITE 			= 0x0A;

	/**
	 * Mueve un motor PaP cantidad de pasos.
	 */
	public static final byte MSG_STEPTOSTEP_MOVE 		= 0x0B;
	
	/**
	 * Cambiar estado de las entradas digitales.
	 */
	public static final byte MSG_SET_STATE_DIGITAL		= 0x0C;
	
	/**
	 * Cambiar estado de las entradas analogicas.
	 */
	public static final byte MSG_SET_STATE_ANALOG		= 0x0D;
	
	public static final byte __LAST_MSG_CODE			= MSG_SET_STATE_ANALOG;
	
	/* ============================================================
	 * Tipos de componentes.
	 * ============================================================
	 * */
	/**
	 * Componentes digitales.
	 */
	public static final byte MSG_TYPE_DIGITAL 					= 0x01;

	/**
	 * Componentes analógicos.
	 */
	public static final byte MSG_TYPE_ANALOG 					= 0x02;
	
	
	/* ============================================================
	 * Comandos para las LCD
	 * ============================================================
	 * */
	
	/**
	 * Limpiar pantalla
	 */
	public static final byte CMD_LCD_CLEAR						= 0x01;
	
	/**
	 * Ubicar cursor
	 */
	public static final byte CMD_LCD_SET_CURSOR					= 0x02;
	
	/**
	 * Imprimir en la pantalla
	 */
	public static final byte CMD_LCD_PRINT						= 0x03;
	
}