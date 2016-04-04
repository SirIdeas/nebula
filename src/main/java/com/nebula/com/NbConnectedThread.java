/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.com;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;
import com.nebula.NbTrace;

/**
 * Hilo de ejecución manejador de la conexión principal.
 * Este hilo es instanciado en el momento que se logra una conexión satisfactoria. Contiene los Stream que permiten la entrada
 * y salida de datos. Este hilo se mantendrá ejecutandose mientras la comunicación está activa. Las especificaciones de esta
 * clase se encargan de obtener los stream necesarios desde la fuente correspondiente.
 * 
 * @version 1.0
 */
public class NbConnectedThread extends Thread{
	
	/**
	 * Instancia de comunicación que está manejando.
	 */
	private NbCom mCom;
	
	/**
	 * Stream que maneja la entrada de datos.
	 */
    private InputStream mmInStream;
    
    /**
     * Stream que maneja la salida de datos.
     */
    private OutputStream mmOutStream;
    
    /**
     * Constructor.
     * Se encarga de la inicialización inicial de la clase.
     *  
     * @param com	Instancia de comunicación que manejará.
     */
    public NbConnectedThread(NbCom com){
    	NbTrace.d(this, "ConnectedThread()");
    	mCom = com;
	}
    
    /**
     * Obtiene la instancia de la comunicación que maneja.
     * 
     * @return	Instancia de comunicación manejado por el hilo.
     */
    protected NbCom getCom(){
    	return mCom;
    }
    
    /**
     * Asigna el Stream de entrada.
     * 
     * @param input		Stream que se asignará.
     */
    protected void setInputStream(InputStream input){
    	mmInStream = input;
    }
    
    /**
     * Asigna el Stream de salida.
     * 
     * @param output	Stream que se asignará.
     */
    protected void setOutputStream(OutputStream output){
    	mmOutStream = output;
    }
    
    /**
     * Permite escribir bytes en el salida de la comunicación.
     * 
     * @param buffer 	Array de bytes que se desea escribir en la salida.
     */
    public void write(byte[] buffer){
    	NbTrace.d(this, String.format("ConnectedThread.write(%s)", new NbBytes(buffer)));
        try {
        	long wait = mCom.getWait();
        	if(wait==0){
        		mmOutStream.write(buffer);
        	}else{
        		NbTrace.d("ERROR", "ERROR");
        		try{
	        		for(int i=0; i<buffer.length; i++){
	        			mmOutStream.write(buffer[i]);
	        			Thread.sleep(wait);
	        		}
        		}catch(InterruptedException e) { e.printStackTrace(); }
        	}
        } catch (IOException e) {
        	NbTrace.e(this, "ConnectedThread.run(): Exception during write", e);
        }
    }
    
    /**
     * Cancela la ejecución del hilo.
     * Para esto se se cierran los stream asignados. Esto generará una Excepcion dentro del bucle infinito del método run en la
     * lectura del stream de entrada, y por consiguiente la salida del mismo.
     */
    public void cancel(){
    	NbTrace.d(this, "ConnectedThread.cancel()");
    	
    	// Cierre del Stream de salida.
        try {
        	if (mmOutStream != null) mmOutStream.close();
        } catch (IOException e) {
        	NbTrace.e(this, "ConnectedThread.cancel(): mmOutStream.close() failed", e);
        	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CLOSE_OUTPUT_STREAM.ordinal(), -1, null, null);
        }
        
        // Cierre del Streem de entrada.
        try {
        	if (mmInStream != null) mmInStream.close();
        } catch (IOException e) {
        	NbTrace.e(this, "ConnectedThread.cancel(): mmInStream.close() failed", e);
        	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CLOSE_INPUT_STREAM.ordinal(), -1, null, null);
        }
        
    }
	
    /**
     * Implementación estándar del hilo de ejecución.
     * Consiste en mantener un bucle infinito en el cual se realice la lectura del buffer de entrada y se genere un evento cada
     * vez que reciban datos. 
     */
    public void run(){
    	NbTrace.d(this, "ConnectedThread.run()");
    	
    	int len;
        byte[] buffer = new byte[1024];
        
        // Al iniciar el la ejecución del hilo se creará un nuevo buffer de bytes. Este buffer de bytes será utilizado durante
        // toda la vida de este hilo.
        NbBuffer bufferIn = new NbBuffer();
        
        // Generar evento que marca el inicio de la conexión.
        getCom().sendMessage(NbComMsgEnum.INIT_CONNECTION, -1, -1, null, null);
        
        try{
        	
        	// Mientras se este conectado este hilo se mantendrá escuchando el Stream de entrada.
        	while(true){
        		
        		// Leer el stream de entrada.
	        	len = mmInStream.read(buffer);
	        	
	        	// Bloquear el recurso pues podría esta siendo utilizado para leer los datos.
	        	synchronized (bufferIn) {
	        		
	        		// Agregar los bytes leídos al buffer de entrada.
	        		for(int i=0; i<len; i++){
	            		bufferIn.add(NbBytes.BYTEOF(buffer[i]));
	            	}
	        		
	        		// Al terminar la lectura del buffer se genera un evento para indicar la nueva llegada de datos.
	        		NbTrace.d(this, String.format("ConnectedThread.read(%s)", bufferIn));
	        		getCom().sendMessage(NbComMsgEnum.DATA_RECEIVED, -1, -1, bufferIn, null);
	        	
	        	}
	        	
        	}
        }catch(IOException e){
        	
        	// Cuando se pierda la conexión se generará una excepción dentro del bucle infinito por lo tanto saldrá del mismo.
        	NbTrace.e(this, "ConnectedThread.run(): disconnected", e);
        	getCom().connectionLost();
        	
        }
        
    }
	
}
