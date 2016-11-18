package com.sirideas.nbtest.activitys.tests;

import android.os.Bundle;
import android.view.View;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.SeekBar.OnSeekBarChangeListener;

import com.nebula.sketch.cmp.NbMotor;
import com.nebula.sketch.cmp.NbMotorDCOneDir;
import com.nebula.sketch.cmp.NbMotorDCTwoDir;
import com.nebula.sketch.cmp.NbServo;
import com.sirideas.nbtest.BtDevicesListActivity;
import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.ActivityBtBase;

public class ActivityMotors extends ActivityBtBase implements OnSeekBarChangeListener{
	
	// Servomotores
	private NbMotor[] motors = {
			new NbServo(Global.IDS.ID_SERVO_0),
			new NbServo(Global.IDS.ID_SERVO_1),
			new NbServo(Global.IDS.ID_SERVO_2),
			new NbMotorDCOneDir(45, 36),
			new NbMotorDCTwoDir(45, 36, 35)
	};
	
	// Seek bars
	private int seekBars[] = {
			R.id.sb_s0, R.id.sb_s1, R.id.sb_s2, // Servos
			R.id.sb_motor_one_dir,				// Motor una direcion
			R.id.sb_motor_two_dirs				// Motor una direcion
	};
	
	// Valores de las seek bars
	private int seekBarsTexts[] = {
			R.id.t_sb_s0, R.id.t_sb_s1, R.id.t_sb_s2,
			R.id.t_sb_motor_one_dir,
			R.id.t_sb_motor_two_dirs
	};
	
	// botones de detener
	private int buttonsStop[] = {
			R.id.b_stop_s0, R.id.b_stop_s1, R.id.b_stop_s2,
			R.id.b_stop_motor_one_dir,
			R.id.b_stop_motor_two_dirs
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_motors);
		
		// Inicializar Servos
		getSketch().addSetupByte(Global.INIT_SERVOS);

		// Indicar la actividad a utilizar para listar los accesorios BT
		setBtDeviceListActivityClass(BtDevicesListActivity.class);
		
		getCom().setAutoConnectToDevice("NebulaBoard");
		
		// Linkear botones al listener
		for(int i=0; i<seekBars.length; i++){
			((SeekBar)findViewById(seekBars[i])).setMax((int)(motors[i].getMaxValue() - motors[i].getMinValue()));
			((SeekBar)findViewById(seekBars[i])).setOnSeekBarChangeListener(this);
		}
		
		// Conectar dispositivos
		getSketch().connect(motors);
		
		// Iniciar conexión
		getCom().connect();
		
	}
	
	// Accion para botones detener
	public void detener(View v) {
		int pos = Global.getIndexOf(v.getId(), buttonsStop);
		motors[pos].stop();
		((SeekBar)findViewById(seekBars[pos])).setProgress((int)(motors[pos].getValue() - motors[pos].getMinValue()));
	}
	
	@Override
	public void onProgressChanged(SeekBar seekBar, int progress,boolean fromUser) {
		int pos = Global.getIndexOf(seekBar.getId(), seekBars);
		long vel = progress + motors[pos].getMinValue();
		motors[pos].setVel(vel);
		((TextView)findViewById(seekBarsTexts[pos])).setText(String.valueOf(vel));
	}
	
	@Override
	public void onStartTrackingTouch(SeekBar seekBar) {}
	
	@Override
	public void onStopTrackingTouch(SeekBar seekBar) {}
	
}
