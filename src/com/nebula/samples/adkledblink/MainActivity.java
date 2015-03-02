package com.nebula.samples.adkledblink;

import com.nebula.helpers.NbAdkMainActivityHelper;
import com.nebula.sketch.cmp.NbButton;
import com.nebula.sketch.cmp.NbLedDigital;
import com.nebula.sketch.cmp.NbTrimmer;
import com.nebula.sketch.cmp.core.NbCmp;

import android.os.Bundle;
import android.widget.CompoundButton;
import android.widget.ToggleButton;
import android.widget.CompoundButton.OnCheckedChangeListener;

public class MainActivity extends NbAdkMainActivityHelper {
    
  // Instanciar un LED Digital
  private NbLedDigital led = new NbLedDigital(13);

  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
      
    // Conectar el led al Sketch
    getSketch().connect(led);
      
    // Asignar listener al ToggleButton para encender y apagar el LED
    ((ToggleButton)findViewById(R.id.toggleButton1)).setOnCheckedChangeListener(new OnCheckedChangeListener() {
        
      @Override
      public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
        led.setValue(isChecked);
      }
        
    });
      
    NbButton b = new NbButton(14);
    NbTrimmer t = new NbTrimmer(0);
      
    b.setOnStateChangeListener(new NbCmp.OnStateChangeListener() {
      
      @Override
      public void onStateChange(NbCmp component, boolean newState) {
//        led.toggle();
      }
    });
      
    t.setOnValueChangeListener(new NbCmp.OnValueChangeListener() {
      
      @Override
      public void onValueChange(NbCmp component, int newValue, int oldValue) {
        led.setValue((int)((newValue * 255) / 1023));
      }
      
    });
      
  }
  
}
