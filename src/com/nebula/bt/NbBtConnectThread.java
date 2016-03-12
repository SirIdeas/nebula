/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.bt;

import java.io.IOException;
import java.util.UUID;

import com.nebula.NbTrace;
import com.nebula.com.NbComErrorEnum;
import com.nebula.com.NbComMsgEnum;

import android.annotation.SuppressLint;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;

/**
 * Hilo de ejecución para esperar la conexión de un dispositivo Bluetooth.
 * Este hilo se encarga de crear un socket que seguro o inseguro para conectarse a al dispositivo bluetooth indicado en los
 * parámetros de instanciación.
 *
 */
@SuppressLint("NewApi")
public class NbBtConnectThread extends Thread {
	
    /**
     * Instancia de conexión manejada por el hilo. 
     */
    private final NbBt mCom;
    
    /**
     * Socket creado para establecer comunicación.
     */
    private final BluetoothSocket mmSocket;
    
    /**
     * Dispositivo bluetooth al que se conectará.
     */
    private final BluetoothDevice mmDevice;
    
    /**
     * Indica si la comunicación es seguro o insegura.
     */
    private boolean mSecure;
    
    /**
     * Constructor.
     * Se encarga de realizar la incialización básica inical de la clase. Dentro de esta cabe destacar que se se crea el socket
     * (seguro o inseguro). 
     * 
     * @param com		Instancia de comunicación que intenta conectarse al dispositivo bluetooth.
     * @param device 	Intancia del dispositivo bluetooth al que se desea conectar.
     * @param secure 	Indica si tipo de conexión es seguro o no.
     * @param btUuid 	UUID que se utilizará para crear el socket.
     */
    public NbBtConnectThread(NbBt com, BluetoothDevice device, boolean secure, UUID btUuid) {
    	NbTrace.d(this, String.format("ConnectThread(%b)", secure));
    	
    	mCom = com;
        mmDevice = device;
        BluetoothSocket tmp = null;
        mSecure = secure;

        try {
        	
        	// Se crea el socket. 
            if (secure) {
                tmp = device.createRfcommSocketToServiceRecord(btUuid);
            } else {
                tmp = device.createInsecureRfcommSocketToServiceRecord(btUuid);
            }
            
        } catch (IOException e) {
        	NbTrace.e(this, String.format("ConnectThread(%s ,%b): device.create() failed", mmDevice, secure), e);
        	mCom.sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CREATE_SOCKET.ordinal(), -1, null, null);
        }
        
        // Asignar el socket.
        mmSocket = tmp;
        
    }
    
    /**
     * Se implementa la función <code>run</code>.
     * La implementación consiste en esperar establecer la conexión con el socket.
     */
    public void run() {
    	NbTrace.d(this, "ConnectThread.run()");
        setName("ConnectThread" + (mSecure ? "Secure" : "Insecure"));

        // Antes de intentar inicar la conexión se deben cancelar el escaneo de dispositivos bluetooth.
        mCom.cancelDiscovery();
        
        // Realizar la conexión con el bluetooth.
        try{
            
        	// Este sentencia solo culminara satisfatoriamente cuando se establezca la conexión con el dispositivo bluetooth.
        	// De lo contrario generará una excpecion.
            mmSocket.connect();
            
        }catch(IOException e){
        	
            // Se cerrara el socket porque falló el intento de conexión.
            try{
                mmSocket.close();
            }catch(IOException e2){
                NbTrace.e(this, "ConnectThread.run(): mmSocket.close() failed during connection failure", e2);
            }
            mCom.connectionFailed();
            return;
        }
        
        // En el caso de que no se halla generado una Excepcion durante la conexión se iniciara el hilo de manejador de
        // conexión.
        mCom.initConnectedThread(mmSocket, mmDevice, mSecure);
        
    }
    
    /**
     * Cancela la ejecución del hilo.
     * Para esto se cierra el soket generado lo que causará una excepcion durante el intendo de conexión en el método
	 * <code>run</code> y terminará la ejecución del hilo actual.
     */
    public void cancel() {
    	NbTrace.d(this, "ConnectThread.cancel()");
    	
    	// Cerrar el socket.
        try {
            mmSocket.close();
        } catch (IOException e) {
        	NbTrace.e(this, "ConnectThread.cancel(): mmSocket.close() failed", e);
        	mCom.sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CLOSE_SOCKET.ordinal(), -1, null, null);
        }
        
    }
}