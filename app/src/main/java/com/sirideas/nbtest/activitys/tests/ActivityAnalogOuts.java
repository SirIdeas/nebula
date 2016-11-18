package com.sirideas.nbtest.activitys.tests;

import com.nebula.sketch.cmp.NbLedAnalog;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

import android.os.Bundle;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;
import android.widget.TextView;

public class ActivityAnalogOuts extends ActivityBtBase implements OnSeekBarChangeListener{
	
	private NbLedAnalog[] leds = {
			new NbLedAnalog(4),	// LED Rojo 0
			new NbLedAnalog(6),	// LED Verde 0
			new NbLedAnalog(5),	// LED Azul 0
			new NbLedAnalog(7),	// LED Rojo 1
			new NbLedAnalog(9),	// LED Verde 1
			new NbLedAnalog(8),	// LED Azul 1
	};
	
	// Botones
	private int seekBars[] = {
			R.id.sb_l0r, R.id.sb_l0g, R.id.sb_l0b,
			R.id.sb_l1r, R.id.sb_l1g, R.id.sb_l1b,
	};
	
	// Botones
	private int seekBarsTexts[] = {
			R.id.t_sb_l0r, R.id.t_sb_l0g, R.id.t_sb_l0b,
			R.id.t_sb_l1r, R.id.t_sb_l1g, R.id.t_sb_l1b,
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_analog_outs);
		
		// Linkear botones al listener
		for(int i=0; i<seekBars.length; i++) ((SeekBar)findViewById(seekBars[i])).setOnSeekBarChangeListener(this);
		
		// Conectar dispositivos
		getSketch().connect(leds);
		
		// Iniciar conexión
		getCom().connect();
		
	}

	@Override
	public void onProgressChanged(SeekBar seekBar, int progress,boolean fromUser) {
		int pos = Global.getIndexOf(seekBar.getId(), seekBars);
		leds[pos].setValue(progress);
		((TextView)findViewById(seekBarsTexts[pos])).setText(String.valueOf(progress));
	}

	@Override
	public void onStartTrackingTouch(SeekBar seekBar) {}

	@Override
	public void onStopTrackingTouch(SeekBar seekBar) {}
	
}
