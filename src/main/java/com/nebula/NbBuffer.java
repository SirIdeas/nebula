/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula;

/**
 * Clase para almacenar bytes recibidos.
 * Esta clase almacena los bytes consecutivos sin un límite. Luego de almacenados permite ir leyendo <code>byte</code> a
 * <code>byte</code>, array de bytes (<code>byte[]</code>), <code>int</code> (2 bytes) o <code>long</code> (4 bytes). Por
 * último permite eliminar los bytes que ya hayan sido procesados, o regresar al último punto de partida en el caso de que la
 * lectura no se haya finalizado por falta de alguna cantidad de bytes. Esta clase esta implementada a través de un
 * <code>VectorBytes</code>.
 * 
 */
public class NbBuffer extends NbBytes{
	
	// Auto generado por heredar de una clases Vector.
	private static final long serialVersionUID = -498701349388083662L;
	
	/**
	 * Índice del último byte leído.
	 * A medida que se vayan leyendo bytes, este índice se irá desplazando para saber que byte leer. Esto permite recuperar los
	 * bytes leídos en el caso de un error.
	 */
	private int index = 0;
	
	/**
	 * Consturctor principal de la clase.
	 * Este tipo de Buffer siempre se debe iniciar vacío, para cuando se haga una lectura de bytes desde algún stream se
	 * almacenen en este.
	 */
	public NbBuffer(){
	}
	
	/**
	 * Reinicia el índice de lectura para recuperar los últimos bytes leídos.
	 * En el caso de que se detecte que que faltan bytes por la razón que fuera, se puede llamar esta función para volver a
	 * mover el índice al inicio de la lisa para recuperar los bytes leídos.
	 */
	public void start(){
		index = 0;
	}
	
	/**
	 * Elimina los bytes leídos hasta los momentos.
	 * En el momento de que se sepa que se ha terminado una lectura exitosa, se puede llamar esta funcion para liberar los
	 * elementos ya leídos pues debería ya no ser necesarios.
	 */
	public void purge(){
		while(index-->0 && size()>0) remove(0);
		index = 0;
	}

	/**
	 * Lee el <code>index</code>-esimo <code>byte</code> de la lista.
	 * El proximo elemento a leer esta indicado por <code>index</code>, por lo que para hacer una lectura se debe leer el
	 * elemento de la lista en esta posición. Además se debe desplazar este indice al próximo elemento.
	 * 
	 * @return	El <code>byte</code> leído.
	 */
	public byte read() throws ReadException{
		if(!available()) throw new ReadException();
		return get(index++);
	}
	
	/**
	 * Obtiene un <code>int</code> del buffer.
	 * Permite la lectura de un <code>int</code> concatenando los bits de los siguientes 2 bytes. Antes de su uso se debe
	 * verificar que existan al menos 2 bytes en la lista.
	 * 
	 * @return	El <code>int</code> leído.
	 */
	public int readInt() throws ReadException{
		return (int)read(NbBytes.SIZE_INTEGER);
	}
	
	/**
	 * Obtiene un <code>long</code> del buffer.
	 * Permite la lectura de un <code>long</code> concatenando los bits de los siguientes 4 bytes. Antes de su uso se debe
	 * verificar que existan al menos 4 bytes en la lista.
	 * 
	 * @return	El <code>long</code> leído.
	 */
	public long readLong() throws ReadException{
		return read(NbBytes.SIZE_LONG);
	}
	
	/**
	 * Obtiene un <code>long</code> del buffer.
	 * Permite la lectura de un <code>long</code> concatenando los bits de los siguientes <code>size</code>-bytes. Los métodos
	 * <code>readInt</code> y <code>readLong</code> están basadas en método. Antes de su uso se debe verificar que existan al
	 * menos <code>size</code>-bytes en la lista.
	 * 
	 * @param	size Cantidad de bytes a concatenar.
	 * @return 	Retorna el <code>long</code> leído.
	 */
	public long read(int size) throws ReadException{
		if(available(size)){
			long ret = 0;
			byte[] buffer = new byte[size];
			for(int i=0; i<buffer.length; i++){
				buffer[i] = read();
			}
	    	for(int i=buffer.length; i>0; i--){
	    		ret = (ret<<Byte.SIZE) | (buffer[i-1] & ((0x1<<Byte.SIZE) -1));
	    	}
	    	return ret;
		}
		return -1;
	}
	
	/**
	 * Indica si hay al menos un bytes disponible en el buffer.
	 * 
	 * @return	Retorna <code>true</code> si existe al menos un byte en el buffer, de lo contrario <code>false</code>.
	 */
	public boolean available(){
		return available(1);
	}

	/**
	 * Indica si hay al menos <code>len</code>-bytes disponible en el buffer.
	 * 
	 * @return	Retorna <code>true</code> si existe al <code>len</code>-byte en el buffer, de lo contrario <code>false</code>.
	 */
	public boolean available(int len){
		return (size() - index)>=len;
	}
	
	/**
	 * Exception para lanzar errores durante la lectura de bytes dle buffer. 
	 */
	public class ReadException extends Exception{
		
		// Autogenerado por heredar de una Exception
		private static final long serialVersionUID = 2813886771517255333L;
		
	}
	
}
