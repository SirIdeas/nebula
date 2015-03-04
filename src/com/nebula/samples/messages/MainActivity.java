package com.nebula.samples.messages;

import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;

import com.nebula.helpers.NbBtMainActivityHelper;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.NbButton;
import com.nebula.sketch.cmp.NbLedDigital;
import com.nebula.sketch.cmp.NbLiquidCrystal;
import com.nebula.sketch.cmp.core.NbCmp;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.telephony.SmsMessage;
import android.util.Log;
import android.widget.Toast;

public class MainActivity extends NbBtMainActivityHelper {

	private static final int LCD_COLS 	= 16;
	public static final int ID_LCD_1  	= 3;
	public static final int INIT_LCD  	= NbDialect.__LAST_MSG_CODE + 2;
	
	private SmsReceiver mReceiver;
	
	// ID de la LCD igual que en el Sketch
	// El segundo parametro es la cantidad de caracteres en una fila
	private NbLiquidCrystal lcd = new NbLiquidCrystal(ID_LCD_1, LCD_COLS);	// LCD de 16x2
	
	private NbButton boton = new NbButton(41);
	private NbLedDigital led = new NbLedDigital(8);
	
	private Vector<String> mensajes = new Vector<>();
	
	private Timer timerLed = null;
	private Timer timerMarkesina = null;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		// Inicializar LCD
		getSketch().addSetupByte(INIT_LCD);
		
		getSketch().connect(lcd);
		getSketch().connect(led);
		getSketch().connect(boton);
		
		// Indicar la actividad a utilizar para listar los accesorios BT
		setBtDeviceListActivityClass(BtDevicesListActivity.class);
		
		getCom().setAutoConnectToDevice("NebulaBoard");
		
		boton.setOnValueChangeListener(new NbCmp.OnValueChangeListener() {
			@Override
			public void onValueChange(NbCmp component, int newValue, int oldValue) {
				if(oldValue>0)
					showNextMessage();
			}
		});
		
		mReceiver = new SmsReceiver();
		
	}
	
	@Override
	protected void onResume() {
		// Registrar broadcaster
		IntentFilter filter = new IntentFilter();
		filter.addAction("android.provider.Telephony.SMS_RECEIVED");
		
		registerReceiver(mReceiver, filter);
		super.onResume();
	}
	
	@Override
	protected void onPause() {
		unregisterReceiver(mReceiver);
		super.onPause();
	}
	
	@Override
	protected void onDestroy() {
		unregisterReceiver(mReceiver);
		super.onDestroy();
	}
	
	// Agregar mensaje y comenzar el parpadeo del led
	private void encolarMensaje(String adress, String body) {
		mensajes.add(adress);
		mensajes.add(body);
		initLedParpadear();
	}
	
	// Hacer parpadear led
	private void ledParpadear(){
		led.high();	delay(100);
		led.low(); 	delay(100);
		led.high();	delay(100);
		led.low();
	}
	
	// Funcion para inicar el parpadel del led
	private void initLedParpadear(){
		if(timerLed != null) return;
		timerLed = new Timer();
		timerLed.scheduleAtFixedRate(new TimerTask() {
			@Override
			public void run() {
				if(mensajes.size()>0)
					ledParpadear();
			}
		}, 0, 2000);
	}
	
	private void stopLedParpadeo(){
		if(timerLed!=null){
			timerLed.cancel();
			timerLed = null;
		}
		led.low(); // Apagar led
	}
	
	// Mostrar el siguiente mensaje
	private void showNextMessage(){
		
		stopMarkesina();
		
		if(mensajes.size()==0)
			return;
		
		// Obtener mensajes
		String mensaje = mensajes.lastElement();
		mensajes.removeElementAt(mensajes.size()-1);
		String address = mensajes.lastElement();
		mensajes.removeElementAt(mensajes.size()-1);
		
		// Mostrar mensaje
		lcd.clear();
		lcd.setCursor(0, 0);
		lcd.print(String.format("%-16s", address));
		initMarkesina(mensaje);
		
		// Detener parpadeo
		if(mensajes.size()==0)
			stopLedParpadeo();
		
	}
	
	// 
	private void mostrarMarkesina(String msg, int i){
		lcd.setCursor(0, 1);
		lcd.print(String.format("%-16s", msg.substring(i)));
	}
	
	// Funcion para inicar el parpadel del led
	private void initMarkesina(final String msg){
		if(timerMarkesina != null) return;
		mostrarMarkesina(msg, 0);
		timerMarkesina = new Timer();
		timerMarkesina.scheduleAtFixedRate(new TimerTask() {
			int i = 0;
			@Override
			public void run() {
				i = (i+1) % msg.length();
				mostrarMarkesina(msg, i);
			}
		}, 4000, 500);
	}
	
	private void stopMarkesina(){
		if(timerMarkesina!=null){
			timerMarkesina.cancel();
			timerMarkesina = null;
		}
		lcd.clear(); // Apagar led
	}
	
	private void delay(int milli) {
		try {
			Thread.sleep(milli);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public class SmsReceiver extends BroadcastReceiver {

	    public static final String SMS_EXTRA_NAME = "pdus";
	    
	    @Override
	    public void onReceive( Context context, Intent intent )
	    {
	        // Get SMS map from Intent
	        Bundle extras = intent.getExtras();

	        String messages = "";
	        String address = "";
	        
	        if ( extras != null )
	        {
	            // Get received SMS array
	            Object[] smsExtra = (Object[]) extras.get( SMS_EXTRA_NAME );

	            for ( int i = 0; i < smsExtra.length; ++i )
	            {
	                SmsMessage sms = SmsMessage.createFromPdu((byte[])smsExtra[i]);
	                messages += sms.getMessageBody().toString();
	                address = sms.getOriginatingAddress();
	            }

	            // Display SMS message
	            encolarMensaje(address, messages);
	            Log.d("resultado", String.format("addres='%s', messages='%s'", address, messages));
//	            Toast.makeText( context, messages, Toast.LENGTH_SHORT ).show();
	            
	        }

	    }
	}

}
