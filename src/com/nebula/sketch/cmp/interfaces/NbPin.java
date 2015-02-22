package com.nebula.sketch.cmp.interfaces;

/**
 * Interface para definir componentes que pueden tener un pin asociado.
 * 
 * @version 1.0
 */
public interface NbPin{
	
	/**
	 * Constante para definir un pin inv�lido. 
	 */
	public static final int INVALID_PIN = -1;
	
	/**
	 * obtener n�mero de pin asociado a la clase.
	 * 
	 * @return 	N�mero de pin asociado.
	 */
	public int getPin();
	
	/**
	 * Asignar un pin asociado.
	 * 
	 * @param pin	N�mero de pin a asociar
	 */
	public void setPin(int pin);
	
}
