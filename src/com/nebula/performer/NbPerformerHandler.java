package com.nebula.performer;

import com.nebula.NbBuffer;
import com.nebula.com.NbComHandler;

/**
 * Implementaci�n del Manejador de eventos de comuniaci�n para un Sketch.
 * En esta clase se implementa los m�todos <code>sendSetup</code> y <code>dataReceived</code>.
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
	 * Este m�todo se apoderara del buffer de entrada. A continuaci�n se proceder� a reiniciar el buffer para luego pasarlo por
	 * la funci�n <code>proccessData</code> del Sketch configurado. La funci�n <code>proccessData</code> deber� leer los bytes
	 * que requiera para su operaci�n y retornar <code>true</code> en el caso de que la lectura haya culminado
	 * satisfactoriamente para que asi se puedan eliminar los bytes le�dos. Si el buffer se queda sin bytes antes de que
	 * <code>proccessData</code> complete su operaci�n, esta deber� retornar <code>false</code>, lo que indicar� que el buffer
	 * esta incompleto y permitir� salir liberar el buffer para que una siguiente entrada de datos siga rellenando el buffer.
	 * En este ultimo caso no se eliminar�n los bytes le�dos.
	 */
	public void dataReceived(NbBuffer data){
		
		// Bloquear el buffer.
		synchronized (data) {
			
			// Reiniciar el buffer.
			data.start();
			
			// Procesar el buffer dentro del sketch y salir si no se completo el procesamiento.
    		while(mPerformer.received(data)){
    			
    			// Eliminar los bytes le�dos.
    			data.purge();
    			
    		}
    		
		}
		
	}
	
}