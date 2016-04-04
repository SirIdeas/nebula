package com.sirideas.nbtest.activitys.tests;


import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.TextView;

import com.nebula.sketch.cmp.NbLiquidCrystal;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivityLCD extends ActivityBtBase implements OnClickListener{
	
	private static final int LCD_COLS = 16;
	
	// ID de la LCD igual que en el Sketch
	// El segundo parametro es la cantidad de caracteres en una fila
	private NbLiquidCrystal lcd = new NbLiquidCrystal(Global.IDS.ID_LCD_1, LCD_COLS);	// LCD de 16x2
	
	private int[] tvs = {
			R.id.t_lcd_0, R.id.t_lcd_1, R.id.t_lcd_2, R.id.t_lcd_3, R.id.t_lcd_4,
			R.id.t_lcd_5, R.id.t_lcd_6, R.id.t_lcd_7, R.id.t_lcd_8, R.id.t_lcd_9,
			R.id.t_lcd_10, R.id.t_lcd_11, R.id.t_lcd_12, R.id.t_lcd_13, R.id.t_lcd_14,
			R.id.t_lcd_15, R.id.t_lcd_16, R.id.t_lcd_17, R.id.t_lcd_18, R.id.t_lcd_19,
			R.id.t_lcd_20, R.id.t_lcd_21, R.id.t_lcd_22, R.id.t_lcd_23, R.id.t_lcd_24,
			R.id.t_lcd_25, R.id.t_lcd_26, R.id.t_lcd_27, R.id.t_lcd_28, R.id.t_lcd_29,
			R.id.t_lcd_30, R.id.t_lcd_31,
	};
	
	int posTxtView = -1;
	
	private EditText editTxt;
	
	@Override
	protected void onCreate(Bundle savedInstanceState){
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_lcd);
		
		// Buscar vistas
		editTxt = ((EditText)findViewById(R.id.e_txt));
		
		// Inicializar LCD
		getSketch().addSetupByte(Global.INIT_LCD);
		
		// Asignar manejador de evento click
		for(int i=0; i<tvs.length; i++) findViewById(tvs[i]).setOnClickListener(this);
		
		// Mover cursor a la primera posición
		setPos(0, false);
		
		// Conectar lcd
		getSketch().connect(lcd);
		
		// Iniciar conexión
		getCom().connect();
		
	}
	
	// Cambiar posición del cursor
	private void setPos(int pos, boolean moveLcdCursor) {
		
		// volver a gris el anterior
		if(posTxtView != -1)
			findViewById(tvs[posTxtView]).setBackgroundColor(getResources().getColor(R.color.gray)); 
		
		// Volver verde el nuevo
		posTxtView = pos;
		findViewById(tvs[pos]).setBackgroundColor(getResources().getColor(R.color.green));
		
		// Mover cursor en la pantalla
		if(moveLcdCursor) lcd.setCursor(pos%LCD_COLS, pos/LCD_COLS);
		
	}

	@Override
	public void onClick(View v) {
		// Obtener la posicion del elemento en el array
		int pos = Global.getIndexOf(v.getId(), tvs);
		
		setPos(pos, true);
		
	}
	
	// Limpiar la pantalla
	public void clear(View v){
		setPos(0, false);
		lcd.clear();
		
		// Limpiar tvs
		for(int i=0; i<tvs.length; i++) ((TextView)findViewById(tvs[i])).setText("");
		
	}
	
	public void enviar(View v){

		// obtener la cadena del campo de texto
		String txt = lcd.print(editTxt.getText().toString());
		editTxt.setText("");
		
		for(int i=0; i<txt.length(); i++){
			((TextView)findViewById(tvs[posTxtView+i])).setText(txt.substring(i,i+1));
		}
		
		setPos(posTxtView+txt.length()>= tvs.length ? 0 : posTxtView+txt.length(), true);
				
	}
	
}
