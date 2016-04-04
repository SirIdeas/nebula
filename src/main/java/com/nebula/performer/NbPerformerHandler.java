package com.nebula.performer;

import com.nebula.NbBuffer;
import com.nebula.com.NbComHandler;

/**
 * Implementación del Manejador de eventos de comunicación para un Sketch.
 * En esta clase se implementa los métodos <code>sendSetup</code> y <code>dataReceived</code>.
 * 
 * @version 1.0
 */
public class NbPerformerHandler extends NbComHandler{
	
	/**
	 * Objeto Sketch relacionado con el manejador de eventos
	 */
	private NbPerformer mPerformer = null;
	
	/**
	 * Asigna un sketch a un Manejador de eventos.
	 * @param sketch Sketch a asignar.
	 */
	public NbPerformerHandler(NbPerformer performer){
		mPerformer = performer;
	}
	
	/**
	 * Asigna un sketch a un Manejador de eventos.
	 * @param sketch Sketch a asignar.
	 */
	public void setPerformer(NbPerformer performer){
		mPerformer = performer;
	}
	
	/**
	 * Envia el setup del sketch al buffer de salida y inicia.
	 */
	@Override
	public void initConnection(){
		mPerformer.setup();
	}
	
	/**
	 * Se encargar de procesar el buffer de entrada siempre que se pueda.
	 * Este método se apoderara del buffer de entrada. A continuación se procederá a reiniciar el buffer para luego pasarlo por
	 * la función <code>proccessData</code> del Sketch configurado. La función <code>proccessData</code> deberá leer los bytes
	 * que requiera para su operación y retornar <code>true</code> en el caso de que la lectura haya culminado
	 * satisfactoriamente para que asi se puedan eliminar los bytes leídos. Si el buffer se queda sin bytes antes de que
	 * <code>proccessData</code> complete su operación, esta deberá retornar <code>false</code>, lo que indicará que el buffer
	 * esta incompleto y permitirá salir liberar el buffer para que una siguiente entrada de datos siga rellenando el buffer.
	 * En este ultimo caso no se eliminarón los bytes leídos.
	 */
	public void dataReceived(NbBuffer data){
		
		// Bloquear el buffer.
		synchronized (data) {
			
			// Reiniciar el buffer.
			data.start();
			
			// Procesar el buffer dentro del sketch y salir si no se completo el procesamiento.
    		while(mPerformer.received(data)){
    			
    			// Eliminar los bytes leídos.
    			data.purge();
    			
    		}
    		
		}
		
	}
	
}