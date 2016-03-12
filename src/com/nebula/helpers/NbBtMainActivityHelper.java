/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

package com.nebula.helpers;

import java.util.UUID;

import com.nebula.R;
import com.nebula.bt.NbBt;
import com.nebula.bt.NbBtHandler;
import com.nebula.com.NbComHandler;
import com.nebula.com.NbComStateEnum;
import com.nebula.sketch.NbSketch;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.ProgressDialog;
import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

@SuppressLint("NewApi")
public class NbBtMainActivityHelper extends Activity{

	// Instancia para la comunicación
	private NbBt com;
	
	// Grupo de componentes
	private NbSketch sketch = new NbSketch(){
		private static final long serialVersionUID = -2410315236868140995L;
		public void loop() {
			NbBtMainActivityHelper.this.loop();
		};
	};
	
	// Titulo principal de la ventana;
	private CharSequence title;
    
    // 
	public static final int DEFAULT_REQUEST_ENABLE_BT_SECURE = 1;
	public static final int DEFAULT_REQUEST_ENABLE_BT_INSECURE = 2;
	public static final int DEFAULT_REQUEST_CONNECT_DEVICE_SECURE = 3;
	public static final int DEFAULT_REQUEST_CONNECT_DEVICE_INSECURE = 4;
	
	private int mRequestEnabledBtSecure = DEFAULT_REQUEST_ENABLE_BT_SECURE;
	private int mRequestEnabledBtInsecure = DEFAULT_REQUEST_ENABLE_BT_INSECURE;
	private int mRequestConnectDeviceSecure = DEFAULT_REQUEST_CONNECT_DEVICE_SECURE;
	private int mRequestConnectDeviceInsecure = DEFAULT_REQUEST_CONNECT_DEVICE_INSECURE;
	
	private UUID mBtUuidSecure = NbBt.DEFAULT_BT_UUID;
	private UUID mBtUuidInsecure = NbBt.DEFAULT_BT_UUID;
	private Class<?> mBtDeviceListActivityClass = null;
	
	private boolean mSecure = false;
	
	public TextResources mTxts = mDummyTxts;
	
	public void setBtDeviceListActivityClass(Class<?> cls){ mBtDeviceListActivityClass = cls; }
	public void setBtUuidSecure(UUID btUuidSecure){ mBtUuidSecure = btUuidSecure; }
	public void setBtUuidInsecure(UUID btUuidInsecure){ mBtUuidInsecure = btUuidInsecure; }
	public void setRequestEnabledBtSecure(int requestEnabledBtSecure){ mRequestEnabledBtSecure = requestEnabledBtSecure; }
	public void setRequestEnabledBtInsecure(int requestEnabledBtInsecure){ mRequestEnabledBtInsecure = requestEnabledBtInsecure; }
	public void setRequestConnectDeviceSecure(int requestConnectDeviceSecure){ mRequestConnectDeviceSecure = requestConnectDeviceSecure; }
	public void setRequestConnectDeviceInsecure(int requestConnectDeviceInsecure){ mRequestConnectDeviceInsecure = requestConnectDeviceInsecure; }
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Obtener el titulo inicial para poder cambiar
		// El titulo de la actividad posteriormente
		title = getTitle();
		
		// Crear instancia de la comunicación
		com = new NbBt(this);
		
		com.addHandler(mHandler);
		
		// asignar sketch
		sketch.setCom(com);
		
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.nb_default_bt_activity, menu);
		
		return true;
	}
	
	// Para la visibilidad de las opciones
	@Override
	public boolean onPrepareOptionsMenu(Menu menu) {
		
		// Asignar titulo
		getWindow().setTitle(String.format("%s (%s)", title, getResources().getString(
				com.isConnect()? R.string.__nb_connected :
					com.isConnecting()? R.string.__nb_connecting :
						R.string.__nb_disconnected
		)));
		
		// Visibilidad de menus 
		menu.findItem(R.id.__nb_connect).setVisible(com.isDisconnect());
		menu.findItem(R.id.__nb_disconnect).setVisible(com.isConnect());
		
		return super.onPrepareOptionsMenu(menu);
	}
	
	// Acciones del menu
	@Override
	public boolean onMenuItemSelected(int featureId, MenuItem item) {
		int id = item.getItemId();
		if(id == R.id.__nb_connect) {
			connect();	// Establecer conexión
		}else if(id == R.id.__nb_disconnect){
			com.disconnect();	// Desconectar
		}
		return super.onMenuItemSelected(featureId, item);
	}
	
	public void onActivityResult(int requestCode, int resultCode, Intent data){
		
		setSecure(requestCode == mRequestConnectDeviceSecure);
		
		if(requestCode != 0){
			if(requestCode == mRequestConnectDeviceSecure || requestCode == mRequestConnectDeviceInsecure){
				 // When DeviceListActivity returns with a device to connect
	            if (resultCode == Activity.RESULT_OK){
	            	
	            	connectToDevice(data.getExtras().getString(NbBt.EXTRA_BT_DEVICE_ADDRESS));
	            	
	            }else{
	            	Toast.makeText(this, mTxts.deviceConnectCanceled(), Toast.LENGTH_SHORT).show();
	            }
			}else if(requestCode == mRequestEnabledBtSecure || requestCode == mRequestEnabledBtInsecure){
	            // When the request to enable Bluetooth returns
	            if (resultCode == Activity.RESULT_OK){
	                // Bluetooth is now enabled, so set up a chat session
	            	connect(requestCode == mRequestEnabledBtSecure);
	            }else{
	                // User did not enable Bluetooth or an error occurred
	            	Toast.makeText(this, mTxts.btNotEnabled(), Toast.LENGTH_SHORT).show();
	            }
			}
		}
	}

	@Override
	protected void onDestroy() {
		com.disconnect();
		super.onDestroy();
	}
	
	public void loop(){
		
	}
	
	// Devuelve la comunicacion actual
	public NbBt getCom() {
		return com;
	}
	
	// Devuelve el sketch de la actividad
	public NbSketch getSketch(){
		return sketch;
	}
	
	public void setTextResources(TextResources txts){
		mTxts = txts == null? mDummyTxts : txts;
	}
	
	public void setSecure(boolean secure){
		mSecure = secure;
	}
	
	public void connect(){
		com.connect();
		
		if(!com.isAvailable()){
			
			Toast.makeText(this, mTxts.btUnsupport(), Toast.LENGTH_SHORT).show();
			
		}else if(!com.isEnabled()){
			
			int requestCode = mSecure? mRequestEnabledBtSecure : mRequestEnabledBtInsecure;
			if(requestCode == 0);
			
			Intent intent = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
			startActivityForResult(intent, requestCode);
		
		}else if(!com.isConnect()){
			
			int requestCode = mSecure? mRequestConnectDeviceSecure : mRequestConnectDeviceInsecure;
			if(requestCode == 0);
			
			if(!com.explorePairedDevices() && mBtDeviceListActivityClass != null){
				Intent intent = new Intent(this, mBtDeviceListActivityClass);
	        	intent.putExtra(NbBt.EXTRA_AUTO_CONNECT_TO_DEVICE, com.getAutoConnectToDevice());
	        	startActivityForResult(intent, requestCode);	
			}
			
		}else{
			
			Toast.makeText(this, mTxts.alreadyConnect(), Toast.LENGTH_SHORT).show();
			
		}

	}
	
	public void connect(boolean secure){
		setSecure(secure);
		connect();
	}
	
	private void connectToDevice(String deviceAdreess){
		com.connect(deviceAdreess, mSecure, mSecure? mBtUuidSecure : mBtUuidInsecure);
	}
	
	private ProgressDialog createProccessDialog(BluetoothDevice device){
		return ProgressDialog.show(this, mTxts.connecting(), String.format(mTxts.formatItemDevice(), device.getName() , device.getAddress()), true, false);
	}
	
	@SuppressLint("HandlerLeak")
	private final NbComHandler mHandler = new NbBtHandler(){
		
		private ProgressDialog mProgressDialog;
		
		@Override
		public void stateChanged(NbComStateEnum state){
			invalidateOptionsMenu();
		}
		
		@Override
    	public void connecting(Object obj){
    		mProgressDialog = createProccessDialog((BluetoothDevice)obj);
    	}
    	
    	@Override
    	public void connected(Object obj){
    		mProgressDialog.dismiss();
    	}
    	
    	@Override
    	public void connectionFailed(){
    		mProgressDialog.dismiss();
    	}
    	
    	public void selectedDevice(BluetoothDevice device) {
    		connectToDevice(device.getAddress());
    	};
    	
	};
	
	public interface TextResources{
		public String deviceConnectCanceled();
		public String btNotEnabled();
		public String btUnsupport();
		public String alreadyConnect();
		public String connecting();
		public String formatItemDevice();
	}
	
	private static final TextResources mDummyTxts = new TextResources(){
		public String deviceConnectCanceled() { return "Device connect canceled"; }
		public String btNotEnabled() { return "Bluetooth no enabled"; }
		public String btUnsupport() { return "Bluetooth unsupport"; }
		public String alreadyConnect() { return "Already connected"; }
		public String connecting() { return "Connecting"; }
		public String formatItemDevice() { return "%s : %s"; }
	};
	
}
