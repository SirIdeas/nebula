package com.sirideas.nbtest.activitys.tests;

import com.nebula.sketch.cmp.NbLedDigital;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

import android.os.Bundle;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ToggleButton;

public class ActivityDigitalOuts extends ActivityBtBase implements OnCheckedChangeListener{
	
	private NbLedDigital[] leds = {
			new NbLedDigital(4),	// LED Rojo 0
			new NbLedDigital(6),	// LED Verde 0
			new NbLedDigital(5),	// LED Azul 0
			new NbLedDigital(7),	// LED Rojo 1
			new NbLedDigital(9),	// LED Verde 1
			new NbLedDigital(8),	// LED Azul 1
	};
	
	// Botones
	private int buttons[] = {
			R.id.b_l0r, R.id.b_l0g, R.id.b_l0b,
			R.id.b_l1r, R.id.b_l1g, R.id.b_l1b,
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_digital_outs);
	    
		// Linkear botones al listener
		for(int i=0; i<buttons.length; i++)
			((ToggleButton)findViewById(buttons[i])).setOnCheckedChangeListener(this);
		
		// Conectar dispositivos
		getSketch().connect(leds);
		
		// Iniciar conexión
		getCom().connect();
		
	}

	// Encender y apagar los leds
	@Override
	public void onCheckedChanged(CompoundButton buttonView, boolean isChecked){
		// Obtener posición del objeto
		int pos = Global.getIndexOf(buttonView.getId(), buttons);
		leds[pos].setValue(isChecked);
		
	}
	
}
