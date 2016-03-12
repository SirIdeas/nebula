/**
 * Proyecto N�bula
 *
 * @author Alex J. Rond�n <arondn2@gmail.com>
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
 * Clase para comunicaci�n Bluetooth.
 * Especificaci�n de la clase <code>BaseComunicacion</code> para realizar comunicaciones por Bluetoth manteniendo un feedback
 * del tipo Serial. Contiene m�todos para lista dispositivos bluetooth dentro del alcance, conectarse, entre otros.
 * 
 */
public class NbBt extends NbCom {
	
	// Unique UUID for this application
    public static final UUID DEFAULT_BT_UUID = UUID.fromString("00001101-0000-1000-8000-00805F9B34FB");
	
	/**
	 * Valor booleano que indica si el tipo de conexi�n es segura o insegura.
	 */
	public static final String EXTRA_IS_SECURE 				= "com.nebula.comunication.bt.BtComunication.IS_SECURE";
	
	/**
	 * String con la direcci�n �nica del dispositivo bluetooth selecionado para la conexi�n.
	 */
	public static final String EXTRA_BT_DEVICE_ADDRESS		= "com.nebula.comunication.bt.BtComunication.BLUETOOTH_DEVICE_ADDRESS";
	
	/**
	 * String con el nombre al que se desea conectar de forma autom�ticamente sin abrir el activity para listar los
	 * dispositivos bluetooth.
	 */
	public static final String EXTRA_AUTO_CONNECT_TO_DEVICE	= "com.nebula.comunication.bt.BtComunication.AUTO_CONNECT_TO_DEVICE";
	
    /**
     * Administrador de bluetooth.
     * Con esta instancia podemos realizar las acciones correspondientes a la comunicaci�n bluetooth como obtener dispositivos
     * bluetooth pareados, iniciar y terminar el escaneo de dispositivos bluetooth, entre otros. 
     */
    private final BluetoothAdapter mBtAdapter = BluetoothAdapter.getDefaultAdapter();

    /**
     * <code>BaseComunicationBroadcastReceiver</code> que se encargar�n de recibir los mensajes cuando se detecte un nuevo
     * dispositivo bluetooth y cuando se termine un escaneo.
     */
    private final BaseComunicationBroadcastReceiver mReceiver;
    
    /**
     * Contexto en el cual se registrar�n y desregistrar�n los <code>BaseComunicationBroadcastReceiver</code> correspondientes
     * a la comunicaci�n bluetooth.
     */
    private Context mContext = null;
    
    /**
     * Nombre del dispositivo bluetooth al que se desea conectar automaticamente.
     * Si el valor de este par�metro es <code>null</code>, entonces on se conectar� autom�ticamente a ningun dispositivo
     * bluetooth.
     */
    private String mAutoConnectToDevice = null;
    
    /**
     * Hilo que se ejecutar� miestras que mantenga la conexi�n activa.
     */
    private NbBtConnectThread mConnectThread;
    
    /**
     * Cantidad de dispositivos bluetooth encontrados.
     * Esta variable permite indica cuando se tiene alg�n o ning�n dispositivo bluetooth listado.
     */
    private int mNewDevicesCount = 0;
    
    /**
     * Constructor.
     * Se encarga de la inicializaci�n necesaria de los par�metros del objeto.
     * 
     * @param context	El conexto de la interfaz (por lo general de la actividad que instancia el objeto).
     */
    public NbBt(Context context) {
    	this(context, 0);
    }
    
    /**
     * Constructor.
     * Se encarga de la inicializaci�n necesaria de los par�metros del objeto.
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
     * @return	Nombre del dispositivo al que se conectara autom�ticamente.
     */
    public String getAutoConnectToDevice(){
    	return mAutoConnectToDevice;
    }
    
    /**
     * Asigna un nombre para del dispositivo bluetooth al que se deber� conectar autom�ticamente.
     * 
     * @param autoConnectToDevice	Nombre del dispositivo bluetooth al que se conectar� automaticamente.
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
     * Indica si el Bluetooth principal est� encendido. 
     * 
	 * @return Retorna <code>true</code> si Bluetooth principal est� encendido <code>false</code> de lo contrario. 
     */
    public boolean isEnabled(){
    	return mBtAdapter.isEnabled();
    }
    
    /**
     * Explora los dispositivos bluetooth pareados.
     * Este metodo generar un evento por cada disposiivo pareado, ofreciendo la posibilidad de agregarlos en una actividad.
     * Adem�s se generar� un evento de conexi�n si el nombre de alg�n dispositivo bluetooth es igual al configurado en la
     * conexi�n autom�tica.
     */
    public boolean explorePairedDevices(){
    	return explorePairedDevices(mAutoConnectToDevice);
    }
    
    /**
     * Explora los dispositivos bluetooth pareados.
     * Este metodo generar un evento por cada disposiivo pareado, ofreciendo la posibilidad de agregarlos en una actividad.
     * Adem�s se generar� un evento de conexi�n si el nombre de alg�n dispositivo bluetooth es igual al pasado por par�metro.
     * 
     * @param autoConnectToDevice	Nombre del dispositivo bluetooth al que se conectar� autom�ticamente.
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
            	
            	// Si el nombre del dispositivo bluetooth es igual al configurado en la conexi�n autom�tica.
            	if(autoConnectToDevice != null && autoConnectToDevice.equals(device.getName())){
            		
            		// Se generar� el evento de selecci�n de dispositivo bluetooth y se detendr� el recorrindo.
            		sendMessage(NbComMsgEnum.BT_SELECTED_DEVICE, -1, -1, device, null);
            		return true;
            		
            	}else{
            		
            		// Generar el evento para agregar dispositivos bluetooth pareados.
            		sendMessage(NbComMsgEnum.BT_ADD_PAIRED_DEVICE, -1, -1, device, null);
            		
            	}
            }
            
        }else{
        	
        	// Generar evento que indica que no existe alg�n dispositivo bluetooth.
        	sendMessage(NbComMsgEnum.BT_NO_PAIRED_DEVICES, -1, -1, null, null);
        	
        }
        
        return false;
    	
    }
    
    /**
     * Inicia el escaneo de dispositivos bluetooth.
     * Para eso primero cancela busquedas previas, genera el evento correspondiente y por �ltimo se inicia el escaneo. 
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
     * Si el manejador de Bluetooth se encuentra buscando nuevos dispositivos bluetooth, entonces se canelar� dicha b�squeda.
     */
    public void cancelDiscovery(){
    	NbTrace.d(this, "cancelDiscovery()");
    	
    	// Si ya estamos buscando dispositivos bluetooth se detendr�.
    	if (mBtAdapter!=null && mBtAdapter.isDiscovering()) mBtAdapter.cancelDiscovery();
    	
    }
    
    /**
     * Cancela la conexi�n actual.
     * Este m�todo permite cancelar la conexi�n actual. Si no esta conectado y existe un intendo de conexi�n entonces tambien
     * es cancelado. Tambien se cambia el estado de la conexi�n y se genera el evento correspondiente.
     */
    @Override
    public synchronized void disconnect(){
    	super.disconnect();
    	
        // Cancelar intendo de conexi�n anterior.
        if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}

        cancelConnectedThread();	// Cancelar hilo de conexi�n.
        
        // Cambiar el estado de la conexi�n.
        setState(NbComStateEnum.STATE_DISCONNECTED);
        
        // Generar evento correspondiente.
        sendMessage(NbComMsgEnum.DISCONNECT, -1 , -1, null, null);
        
    }
    
    public void connect(){
    	super.connect();
    }
    
    /**
     * Intenta conectarse a un dispositivo en modo no seguro y con el UUID por defecto.
     * @param address	Direcci�n del dispositivo al que se desea conectar
     */
    public void connect(String address){
    	connect(address, false, DEFAULT_BT_UUID);
    }
    
    /**
     * Comenzar un intendo de conexi�n.
     * Inicia un hilo donde se intentar� establecer conexi�n con el dispositivo bluetooth externo. En este caso se obtendr� una
     * instancia <code>BluetoothDevice</code> mediante la direcci�n <code>address</code> ya si llamar el m�todo que se encarga
     * de la conexi�n final.
     * 
     * @param address	Direcci�n del dispositivo Bluetooth al que se intentar� conextarse.
     * @param secure	Valor booleano que indica si la conexi�n que se establecer� es segura o no.
     * @param btUuid 	UUID que se utilizar� para crear el socket.
     */
    public void connect(String address, boolean secure, UUID btUuid){
    	connect(mBtAdapter.getRemoteDevice(address), secure, btUuid);
    }
    
    /**
     * Comenzar un intendo de conexi�n.
     * Esta funci�n se crear� un hilo para el intendo de conexi�n con el dispositivo Bluetooth. El tipo de conexi�n que se
     * intentar� establecer puede ser segura o insegura. Para esto se cancela cualquier conexi�n y/o intento anterior de
     * conexi�n. De igual forma se establece el estado de conexi�n a <code>STATE_CONNECTING</code>. Por �ltimo se genera el
     * evento correspondiente.
     * 
     * @param device	Instancia del dispositivo bluetooth al que se intentar� conectarse.
     * @param secure 	Valor booleano que indica si la conexi�n que se establecer� es segura o no.
     * @param btUuid 	UUID que se utilizar� para crear el socket.
     */
    public synchronized void connect(BluetoothDevice device, boolean secure, UUID btUuid) {
    	NbTrace.d(this, "connect()");
    	
    	// Cancelar intendo de conexi�n anterior.
        if (getState() == NbComStateEnum.STATE_CONNECTING) {
            if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}
        }
        
        cancelConnectedThread();	// Cancelar conexi�nes activas.
        
        // Iniciar el nuevo Thread para el intendo de conexi�n.
        mConnectThread = new NbBtConnectThread(this, device, secure, btUuid);
        mConnectThread.start();
        
        // Establecer estado como conectando.
        setState(NbComStateEnum.STATE_CONNECTING);
        
        // Generar el evento correspondiente.
        sendMessage(NbComMsgEnum.CONNECTING, secure?1:0, -1, device, null);
    	
    }
    
    /**
     * Iniciar el hilo de conexi�n.
     * Esta funcion se encarga de configurar la instancia de la clase para inicar el hilo de conexi�n. Para esto se asegura de
     * setar el valor del hilo de intento de conexi�n a <code>null</code> para evitar que sea cerrado.
     * 
     * @param socket 	Socket al que se logr� conectar.
     * @param device 	Dispositivo bluetooth al que se conect�.
     * @param secure	Tipo de conexi�n realizada (segura o insegura).
     */
    public void initConnectedThread(BluetoothSocket socket, BluetoothDevice device, boolean secure){
    	
    	// Esto evita cerrar el socket de conexi�n para que pueda ser utilizado por el hilo de conexi�n.
    	synchronized (this) {
    		mConnectThread = null;
    	}
    	
    	// Iniciar hilo conexi�n.
    	connected(socket, device, secure);
    	
    }
    
    /**
     * Inicia el hilo para la conexi�n activa con el dispositivo bluetooth selecionado.
     * 
     * @param socket	Sokect de conexi�n creado.
     * @param device  	Dispositivo bluetooth al que se conect�.
     * @param secure  	Indica si el tipo de conexi�n es segura o no.
     */
    public synchronized void connected(BluetoothSocket socket, BluetoothDevice device, boolean secure){
    	NbTrace.d(this, "connected()");
    	
        // Cancelar intendo de conexi�n anterior.
//        if (mConnectThread != null) {mConnectThread.cancel(); mConnectThread = null;}
        mConnectThread = null;

        cancelConnectedThread();	// Cancela el hilo de conexi�n actual si existe.
        
        // Genera e inicia un nuevo hilo de conexi�n activa. para el socket nuevo.
        startNewConnectedThread(new NbBtConnectedThread(this, socket));
        
        // Cambia el estado de la conexi�n a conectado.
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
     * Para esta especificaci�n recibe mensajes para cuando se encuentra un dispositivo bluetooth y cuando se finaliza el
     * escaneo.
     */
    public void onReciveBroadcastReciver(Context context, Intent intent){
    	NbTrace.d(this, "onReciveBroadcastReciver()");
    	
    	String action = intent.getAction();

        // Caundo se descubre un dispositivo bluetooth.
        if (BluetoothDevice.ACTION_FOUND.equals(action)) {
        	
            // Obtiene la instancia del dispositivo bluetooth desde el intent.
            BluetoothDevice device = intent.getParcelableExtra(BluetoothDevice.EXTRA_DEVICE);
            
            // Si el dispositivo bluetooth ya est� pareado se pasa, porque ya de debi� agregar como un dispositivo bluetooth
            // pareado.
            if (device.getBondState() != BluetoothDevice.BOND_BONDED) {
            	
            	// Incrementa la cantidad de dispositivos bluetooth encontrados.
            	mNewDevicesCount++;
            	
            	// Generar evento correspondiente
            	sendMessage(NbComMsgEnum.BT_ADD_NEW_DEVICE, -1, -1, device, null);
            	
            }
            
        // Cuando finalice la b�squeda de dispositivos bluetooth.
        } else if (BluetoothAdapter.ACTION_DISCOVERY_FINISHED.equals(action)) {
        	
        	// Generar evento correspondiente
        	sendMessage(NbComMsgEnum.BT_DO_DISCOVERY_FINISH, -1, -1, null, null);
        	
        	// Si no se ha encontrado ning�n dispositivo bluetooth.
            if (mNewDevicesCount == 0) {
            	
            	// Generar evento para indica que no se controntr� ningun dispositivo bluetooth.
            	sendMessage(NbComMsgEnum.BT_NO_NEW_DEVICES_FOUND, -1, -1, null, null);
            	
            }
            
        }
    }
    
}
