/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.bt;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import android.bluetooth.BluetoothSocket;

import com.nebula.NbTrace;
import com.nebula.com.NbCom;
import com.nebula.com.NbComErrorEnum;
import com.nebula.com.NbComMsgEnum;
import com.nebula.com.NbConnectedThread;

/**
 * Hilo de ejecución manejador para conexiones por Bluetooth.
 * Este tipo de hilo obtiene los stream desde el socket de conexión obtenido del dispositivo bluetooth conectado. Este socket
 * es recibido como un parametro en a instanciación de esta clase.
 * 
 */
public class NbBtConnectedThread extends NbConnectedThread{
	
	/**
	 * Socket de comunicación con el dispositivo bluetooth.
	 */
	private final BluetoothSocket mmSocket;
	
	/**
	 * Para mantener visible el stream de entrada pues se necesario para leer el primer mensaje.
	 */
	private final InputStream mInStream;

	/**
	 * Constructor.
	 * Realiza la inicialización mínima de la clase.
	 * 
	 * @param com 		Instancia de comunicación que manejará el hijo.
	 * @param socket	Socket de conexión con el dispositivo bluetooth.
	 */
    public NbBtConnectedThread(NbCom com, BluetoothSocket socket){
    	super(com);
    	
        mmSocket = socket;
        InputStream tmpIn = null;
        OutputStream tmpOut = null;
        
        try{
        	
        	// Obtener los Stream del socket.
            tmpIn = socket.getInputStream();
            tmpOut = socket.getOutputStream();
            
        }catch (IOException e){
        	NbTrace.e(this, String.format("ConnectedThread(): temp sockets not created"), e);
        	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CREATE_STREAM.ordinal(), -1, null, null);
        }
        
        // Asignar los Stream.
        setInputStream(tmpIn);
        setOutputStream(tmpOut);
        
        mInStream = tmpIn;
        
    }

	/**
	 * Se sobreescribe el método para que tambien cierre el socket creado.
	 */
    public void cancel(){
		super.cancel(); // Esto cierra los stream.
		
		// Cerrar el descriptor de archivo.
        try {
            mmSocket.close();
        } catch (IOException e) {
        	NbTrace.e(this, "ConnectedThread.cancel(): mmSocket.close() failed", e);
        	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CLOSE_SOCKET.ordinal(), -1, null, null);
        }
    }
    
    /**
     * Se sobreescribe el método de <code>run</code> para realizar realizar la lectura del primer mensaje antes de comenzar a
     * verdadera corrida del hilo.
     */
    @Override
    public void run() {
    	NbTrace.d(this, "ConnectedThread.run(): waiting FIRST_MESSAGE");
    	
        byte[] buffer = new byte[1024];
        
        try{
        	write(new byte[]{0});
        	// Leer el input de entrada.
        	// La función se mantendrá en este punto hasta que se reciba los primeros bytes desde el dispositivo bluetooth. 
        	// Este permite generar un evento que indique que ya se leyó el primer mensaje lo que garantiza que la conexión
        	// se ha efectuado satisfactoriamente.
	        mInStream.read(buffer);
	        getCom().sendMessage(NbComMsgEnum.BT_FIRST_MESSAGE, -1, -1, null, null);
	        
	        // Una vez recibido el primer mensaje procede a escuchar el hilo como normalmente se hace.
	        super.run();
	        
        }catch(IOException e){
        	NbTrace.e(this, "ConnectedThread.run(): disconnected", e);
        	getCom().connectionLost();
        }
        
    }
    
}