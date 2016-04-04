/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.adk;

import java.io.FileDescriptor;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import android.os.ParcelFileDescriptor;

import com.nebula.NbTrace;
import com.nebula.com.NbCom;
import com.nebula.com.NbComErrorEnum;
import com.nebula.com.NbComMsgEnum;
import com.nebula.com.NbConnectedThread;

/**
 * Hilo de ejecución manejador para conexiones por ADK.
 * Este tipo de hilo obtiene los stream desde el descriptor de archivos obteneido del accesorio conectado. Este descritor es
 * recibido como un parametro en a instanciación de esta clase.
 * 
 */
public class NbAdkConnectedThread extends NbConnectedThread{
	
	/**
	 * Descriptor de archivo del Accesorio conectado.
	 */
	private final ParcelFileDescriptor mParcelFileDescriptor;
	
	/**
	 * Constructor.
	 * Realiza la inicialización mínima de la clase.
	 * 
	 * @param com					Instancia de comunicación que manejar� el hijo.
	 * @param parcelFileDescriptor 	Descriptor de archivo del Accesorio conectado.
	 */
	public NbAdkConnectedThread(NbCom com, ParcelFileDescriptor parcelFileDescriptor){
		super(com);

		mParcelFileDescriptor = parcelFileDescriptor;
        InputStream tmpIn = null;
        OutputStream tmpOut = null;
		
        // Si el descriptor es null entonces no se pudo conectar al accesorio.
		if (parcelFileDescriptor != null){
			
			// Obtener los Stream del descriptor.
			FileDescriptor fd = parcelFileDescriptor.getFileDescriptor();
			tmpIn = new FileInputStream(fd);
			tmpOut = new FileOutputStream(fd);
			
		}else{
			NbTrace.e(this, String.format("ConnectedThread(): cant get parcelFileDescriptor"));
        	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_GET_PARCEL_FILE_DESCRIPTOR.ordinal(), -1, null, null);
		}
        
		// Asignar los Stream.
        setInputStream(tmpIn);
        setOutputStream(tmpOut);
		
	}
    
	/**
	 * Se sobreescribe el método para que tambien cierre el descriptor de archivo.
	 */
	public void cancel(){
		super.cancel(); // Esto cierra los stream.
		
		// Cerrar el descriptor de archivo.
	    try {
	    	NbTrace.e(this, "mParcelFileDescriptor.close()");
	    	if (mParcelFileDescriptor != null) mParcelFileDescriptor.close();
	    }catch(IOException e) {
	    	NbTrace.e(this, "ConnectedThread.cancel(): mParcelFileDescriptor.close() failed", e);
	    	getCom().sendMessage(NbComMsgEnum.ERROR, NbComErrorEnum.CANT_CLOSE_PARCEL_FILE_DESCRIPTOR.ordinal(), -1, null, null);
	    }
	    
	}
	
}