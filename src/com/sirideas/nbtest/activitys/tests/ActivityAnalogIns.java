package com.sirideas.nbtest.activitys.tests;

import java.util.Arrays;

import android.os.Bundle;
import android.widget.TextView;

import com.nebula.sketch.cmp.NbTrimmer;
import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.core.NbCmp.OnValueChangeListener;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivityAnalogIns extends ActivityBtBase implements OnValueChangeListener{
	
	private NbTrimmer[] trimmers = {
			new NbTrimmer(8),	// Trim 0
			new NbTrimmer(9),	// Trim 1
	};
	
	// Botones
	private int[] tvs = {
			R.id.tv_t0, R.id.tv_t1
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_analog_ins);
		
		// inicializar chart
		initChart(R.id.lcv, trimmers.length);
		
		// Linkear botones al listener
		for(int i=0; i<trimmers.length; i++) trimmers[i].setOnValueChangeListener(this);
		
		// Conectar dispositivos
		getSketch().connect(trimmers);
		
		// Iniciar conexión
		getCom().connect();
		
	}
	
	// Cuando cambie la lectura de un trim
	@Override
	public void onValueChange(NbCmp component, int newValue, int oldValue) {
		
		// Obtener posición del objeto
		int pos = Arrays.asList(trimmers).indexOf(component);
		pushValue(pos, newValue);	// Actualizar lista de valores
		
		// actualizar en el text view
		((TextView)findViewById(tvs[pos])).setText(String.valueOf(newValue));
		
	}
	
}
