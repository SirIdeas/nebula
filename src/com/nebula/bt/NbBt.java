/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.bt;

import java.util.Set;
import java.util.UUID;

import com.nebula.NbTrace;
import com.nebula.com.NbCom;
import com.nebula.com.NbComMsgEnum;
import com.nebula.com.NbComStateEnum;

import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.bluetooth.BluetoothSocket;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;

/**
 * Clase para comunicación Bluetooth.
 * Especificación de la clase <code>BaseComunicacion</code> para realizar comunicaciones por Bluetoth manteniendo un feedback
 * del tipo Serial. Contiene métodos para lista dispositivos bluetooth dentro del alcance, conectarse, entre otros.
 * 
 */
public class NbBt extends NbCom {
	
	// Unique UUID for this application
    public static final UUID DEFAULT_BT_UUID = UUID.fromString("00001101-0000-1000-8000-00805F9B34FB");
	
	/**
	 * Valor booleano que indica si el tipo de conexión es segura o insegura.
	 */
	public static final String EXTRA_IS_SECURE 				= "com.nebula.comunication.bt.BtComunication.IS_SECURE";
	
	/**
	 * String con la dirección única del dispositivo bluetooth selecionado para la conexión.
	 */
	public static final String EXTRA_BT_DEVICE_ADDRESS		= "com.nebula.comunication.bt.BtComunication.BLUETOOTH_DEVICE_ADDRESS";
	
	/**
	 * String con el nombre al que se desea conectar de forma automáticamente sin abrir el activity para listar los
	 * dispositivos bluetooth.
	 */
	public static final String EXTRA_AUTO_CONNECT_TO_DEVICE	= "com.nebula.comunication.bt.BtComunication.AUTO_CONNECT_TO_DEVICE";
	
    /**
     * Administrador de bluetooth.
     * Con esta instancia podemos realizar las acciones correspondientes a la comunicación bluetooth como obtener dispositivos
     * bluetooth pareados, iniciar y terminar el escaneo de dispositivos bluetooth, entre otros. 
     */
    private final BluetoothAdapter mBtAdapter = BluetoothAdapter.getDefaultAdapter();

    /**
     * <code>BaseComunicationBroadcastReceiver</code> que se encargarán de recibir los mensajes cuando se detecte un nuevo
     * dispositivo bluetooth y cuando se termine un escaneo.
     */
    private final BaseComunicationBroadcastReceiver mReceiver;
    
    /**
     * Contexto en el cual se registrarán y desregistrarán los <code>BaseComunicationBroadcastReceiver</code> correspondientes
     * a la comunicación bluetooth.
     */
    private Context mContext = null;
    
    /**
     * Nombre del dispositivo bluetooth al que se desea conectar automaticamente.
     * Si el valor de este parámetro es <code>null</code>, entonces on se conectará automáticamente a ningun dispositivo
     * bluetooth.
     */
    private String mAutoConnectToDevice = null;
    
    /**
     * Hilo que se ejecutará miestras que mantenga la conexión activa.
     */
    private NbBtConnectThread mConnectThread;
    
    /**
     * Cantidad de dispositivos bluetooth encontrados.
     * Esta variable permite indica cuando se tiene algún o ningún dispositivo bluetooth listado.
     */
    private int mNewDevicesCount = 0;
    
    /**
     * Constructor.
     * Se encarga de la inicialización necesaria de los parámetros del objeto.
     * 
     * @param context	El conexto de la interfaz (por lo general de la actividad que instancia el objeto).
     */
    public NbBt(Context context) {
    	this(context, 0);
    }
    
    /**
     * Constructor.
     * Se encarga de la inicialización necesaria de los parámetros del objeto.
     * 
     * @param context	El contexto de la interfaz (por lo general de la actividad que instancia el objeto).
     * @param wait      Tiempo de espera entre envios de paquetes (en milisegundos)
     */
    public NbBt(Context context, long wait) {
    	super(wait);
    	mReceiver = new BaseComunicationBroadcastReceiver(this);	// Crear el broadcaster.
    	mContext = context; 
        mAutoConnectToDevice = null;
    }
    
    /**
     * Devuelve el nombre del dispositivo bluetooth al que se conectara automaticamente.
     * 
     * @return	Nombre del dispositivo al que se conectara automáticamente.
     */
    public String getAutoConnectToDevice(){
    	return mAutoConnectToDevice;
    }
    
    /**
     * Asigna un nombre para del dispositivo bluetooth al que se deberá conectar automáticamente.
     * 
     * @param autoConnectToDevice	Nombre del dispositivo bluetooth al que se conectará automaticamente.
     */
    public void setAutoConnectToDevice(String autoConnectToDevice){
    	mAutoConnectToDevice = autoConnectToDevice;
    }
    
    /**
     * Indica si hay un Bluetooth disponible.
     * 
     * @return Retorna <code>true</code> si existe un Bluetooth disponible <code>false</code> de lo contrario. 
     */
    public boolean isAvailable(){
    	return mBtAdapter !=null;
    }
    
    /**
     * Indica si el Bluetooth principal está encendido. 
     * 
	 * @return Retorna <code>true</code> si Bluetooth principal está encendido <code>false</code> de lo contrario. 
     */
    public boolean isEnabled(){
    	return mBtAdapter.isEnabled();
    }
    
    /**
     * Explora los dispositivos bluetooth pareados.
     * Este metodo generar un evento por cada disposiivo pareado, ofreciendo la posibilidad de agregarlos en una actividad.
     * Además se generará un evento de conexión si el nombre de algún dispositivo bluetooth es igual al configurado en la
     * conexión automática.
     */
    public boolean explorePairedDevices(){
    	return explorePairedDevices(mAutoConnectToDevice);
    }
    
    /**
     * Explora los dispositivos bluetooth pareados.
     * Este metodo generar un evento por cada disposiivo pareado, ofreciendo la posibilidad de agregarlos en una actividad.
     * Además se generará un evento de conexión si el nombre de algún dispositivo bluetooth es igual al pasado por parámetro.
     * 
     * @param autoConnectToDevice	Nombre del dispositivo bluetooth al que se conectará automáticamente.
     */
    public boolean explorePairedDevices(String autoConnectToDevice){
    	NbTrace.d(this, "addPairedDevices()");
    	
        // Obtener los dispositivos bluetooth pareados acutalmente.
        Set<BluetoothDevice> pairedDevices = mBtAdapter.getBondedDevices();
        
        // Si hay dispositivos bluetooth pareados entonces se recorren.
        if (pairedDevices.size() > 0) {
        	
        	// Generar evento que indica que existe al menos un dispositivo bluetooth pareado.
            sendMessage(NbComMsgEnum.BT_ANY_PAIRED_DEVICE, -1, -1, null, null);
            
            for (BluetoothDevice device : pairedDevices) {
            	
            	// Si el nombre del dispositivo bluetooth es igual al configurado en la conexión automática.
            	if(autoConnectToDevice != null && autoConnectToDevice.equals(device.getName())){
            		
            		// Se generará el evento de selección de dispositivo bluetooth y se detendrá el recorrindo.
            		sendMessage(NbComMsgEnum.BT_SELECTED_DEVICE, -1, -1, device, null);
            		return true;
            		
            	}else{
            		
            		// Generar el evento para agregar dispositivos bluetooth pareados.
            		sendMessage(NbComMsgEnum.BT_ADD_PAIRED_DEVICE, -1, -1, device, null);
            		
            	}
            }
            
        }else{
        	
        	// Generar evento que indica que no existe algún dispositivo bluetooth.
        	sendMessage(NbComMsgEnum.BT_NO_PAIRED_DEVICES, -1, -1, null, null);
        	
        }
        
        return false;
    	
    }
    
    /**
     * Inicia el escaneo de dispositivos bluetooth.
     * Para eso primero cancela busquedas previas, genera el evento correspondiente y por último se inicia el escaneo. 
     */
    public void doDiscovery() {
    	NbTrace.d(this, "doDiscovery()");
        
    	// Cancelar busquedas previas.
        if (mBtAdapter.isDiscovering()) mBtAdapter.cancelDiscovery();
        
        // Generar evento correspondiente.
        sendMessage(NbComMsgEnum.BT_DO_DISCOVERY_START, -1, -1, null, null);
        
        // Iniciar el escaneo.
        mBtAdapter.startDiscovery();
        
    }
    
    /**
     * Cancelar el proceso actual de escaneo de dispositivos bluetooth.
     * Si el manejador de Bluetooth se encuentra buscando nuevos dispositivos bluetooth, entonces se canelará dicha búsqueda.
     */
    public void cancelDiscovery(){
    	NbTrace.d(this, "cancelDiscovery()");
    	
    	// Si ya estamos buscando dispositivos bluetooth se detendrá.
    	if (mBtAdapter!=null && mBtAdapter.isDiscovering()) mBtAdapter.cancelDiscovery();
    	
    }
    
    /**
     * Cancela la conexión actual.
     * Este método permite cancelar la conexión actual. Si no esta conectado y existe un intendo de conexión entonces tambien
     * es cancelado. Tambien se cambia el estado de la conexión y se genera el evento correspondiente.
     */
    @Override
    public synchronized void disconnect(){
    	super.disconnect();
    	
        // Cancelar intendo de conexión anterior.
        if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}

        cancelConnectedThread();	// Cancelar hilo de conexión.
        
        // Cambiar el estado de la conexión.
        setState(NbComStateEnum.STATE_DISCONNECTED);
        
        // Generar evento correspondiente.
        sendMessage(NbComMsgEnum.DISCONNECT, -1 , -1, null, null);
        
    }
    
    public void connect(){
    	super.connect();
    }
    
    /**
     * Intenta conectarse a un dispositivo en modo no seguro y con el UUID por defecto.
     * @param address	Dirección del dispositivo al que se desea conectar
     */
    public void connect(String address){
    	connect(address, false, DEFAULT_BT_UUID);
    }
    
    /**
     * Comenzar un intendo de conexión.
     * Inicia un hilo donde se intentará establecer conexión con el dispositivo bluetooth externo. En este caso se obtendrá una
     * instancia <code>BluetoothDevice</code> mediante la dirección <code>address</code> ya si llamar el método que se encarga
     * de la conexión final.
     * 
     * @param address	Dirección del dispositivo Bluetooth al que se intentará conextarse.
     * @param secure	Valor booleano que indica si la conexión que se establecerá es segura o no.
     * @param btUuid 	UUID que se utilizará para crear el socket.
     */
    public void connect(String address, boolean secure, UUID btUuid){
    	connect(mBtAdapter.getRemoteDevice(address), secure, btUuid);
    }
    
    /**
     * Comenzar un intendo de conexión.
     * Esta función se creará un hilo para el intendo de conexión con el dispositivo Bluetooth. El tipo de conexión que se
     * intentará establecer puede ser segura o insegura. Para esto se cancela cualquier conexión y/o intento anterior de
     * conexión. De igual forma se establece el estado de conexión a <code>STATE_CONNECTING</code>. Por último se genera el
     * evento correspondiente.
     * 
     * @param device	Instancia del dispositivo bluetooth al que se intentará conectarse.
     * @param secure 	Valor booleano que indica si la conexión que se establecerá es segura o no.
     * @param btUuid 	UUID que se utilizará para crear el socket.
     */
    public synchronized void connect(BluetoothDevice device, boolean secure, UUID btUuid) {
    	NbTrace.d(this, "connect()");
    	
    	// Cancelar intendo de conexión anterior.
        if (getState() == NbComStateEnum.STATE_CONNECTING) {
            if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}
        }
        
        cancelConnectedThread();	// Cancelar conexiónes activas.
        
        // Iniciar el nuevo Thread para el intendo de conexión.
        mConnectThread = new NbBtConnectThread(this, device, secure, btUuid);
        mConnectThread.start();
        
        // Establecer estado como conectando.
        setState(NbComStateEnum.STATE_CONNECTING);
        
        // Generar el evento correspondiente.
        sendMessage(NbComMsgEnum.CONNECTING, secure?1:0, -1, device, null);
    	
    }
    
    /**
     * Iniciar el hilo de conexión.
     * Esta funcion se encarga de configurar la instancia de la clase para inicar el hilo de conexión. Para esto se asegura de
     * setar el valor del hilo de intento de conexión a <code>null</code> para evitar que sea cerrado.
     * 
     * @param socket 	Socket al que se logró conectar.
     * @param device 	Dispositivo bluetooth al que se conectó.
     * @param secure	Tipo de conexión realizada (segura o insegura).
     */
    public void initConnectedThread(BluetoothSocket socket, BluetoothDevice device, boolean secure){
    	
    	// Esto evita cerrar el socket de conexión para que pueda ser utilizado por el hilo de conexión.
    	synchronized (this) {
    		mConnectThread = null;
    	}
    	
    	// Iniciar hilo conexión.
    	connected(socket, device, secure);
    	
    }
    
    /**
     * Inicia el hilo para la conexión activa con el dispositivo bluetooth selecionado.
     * 
     * @param socket	Sokect de conexión creado.
     * @param device  	Dispositivo bluetooth al que se conectó.
     * @param secure  	Indica si el tipo de conexión es segura o no.
     */
    public synchronized void connected(BluetoothSocket socket, BluetoothDevice device, boolean secure){
    	NbTrace.d(this, "connected()");
    	
        // Cancelar intendo de conexión anterior.
//        if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}
        mConnectThread = null;

        cancelConnectedThread();	// Cancela el hilo de conexión actual si existe.
        
        // Genera e inicia un nuevo hilo de conexión activa. para el socket nuevo.
        startNewConnectedThread(new NbBtConnectedThread(this, socket));
        
        // Cambia el estado de la conexión a conectado.
        setState(NbComStateEnum.STATE_CONNECTED);
        
        // Genera el evento correspondiente.
        sendMessage(NbComMsgEnum.CONNECTED, secure?1:0, -1, device, null);
        
    }
    
    /**
     * Registra el <code>BaseComunicationBroadcastReceiver</code> en el contexto.
     */
    public void registerReceiver(){
    	NbTrace.d(this, "registerReceiver()");
    	
    	IntentFilter filter = new IntentFilter();
    	
    	// Registrar broadcasts para cuando se encuentre un elemento.
		filter.addAction(BluetoothDevice.ACTION_FOUND);
    	
    	// Registrar broadcasts para cuando de termine el escaneo.
		filter.addAction(BluetoothAdapter.ACTION_DISCOVERY_FINISHED);
		
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
     * Para esta especificación recibe mensajes para cuando se encuentra un dispositivo bluetooth y cuando se finaliza el
     * escaneo.
     */
    public void onReciveBroadcastReciver(Context context, Intent intent){
    	NbTrace.d(this, "onReciveBroadcastReciver()");
    	
    	String action = intent.getAction();

        // Caundo se descubre un dispositivo bluetooth.
        if (BluetoothDevice.ACTION_FOUND.equals(action)) {
        	
            // Obtiene la instancia del dispositivo bluetooth desde el intent.
            BluetoothDevice device = intent.getParcelableExtra(BluetoothDevice.EXTRA_DEVICE);
            
            // Si el dispositivo bluetooth ya está pareado se pasa, porque ya de debió agregar como un dispositivo bluetooth
            // pareado.
            if (device.getBondState() != BluetoothDevice.BOND_BONDED) {
            	
            	// Incrementa la cantidad de dispositivos bluetooth encontrados.
            	mNewDevicesCount++;
            	
            	// Generar evento correspondiente
            	sendMessage(NbComMsgEnum.BT_ADD_NEW_DEVICE, -1, -1, device, null);
            	
            }
            
        // Cuando finalice la búsqueda de dispositivos bluetooth.
        } else if (BluetoothAdapter.ACTION_DISCOVERY_FINISHED.equals(action)) {
        	
        	// Generar evento correspondiente
        	sendMessage(NbComMsgEnum.BT_DO_DISCOVERY_FINISH, -1, -1, null, null);
        	
        	// Si no se ha encontrado ningún dispositivo bluetooth.
            if (mNewDevicesCount == 0) {
            	
            	// Generar evento para indica que no se controntró ningun dispositivo bluetooth.
            	sendMessage(NbComMsgEnum.BT_NO_NEW_DEVICES_FOUND, -1, -1, null, null);
            	
            }
            
        }
    }
    
}
