package com.nebula.helpers;

import android.app.Activity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import com.nebula.adk.NbAdk;
import com.nebula.com.NbComHandler;
import com.nebula.com.NbComStateEnum;
import com.nebula.sketch.NbSketch;
import com.nebula.NbBuffer;
import com.nebula.NbBytes;
import com.nebula.R;

public class NbAdkMainActivityHelper extends Activity{

	// Instancia para la comunicación
	private NbAdk com;
	
	// Grupo de componentes
	private NbSketch sketch = new NbSketch(){
		private static final long serialVersionUID = -2410315236868140995L;
		public void loop() {
			NbAdkMainActivityHelper.this.loop();
		}
		public NbBytes getUserBytes() {
			return NbAdkMainActivityHelper.this.getUserBytes();
		}
		public boolean unknowCmd(int cmd, NbBuffer data) throws com.nebula.NbBuffer.ReadException {

			return NbAdkMainActivityHelper.this.unknowCmd(cmd, data); 
		}
	};
	
	// Titulo principal de la ventana;
	private CharSequence title;
	
	// Devuelve la comunicacion actual
	public NbAdk getCom() {
		return com;
	}
	
	public void loop(){
		
	}
	public NbBytes getUserBytes() {
		return null;
	}
	public boolean unknowCmd(int cmd, NbBuffer data) throws com.nebula.NbBuffer.ReadException{
		return false;
	}
	
	
	// Devuelve el sketch de la actividad
	public NbSketch getSketch(){
		return sketch;
	}
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		// Obtener el titulo inicial para poder cambiar
		// El titulo de la actividad posteriormente
		title = getTitle();
		
		// Crear instancia de la comunicación
		com = new NbAdk(this);
		
		com.addHandler(new NbComHandler(){
			
			@Override
			public void stateChanged(NbComStateEnum state){
				invalidateOptionsMenu();
			}
			
		});
		
		// asignar sketch
		sketch.setCom(com);
		
		// Chequea el intent
		com.checkIntent();
		
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
			com.connect();	// Establecer conexión
		}else if(id == R.id.__nb_disconnect){
			com.disconnect();	// Desconectar
		}
		return super.onMenuItemSelected(featureId, item);
	}

	@Override
	protected void onDestroy() {
		com.disconnect();
		com.unregisterReceiver();
		super.onDestroy();
	}
	
}
