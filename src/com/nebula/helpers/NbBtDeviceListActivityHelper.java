/* ========================================================================
 * Nebula Android NbBtDeviceListHelper: NbCom v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula.helpers;

import com.nebula.bt.NbBt;
import com.nebula.bt.NbBtHandler;
import com.nebula.com.NbComHandler;
import com.nebula.R;

import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.app.Activity;
import android.bluetooth.BluetoothDevice;
import android.content.Intent;

public class NbBtDeviceListActivityHelper extends Activity{
	
	private ArrayAdapter<String> mPairedDevicesArrayAdapter;
	private ArrayAdapter<String> mNewDevicesArrayAdapter;
	
	public TextResources mTxts = mDummyTxts;
	
	public NbBt com;
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.nb_bt_device_list_activity);
		
		com = new NbBt(this);
		
		// Setup the window
//      requestWindowFeature(Window.FEATURE_INDETERMINATE_PROGRESS);
      
		mPairedDevicesArrayAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1);
		mNewDevicesArrayAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1);
		
		// Initialize the button to perform device discovery
		((Button) findViewById(R.id.button_scan)).setOnClickListener(new OnClickListener() {
			public void onClick(View v) {
				com.doDiscovery();
			}
		});

		// Find and set up the ListView for paired devices
		ListView pairedListView = (ListView) findViewById(R.id.paired_devices);
		pairedListView.setAdapter(mPairedDevicesArrayAdapter);
		pairedListView.setOnItemClickListener(mOnItemClickListener);

		// Find and set up the ListView for newly discovered devices
		ListView newDevicesListView = (ListView) findViewById(R.id.new_devices);
		newDevicesListView.setAdapter(mNewDevicesArrayAdapter);
		newDevicesListView.setOnItemClickListener(mOnItemClickListener);
		
		// Set result CANCELED in case the user backs out
		setResult(Activity.RESULT_CANCELED);
      
		com.setAutoConnectToDevice(getIntent().getExtras().getString(NbBt.EXTRA_AUTO_CONNECT_TO_DEVICE));
		com.addHandler(mHandler);
		com.registerReceiver();
		com.explorePairedDevices();
		refreshTexts();
	}
	
	@Override
	protected void onDestroy() {
		// TODO Auto-generated method stub
		super.onDestroy();
        
        // Make sure we're not doing discovery anymore
        com.cancelDiscovery();
        
    	// Unregister broadcast listeners
        com.unregisterReceiver();
    	
	}
	
	protected void refreshTexts(){
		((TextView)findViewById(R.id.title_paired_devices)).setText(mTxts.pairedDevices());
		((TextView)findViewById(R.id.title_new_devices)).setText(mTxts.othersAvailableDevices());
		((Button)findViewById(R.id.button_scan)).setText(mTxts.scan());
	}
    
    public void selectDevice(String deviceAddress){
    	
    	// Cancel discovery because it's costly and we're about to connect
    	com.cancelDiscovery();
        
    	// Create the result Intent and include the MAC address
        Intent intent = new Intent();
        intent.putExtra(NbBt.EXTRA_BT_DEVICE_ADDRESS, deviceAddress);

        // Set result and finish this Activity
        setResult(Activity.RESULT_OK, intent);
        finish();
        
    }
    
    private final OnItemClickListener mOnItemClickListener = new OnItemClickListener(){
    	
        // The on-click listener for all devices in the ListViews
        public void onItemClick(AdapterView<?> av, View v, int arg2, long arg3) {
        	
            // Get the device MAC address, which is the last 17 chars in the View
            String info = ((TextView) v).getText().toString();
            selectDevice(info.substring(info.length() - 17));
            
        }
    	
    };
	
	public void setTextResources(TextResources txts){
		mTxts = txts == null? mDummyTxts : txts;
		refreshTexts();
	}
	
	public interface TextResources{
		public String dontExistsPairedDevices();
		public String scanning();
		public String selectDeviceToConnect();
		public String dontDevicesFounded();
		public String pairedDevices();
		public String scan();
		public String othersAvailableDevices();
	}
	
	private static final TextResources mDummyTxts = new TextResources(){
		public String dontExistsPairedDevices() { return "Don't exists paired devices"; }
		public String scanning() { return "Scanning"; }
		public String selectDeviceToConnect() { return "Select devices to connect"; }
		public String dontDevicesFounded() { return "Don't devices founded"; }
		public String pairedDevices() { return "Paired devices"; }
		public String scan() { return "Scan"; }
		public String othersAvailableDevices() { return "Others devices available"; }
	};
    
    // The Handler that gets information back from the BluetoothChatService
    private final NbComHandler mHandler = new NbBtHandler(){
    	
        @Override
        public void anyPairedDevices(){
        	findViewById(R.id.title_paired_devices).setVisibility(View.VISIBLE);
    	}
    	
        @Override
    	public void noPairedDevices(){
        	mPairedDevicesArrayAdapter.add(mTxts.dontExistsPairedDevices());
    	}
    	
        @Override
    	public void doDiscoveryStart(){
        	setProgressBarIndeterminateVisibility(true);
        	setTitle(mTxts.scanning());
	        findViewById(R.id.title_new_devices).setVisibility(View.VISIBLE);
	        findViewById(R.id.button_scan).setVisibility(View.GONE);
    	}
    	
        @Override
    	public void doDiscoveryFinished(){
        	setProgressBarIndeterminateVisibility(false);
        	setTitle(mTxts.selectDeviceToConnect());
    	}
    	
        @Override
    	public void noNewDevicesFound(){
        	mNewDevicesArrayAdapter.add(mTxts.dontDevicesFounded());
    	}
    	
        @Override
    	public void addPairedDevices(BluetoothDevice device){
        	mPairedDevicesArrayAdapter.add(device.getName() + "\n" + device.getAddress());
    	}
    	
        @Override
    	public void addNewDevices(BluetoothDevice device){
        	mNewDevicesArrayAdapter.add(device.getName() + "\n" + device.getAddress());
    	}
    	
        @Override
    	public void selectedDevice(BluetoothDevice device){
        	selectDevice(device.getAddress());
    	}
        
    };

}
