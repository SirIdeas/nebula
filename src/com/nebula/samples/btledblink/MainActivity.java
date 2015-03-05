package com.nebula.samples.btledblink;

import com.nebula.helpers.NbBtMainActivityHelper;
import com.nebula.sketch.cmp.NbLedDigital;

import android.os.Bundle;
import android.view.WindowManager;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ToggleButton;

public class MainActivity extends NbBtMainActivityHelper{
  
  // Instanciar un LED Digital
  private NbLedDigital led = new NbLedDigital(13);
  
  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
    getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
    
    // Indicar la actividad a utilizar para listar los accesorios BT
    setBtDeviceListActivityClass(BtDevicesListActivity.class);
    
    // Conectar el led al Sketch
    getSketch().connect(led);
    
    // Asignar listener al ToggleButton para encender y apagar el LED
    ((ToggleButton)findViewById(R.id.toggleButton1)).setOnCheckedChangeListener(new OnCheckedChangeListener() {
      
      @Override
      public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
        led.setValue(isChecked);
      }
      
    });
    
  }
  
}