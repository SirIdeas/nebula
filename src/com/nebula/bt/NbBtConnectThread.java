/**
 * Proyecto N�bula
 *
 * @author Alex J. Rond�n <arondn2@gmail.com>
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
 * Hilo de ejecuci�n para esperar la conexi�n de un dispositivo Bluetooth.
 * Este hilo se encarga de crear un socket que seguro o inseguro para conectarse a al dispositivo bluetooth indicado en los
 * par�metros de instanciaci�n.
 *
 */
@SuppressLint("NewApi")
public class NbBtConnectThread extends Thread {
	
    /**
     * Instancia de conexi�n manejada por el hilo. 
     */
    private final NbBt mCom;
    
    /**
     * Socket creado para establecer comunicaci�n.
     */
    private final BluetoothSocket mmSocket;
    
    /**
     * Dispositivo bluetooth al que se conectar�.
     */
    private final BluetoothDevice mmDevice;
    
    /**
     * Indica si la comunicaci�n es seguro o insegura.
     */
    private boolean mSecure;
    
    /**
     * Constructor.
     * Se encarga de realizar la incializaci�n b�sica inical de la clase. Dentro de esta cabe destacar que se se crea el socket
     * (seguro o inseguro). 
     * 
     * @param com		Instancia de comunicaci�n que intenta conectarse al dispositivo bluetooth.
     * @param device 	Intancia del dispositivo bluetooth al que se desea conectar.
     * @param secure 	Indica si tipo de conexi�n es seguro o no.
     * @param btUuid 	UUID que se utilizar� para crear el socket.
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
     * Se implementa la funci�n <code>run</code>.
     * La implementaci�n consiste en esperar establecer la conexi�n con el socket.
     */
    public void run() {
    	NbTrace.d(this, "ConnectThread.run()");
        setName("ConnectThread" + (mSecure ? "Secure" : "Insecure"));

        // Antes de intentar inicar la conexi�n se deben cancelar el escaneo de dispositivos bluetooth.
        mCom.cancelDiscovery();
        
        // Realizar la conexi�n con el bluetooth.
        try{
            
        	// Este sentencia solo culminara satisfatoriamente cuando se establezca la conexi�n con el dispositivo bluetooth.
        	// De lo contrario generar� una excpecion.
            mmSocket.connect();
            
        }catch(IOException e){
        	
            // Se cerrara el socket porque fall� el intento de conexi�n.
            try{
                mmSocket.close();
            }catch(IOException e2){
                NbTrace.e(this, "ConnectThread.run(): mmSocket.close() failed during connection failure", e2);
            }
            mCom.connectionFailed();
            return;
        }
        
        // En el caso de que no se halla generado una Excepcion durante la conexi�n se iniciara el hilo de manejador de
        // conexi�n.
        mCom.initConnectedThread(mmSocket, mmDevice, mSecure);
        
    }
    
    /**
     * Cancela la ejecuci�n del hilo.
     * Para esto se cierra el soket generado lo que causar� una excepcion durante el intendo de conexi�n en el m�todo
	 * <code>run</code> y terminar� la ejecuci�n del hilo actual.
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