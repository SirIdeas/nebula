package com.sirideas.nbtest;

import com.nebula.sketch.NbDialect;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;

@SuppressLint("NewApi")
public class Global {
	
	public static final String DEVICE_NAME = "NebulaBoard";
	
    public static final int ACTIVE_HCSR04 	= NbDialect.__LAST_MSG_CODE + 1;
	public static final int INIT_LCD 		= NbDialect.__LAST_MSG_CODE + 2;
	public static final int INIT_SERVOS 	= NbDialect.__LAST_MSG_CODE + 3;
	
	public static final class IDS{
		public static final int ID_HCSR04_0 	= 1;
		public static final int ID_HCSR04_1 	= 2;
		public static final int ID_LCD_1 		= 3;
		public static final int ID_SERVO_0 		= 4;
		public static final int ID_SERVO_1 		= 5;
		public static final int ID_SERVO_2 		= 6;
	}
	
	// Muestra una actividad
	public static void openActivity(Activity act, Class <?> cls){
		act.startActivity(new Intent(act, cls));
	}
	
	public static int getIndexOf(int value, int[] values){
		for(int i=0; i<values.length; i++)
			if (values[i] == value) return i;
		return -1;
	}

}
