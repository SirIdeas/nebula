package com.nebula.sketch.cmp.interfaces;

/**
 * Interface para definir componentes que pueden tener un pin asociado.
 * 
 * @version 1.0
 */
public interface NbPin{
	
	/**
	 * Constante para definir un pin inválido. 
	 */
	public static final int INVALID_PIN = -1;
	
	/**
	 * obtener número de pin asociado a la clase.
	 * 
	 * @return 	Número de pin asociado.
	 */
	public int getPin();
	
	/**
	 * Asignar un pin asociado.
	 * 
	 * @param pin	Número de pin a asociar
	 */
	public void setPin(int pin);
	
}
