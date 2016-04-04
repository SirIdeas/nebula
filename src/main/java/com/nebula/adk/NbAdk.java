/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.adk;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
//import com.android.future.usb.UsbAccessory;
//import com.android.future.usb.UsbManager;
import android.hardware.usb.UsbAccessory;
import android.hardware.usb.UsbManager;
import android.os.ParcelFileDescriptor;

import com.nebula.NbTrace;
import com.nebula.com.NbCom;
import com.nebula.com.NbComMsgEnum;
import com.nebula.com.NbComStateEnum;

/**
 * Clase para comunicación ADK.
 * Especificación de la clase <code>BaseComunicacion</code> para realizar comunicaciones por ADK manteniendo un feedback del
 * tipo Serial. Básicamente consiste en métodos que permiten realizar este tipo de comunicación.
 * 
 */
@SuppressLint("NewApi")
public class NbAdk extends NbCom {
	
	/**
	 * Clave para crear filtrar las solicitudes de permisos del puerto USB
	 */
	private final String ACTION_USB_PERMISSION = ".USB_PERMISSION";

    /**
     * Contexto en el cual se registrarán y desregistrarán los <code>BaseComunicationBroadcastReceiver</code> correspondientes
     * a la comunicación bluetooth.
     */
	private Context mContext = null;

    /**
     * <code>BaseComunicationBroadcastReceiver</code> que se encargarán de recibir los mensajes cuando se detecte un nuevo
     * accesorio.
     */
	private final BaseComunicationBroadcastReceiver mReceiver;
	
	/**
	 * String que contiene el valor para identidicar PendingIntent para solicitar el permiso de conexión al accesorio USB no soportado
	 */
    private String mActionUsbPermission = null;
	
    /**
     * Administrador de conexiones USB.
     */
	private UsbManager mUsbManager;
	
	/**
	 * Accesorio al que se esta conectado.
	 */
	private UsbAccessory mAccessory;
	
	/**
	 * Petición de permisos para usar el Accesorio USB.
	 */
	private PendingIntent mPermissionIntent;
	
	/**
	 * Indica si ya se solicito el permito o no.
	 */
	private boolean mPermissionRequestPending;
	
	/**
	 * Constructor.
	 * Inicializa los paramátros necesario para el funcionamiento mínimo de la instancia.
	 * 
	 * @param context	El contexto bajo el que trabajar� la instancia (por lo general es la actividad que instancia la clase)
	 */
	public NbAdk(Context context){
		this(context, 0);
	}
	
	/**
	 * Constructor.
	 * Inicializa los parámetros necesario para el funcionamiento mínimo de la instancia.
	 * 
	 * @param context 	El contexto bajo el que trabajará la instancia (por lo general es la actividad que instancia la clase)
	 * @param wait		Tiempo de espera entre envios de paquetes (en milisegundos)
	 */
	public NbAdk(Context context, long wait) {
		super(wait);
		
		mContext = context;
		
		// Obtener el manejador de USB.
//		mUsbManager = UsbManager.getInstance(context);
		mUsbManager = (UsbManager)mContext.getSystemService(Context.USB_SERVICE);
		
		// Action para identificar el respuestas de los intent de
		// solicitud de permiso par uso de accesorio conectados no soportados
		mActionUsbPermission = mContext.getPackageName().concat(ACTION_USB_PERMISSION);
		
		// Crear el intent para solicitar permisos no soportados
		mPermissionIntent = PendingIntent.getBroadcast(mContext, 0, new Intent(mActionUsbPermission), 0);
		
		// Instanciar BroadcastReceiver que recibirá los mensajes.
		mReceiver = new BaseComunicationBroadcastReceiver(this);
		
		// Registrar los Broadcasts.
		registerReceiver();
		
	}
	
	/**
	 * Chequqa que el intent del contexto actual. Esto sirve para autoconectarse al
	 * accesorio cuado un contexto se ejecuta por la conexión de un dispositivo. Esto
	 * se realiza si el contexto es una instancia de un Activity
	 */
	public void checkIntent(){
		if(mContext instanceof Activity)
			onReciveBroadcastReciver(mContext, ((Activity)mContext).getIntent());
	}
	
	/**
	 * Retorna el accesorio en una posicion especifica.
	 * 
	 * @param pos	Posicion del accesorio que se desea conultar.
	 * @return		Instancia del accesorio o <code>null</code> si la posición es inv�lida.
	 */
	public UsbAccessory getAccessoryItem(int pos){
		
		// Obtener el primer accesorio conectado.
		UsbAccessory[] accessories = mUsbManager.getAccessoryList();
		
		if(accessories == null || pos < 0 || accessories.length<=pos){
			return null;
		}
		
		return accessories[pos];
				
	}

	/**
	 * Devuelve el accesorio en la primera posición.
	 * 
	 * @return	Instancia del accesorio o <code>null</code> si la posición es inválidad
	 */
	public UsbAccessory getAccessoryItem(){
		return getAccessoryItem(0);
	}
    
	/**
	 * Obtener el accesorio al que se está conectado
	 * 
	 * @return 	Retorna el accesorio acutamente conectado.
	 */
	public UsbAccessory getAccesory(){
		return mAccessory;
	}
	
	/**
	 * Chequea si existe algun accesorio conectado y intenta conectarse al primero de la lista.
	 * Esta funcion chequea los accesorios, si esta conectado y si tiene permiso para conectarse.
	 */
	public void connect(){
		super.connect();
		
		// Si mAccesory es deiferente de null, indica que ya extiste una conexión.
		if (mAccessory != null) {
			setState(NbComStateEnum.STATE_CONNECTED);
            return;
        }
		
		// Conectarse al accesorio
		connect(getAccessoryItem());
		
	}
	
	public void connect(UsbAccessory accessory){
		
		// Si el accesorio es null.
		if (accessory != null) {
			
			// Se cambia el estado a no conectado.
			setState(NbComStateEnum.STATE_CONNECTING);
			
			// Generar el evento correspondiente.
	        sendMessage(NbComMsgEnum.CONNECTING, -1, -1, accessory, null);
	        
			// Si tiene permiso.
			if (mUsbManager.hasPermission(accessory)) {
				
				// Se establece conexión.
				connected(accessory);
				
			// Si no tiene permiso.
			}else{
				
				// Se inicia la petición de permiso de forma sincronizada.
				synchronized (mReceiver) {
					if (!mPermissionRequestPending) {
						mUsbManager.requestPermission(accessory, mPermissionIntent);
						mPermissionRequestPending = true;
					}
				}
				
			}
			
		}else{
			
			// No existe accesorio conectado.
			disconnect();
			
		}
		
	}
	
	/**
     * Cancela la conexión actual.
     * Este método permite cancelar la conexión actual. También se cambia el estado de la conexión y se genera el evento
     * correspondiente.
     */
	@Override
	public synchronized void disconnect(){
		super.disconnect();
    	
		cancelConnectedThread();	// Cancelar hilo de conexión.
        mAccessory = null;			// Se elimina el accesorio al que se estaba conectado.
        
        mPermissionRequestPending = false;
        
        // Cambiar el estado de la conexión.
        setState(NbComStateEnum.STATE_DISCONNECTED);
        
      	// Generar evento correspondiente.
        sendMessage(NbComMsgEnum.DISCONNECT, -1 , -1, null, null);
        
    }
	
    /**
     * Inicia el hilo para la conexión activa con el accesorio.
     * 
     * @param accessory		Accesorio al que se conectará.
     */
    public synchronized void connected(UsbAccessory accessory) {
    	NbTrace.d(this, String.format("connected(%s)", accessory.getModel()));
    	
    	// Obtener el Descriptor de la comunicación.
    	ParcelFileDescriptor fileDescriptor = mUsbManager.openAccessory(accessory);
    	
    	// Si es diferente de null.
    	if (fileDescriptor != null) {
    		
    		// Guardar el accesorio al que se conectó.
    		mAccessory = accessory;
    		
    		cancelConnectedThread();	// Cancelar hilos de conexión anteriores.
    		
    		startNewConnectedThread(new NbAdkConnectedThread(this, fileDescriptor));
    		
    		// Cambiar estado a conectado.
    		setState(NbComStateEnum.STATE_CONNECTED);
    		
    		// Generar evento correspondiente.
    		sendMessage(NbComMsgEnum.CONNECTED, -1, -1, accessory, null);
    		
    	}else{
    		
    		// No se pudo establecer conexión con el accesorio.
            NbTrace.e(this, "connected(): Accessory open failed");
            
            // Cambiar estado a no conectado.
            connectionFailed();
    		
        }
    	
    }

    /**
     * Registra el <code>BaseComunicationBroadcastReceiver</code> en el contexto.
     */
    public void registerReceiver(){
    	NbTrace.d(this, "registerReceiver()");
    	
    	// Crear el filtro de intents para dejar pasar solo los correspondientes
    	// a la solicitud de permiso para accesorios conectados no soportados
    	IntentFilter filter = new IntentFilter(mActionUsbPermission);
    	
    	// deja pasar intents de conexión y desconexión de accesorios
    	filter.addAction(UsbManager.ACTION_USB_ACCESSORY_ATTACHED);
		filter.addAction(UsbManager.ACTION_USB_ACCESSORY_DETACHED);
    	
		// Registrar broadcaster
        mContext.registerReceiver(mReceiver, filter);
        
    }

    /**
     * Eliminar el <code>BaseComunicationBroadcastReceiver</code> en el contexto.
     */
    public void unregisterReceiver(){
    	NbTrace.d(this, "unregisterReceiver()");
    	
    	mContext.unregisterReceiver(mReceiver);
    	
    }
    
    /**
     * Recive los mensajes del <code>BaseComunicationBroadcastReceiver</code>.
     * Para esta especificaci�n se recibe la respuesta de solicitud de permisos para conectarse a los accesorios y recive aviso
     * de cuando se detecta un accesorio compatible.
     */
    public void onReciveBroadcastReciver(Context context, Intent intent){
    	
    	String action = intent.getAction();
    	
    	// Respuesta recibida a la peticion de permiso para usar accesorio USB
		if(mActionUsbPermission.equals(action)){
			
			synchronized (this) {
				
//				UsbAccessory accessory = UsbManager.getAccessory(intent);
				UsbAccessory accessory = intent.getExtras().getParcelable(UsbManager.EXTRA_ACCESSORY);
				
				// Si se otorgo permiso para conectarse al dispositivo entonces se conectara. 
				if(intent.getBooleanExtra(UsbManager.EXTRA_PERMISSION_GRANTED, false)){
					connected(accessory);
				}else{
					connectionFailed();
				}
				mPermissionRequestPending = false;
			}
		
		// Se conecto un dispositivo
		}else if(UsbManager.ACTION_USB_ACCESSORY_ATTACHED.equals(action)){
			
//			UsbAccessory accessory = UsbManager.getAccessory(intent);
			UsbAccessory accessory = intent.getExtras().getParcelable(UsbManager.EXTRA_ACCESSORY);
			connect(accessory);
    		
		// Se desconecto el accesorio
		}else if(UsbManager.ACTION_USB_ACCESSORY_DETACHED.equals(action)){
			
			// Si se trata del accesorio actuamente conectado entonces de desconecta.
//			UsbAccessory accessory = UsbManager.getAccessory(intent);
			UsbAccessory accessory = intent.getExtras().getParcelable(UsbManager.EXTRA_ACCESSORY);
			if (accessory != null && accessory.equals(mAccessory)) {
				disconnect();
			}
			
		}
		
    }
	
}
