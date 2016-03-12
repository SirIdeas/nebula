/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula;

import android.annotation.SuppressLint;

import java.util.Collection;
import java.util.Vector;

/**
 * Lista dinámica de Bytes.
 * Permite tener una lista ilimitada de elementos Byte.
 * 
 */
@SuppressLint("DefaultLocale")
public class NbBytes extends Vector<Byte>{
	
	// Auto generado por extender de la clase Vector
	private static final long serialVersionUID = 48536526537484489L;

	/**
	 * Tamaño en bytes de un <code>int</code>. Por general dos (2) bytes.
	 * Este es utilizado para conocer la cantidad de bytes a leer o escribir cuando se desee obtener o guardar un
	 * <code>int</code> en <code>VectorBytes</code>.
	 */
	public static final int SIZE_INTEGER = 2;
	
	/**
	 * Tamaño en bytes de un <code>long</code>. Por general dos (4) bytes. Este es utilizado para conocer la cantidad de bytes
	 * a leer o escribir cuando se desee obtener o guardar un <code>long</code> en <code>VectorBytes</code>.
	 */
	public static final int SIZE_LONG = 4;
	
	/**
	 * Máscara para obtener el primer byte de un <code>long</code>.
	 * Esta función consiste obtener el primer byte de un <code>long</code>.
	 * 
	 * @param num 	Entero de cual se obtendrá el byte.
	 * @return 		El primero byte el número pasado por parámetro.
	 */
    public static byte BYTEOF(long num){
    	return (byte)(num & 0xFF);
    }
	
	/**
	 * Constructor de un nuevo <code>VectorBytes</code> sin elementos.
	 */
	public NbBytes() {
	}
	
	/**
	 * Constructor de un nuevo <code>VectorBytes</code> con ciertos elementos iniciales.
	 * 
	 * @param bytes		Array de bytes iniciales del <code>VectorBytes</code>.
	 */
	public NbBytes(byte[] bytes) {
		for(byte b : bytes) add(BYTEOF(b));
	}
	
	/**
	 * Agrega un <code>byte</code> a la lista.
	 * Esta funciona toma el primer byte de la derecha de un entero para agregarlo al <code>VectorBytes</code>.
	 * 
	 * @param num	Número a agregar.
	 * @return 		Siempre retorna true.
	 */
	public boolean add(long num) {
		return super.add(BYTEOF(num));
	}
	
	/**
	 * Agrega cierta cantidad de bytes de un entero largo al VectorBytes.
	 * Agrega los primeros <code>size</code>-bytes de <code>num</code> al <code>VectorBytes</code> comenzando desde la derecha.
	 * 
	 * @param num 	Entero largo del cual se obtendra los bytes.
	 * @param size	Cantidad de bytes a tomar. Por lo general es <code>SIZE_INTEGER</code> o <code>SIZE_LONG</code>.
	 */
	protected void addSeparateBytes(long num, int size){
		for(int i=0; i<size; i++, num>>=Byte.SIZE) add(num);
	}
	
	/**
	 * Agrega los <code>SIZE_INTEGER</code>-bytes de un entero al <code>VectorBytes</code>.
	 * Divide un entero en <code>SIZE_INTEGER</code>-bytes y luego los agrega al <code>VectorBytes</code>.
	 * 
	 * @param num	Entero a dividir.
	 */
	public void addInt(int num){
		addSeparateBytes(num, SIZE_INTEGER);
	}
	
	/**
	 * Agrega los <code>SIZE_LONG</code>-bytes de un entero al <code>VectorBytes</code>.
	 * Divide un entero en <code>SIZE_INTEGER</code>-bytes y luego los agrega al <code>VectorBytes</code>.
	 * 
	 * @param num	Entero a dividir.
	 */
	public void addLong(long num){
		addSeparateBytes(num, SIZE_LONG);
	}
	
	/**
	 * Convierte el <code>VectorBytes</code> en un <code>byte[]</code>.
	 * 
	 * @return	retorna un <code>byte[]</code> con los elementos del <code>VectorBytes</code>.
	 */
	public byte[] getArray(){
		byte[] data = new byte[size()];
    	for(int i=0; i<data.length; i++) data[i] = get(i);
    	return data;
	}
	
	/**
	 * Agrega los elementos de un <code>byte[]</code> al <code>VectorBytes</code>.
	 * Recorre el <code>byte[]</code> para agregalo al <code>VectorBytes</code>.
	 * 
	 * @param data	Array de bytes que se desea agregar al vector.
	 */
	public void addBytes(byte[] data){
		for(byte b : data) add(b);
	}
	
	/**
	 * Se sobreescribe esta función para que retorne falso en el caso de que la <code>collection==null</code> y asi evitar el
	 * lanzamiento de un Exception. 
	 */
	@Override
	public boolean addAll(Collection<? extends Byte> collection) {
		if(collection == null) return false;
		return super.addAll(collection);
	}
	
	/**
	 * Convierte el vector en un cadena de caracteres.
	 */
	public String toString(){
		int size = size();
		String str = String.format("%d: ", size);
		for(int i=0; i<size; i++){
			str = String.format("%s%d ", str, (int)(0x00FF & get(i)));
		}
		return str.trim();
	}
	
}
