package com.sirideas.nbtest.activitys.tests;

import java.util.Arrays;

import android.os.Bundle;
import android.widget.TextView;

import com.nebula.sketch.cmp.NbButton;
import com.nebula.sketch.cmp.NbSwitch;
import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.in.NbCmpIn;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivityDigitalIns extends ActivityBtBase implements com.nebula.sketch.cmp.core.NbCmp.OnValueChangeListener{
	
	private NbCmpIn[] ins = {
			new NbSwitch(43),	// Interruptor 0
			new NbSwitch(42),	// Interruptor 1
			new NbSwitch(41),	// Interruptor 2
			new NbButton(49),	// Boton 0
			new NbButton(48),	// Boton 1
			new NbButton(47),	// Boton 2
	};
	
	// Botones
	private int tvs[] = {
			R.id.tv_b0, R.id.tv_b1, R.id.tv_b2,
			R.id.tv_i0, R.id.tv_i1, R.id.tv_i2,
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_digital_ins);
		
		// Linkear botones al listener
		for(int i=0; i<ins.length; i++) ins[i].setOnValueChangeListener(this);
		
		// Conectar dispositivos
		getSketch().connect(ins);
		
		// Iniciar conexión
		getCom().connect();
				
	}
	
	@Override
	public void onValueChange(NbCmp component, int newValue, int oldValue) {
		// Obtener posición del objeto
		int pos = Arrays.asList(ins).indexOf(component);
		
		((TextView)findViewById(tvs[pos])).setBackgroundColor(
				getResources().getColor(newValue!=0? R.color.green: R.color.red));
		
	}

}
