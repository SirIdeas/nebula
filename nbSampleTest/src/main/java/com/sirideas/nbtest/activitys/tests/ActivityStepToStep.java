package com.sirideas.nbtest.activitys.tests;

import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;

import com.nebula.sketch.cmp.NbStepToStep;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivityStepToStep extends ActivityBtBase implements OnSeekBarChangeListener{
	
	// Motor paso a paso
	private NbStepToStep motor = new NbStepToStep(38, 37, 36, 35);
	
	// Botones
	private int seekBars[] = { R.id.sb_steps, R.id.sb_delay };
	
	//
	private int seekBarsEdits[] = { R.id.e_steps, R.id.e_delay };
	
	private EditText editSteps;
	private EditText editDelay;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_step_to_step);
		
		editSteps = (EditText)findViewById(R.id.e_steps);
		editDelay = (EditText)findViewById(R.id.e_delay);
		
		// Linkear botones al listener
		for(int i=0; i<seekBars.length; i++) ((SeekBar)findViewById(seekBars[i])).setOnSeekBarChangeListener(this);
		
		// conectar componentes
		getSketch().connect(motor);
		
		// Iniciar conexión
		getCom().connect();
		
	}
	
	// Obtener valores de los edit y moverlos
	public void enviar(View v){
		try{
			int steps = Integer.valueOf(editSteps.getText().toString());
			int delay = Integer.valueOf(editDelay.getText().toString());
			motor.move(steps, delay);
		}catch(Exception e){
			e.printStackTrace();
		}
	}

	@Override
	public void onProgressChanged(SeekBar seekBar, int progress,boolean fromUser) {
		int pos = Global.getIndexOf(seekBar.getId(), seekBars);
		((EditText)findViewById(seekBarsEdits[pos])).setText(String.valueOf(progress));
	}

	@Override
	public void onStartTrackingTouch(SeekBar seekBar) {}

	@Override
	public void onStopTrackingTouch(SeekBar seekBar) {}
	
}
