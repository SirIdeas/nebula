package com.sirideas.nbtest.activitys.tests;

import java.util.Arrays;

import android.os.Bundle;
import android.widget.TextView;

import com.nebula.sketch.cmp.NbCNY70;
import com.nebula.sketch.cmp.NbHCSR04;
import com.nebula.sketch.cmp.NbLDR;
import com.nebula.sketch.cmp.NbLM35;
import com.nebula.sketch.cmp.NbSHARP;
import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.core.NbCmp.OnValueChangeListener;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivitySensors extends ActivityBtBase implements OnValueChangeListener{
	
	private NbCmp[] ins = {
			new NbLM35(12),		// sensor de temperatura. PROBLEMA
			new NbLDR(14),		// sensor de luz
			new NbSHARP(13),	// sensor de distancia
			new NbCNY70(10),	// PROBLEMA
			new NbCNY70(11),	// PROBLEMA
			
			// Reciben ID, Debe ser igual en el programa de arduino
			new NbHCSR04(Global.IDS.ID_HCSR04_0),
			new NbHCSR04(Global.IDS.ID_HCSR04_1),
			
	};
	
	// Botones
	private int[] tvs = {
			R.id.tv_lm35,
			R.id.tv_ldr,
			R.id.tv_sharp,
			R.id.tv_cny70_0,
			R.id.tv_cny70_1,
			
			R.id.tv_hcsr04_0,
			R.id.tv_hcsr04_1,
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_sensors);
		
		// inicializar chart
		initChart(R.id.lcv, ins.length);
		
		// Linkear botones al listener
		for(int i=0; i<ins.length; i++) ins[i].setOnValueChangeListener(this);
		
		getSketch().addSetupByte(Global.ACTIVE_HCSR04);
		
		// Conectar dispositivos
		getSketch().connect(ins);
		
		// Iniciar conexión
		getCom().connect();
		
	}
	
	// Cuando cambie la lectura de un trim
	@Override
	public void onValueChange(NbCmp component, int newValue, int oldValue) {
		
		if(component instanceof NbHCSR04){
			newValue = (int)component.getValueMap(0, 1024, NbHCSR04.MIN_RANGE, NbHCSR04.MAX_RANGE);
		}
		
		// Obtener posición del objeto
		int pos = Arrays.asList(ins).indexOf(component);
		pushValue(pos, newValue);	// Actualizar lista de valores
		
		// actualizar en el text view
		((TextView)findViewById(tvs[pos])).setText(String.valueOf(newValue));
		
	}

}
