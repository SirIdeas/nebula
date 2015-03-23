package com.sirideas.nbtest.activitys;

import java.util.ArrayList;

import com.db.chart.model.LineSet;
import com.db.chart.model.Point;
import com.db.chart.view.LineChartView;
import com.db.chart.view.YController;
import com.nebula.helpers.NbBtMainActivityHelper;
import com.sirideas.nbtest.BtDevicesListActivity;

import android.graphics.Color;
import android.os.Bundle;
import android.view.WindowManager;

public class ActivityBtBase extends NbBtMainActivityHelper {
	
	private final static String[] mColors = {"#f36c60","#7986cb", "#4db6ac", "#aed581", "#ffb74d"};

	// Puntos de una linea
	private final static int POINTS_AMOUNT = 100;
	
	// View con el chart
	private LineChartView lcv;
	
	// Lista de lecturas de los trimmer
	private ArrayList<ArrayList<Integer>> lects = new ArrayList<ArrayList<Integer>>();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		
	    // Indicar la actividad a utilizar para listar los accesorios BT
	    setBtDeviceListActivityClass(BtDevicesListActivity.class);
	}
	
	@Override
	public void onBackPressed() {
		getCom().disconnect();
		super.onBackPressed();
	}
	
	// Inicializar la clase
	public void initChart(int lcvRes, int linesAmount){
		
		// vars
		LineSet data;
		
		lcv = (LineChartView)findViewById(lcvRes);	// Obtener la vista de lcv
			
		// reset
		lects.clear();	// Limpiar lecturas
		lcv.reset();	// Limpiar chart
		
		// crear listas de lecturas
		for(int i=0; i<linesAmount; i++){
			
			lects.add(new ArrayList<Integer>());
			
		}
		
		
		// Por cadar trimmer crear una linea
		for(int i = 0; i < lects.size(); i++){
			
			// Agregar puntos a la linea
			data = new LineSet();
			for(int j = 0; j < POINTS_AMOUNT; j++){
				lects.get(i).add(0);
				data.addPoint(new Point(String.valueOf(j), 0));
			}
			
			// Color de la linea
			data.setLineColor(Color.parseColor(getColor(i)));
			
			// Agregar linea al chart
			lcv.addData(data);
			
		}
				
		// configurar chart
		lcv.setLabels(YController.NONE)
			.setMaxAxisValue(1024, 1)
			.show();	
			
	}
	
	// Actualizar lista de lecturas. agrega un valor al final y elimina el primero
	protected void pushValue(int pos, int value) {
		lects.get(pos).add(value);	// En coloar valor
		lects.get(pos).remove(0);	// Desencolar valor
		updateChart();	// Actualiz valores	
	}
	
	// Actualizar datos del chart
	private void updateChart(){
		
		float[] newValues;
		
		// recorrar las  lecturas obtener los valores y asignarlo
		for(int i = 0; i<lects.size(); i++){
			newValues = new float[POINTS_AMOUNT];
			
			// convertir lista a array
			for(int j = 0; j<newValues.length; j++)
				newValues[j] = lects.get(i).get(j);
			
			// actualizar lista
			lcv.updateValues(i, newValues);
		}
		
		lcv.notifyDataUpdate();
		
	}
	
	public static String getColor(int index){
		
		return mColors[index%mColors.length];
		
	}
	
}
