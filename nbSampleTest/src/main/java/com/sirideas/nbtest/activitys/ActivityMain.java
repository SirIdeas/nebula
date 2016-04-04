package com.sirideas.nbtest.activitys;

import com.sirideas.nbtest.Global;
import com.sirideas.nbtest.R;
import com.sirideas.nbtest.activitys.tests.ActivityAnalogIns;
import com.sirideas.nbtest.activitys.tests.ActivityAnalogOuts;
import com.sirideas.nbtest.activitys.tests.ActivityDigitalIns;
import com.sirideas.nbtest.activitys.tests.ActivityDigitalOuts;
import com.sirideas.nbtest.activitys.tests.ActivityLCD;
import com.sirideas.nbtest.activitys.tests.ActivitySensors;
import com.sirideas.nbtest.activitys.tests.ActivityMotors;
import com.sirideas.nbtest.activitys.tests.ActivityStepToStep;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;

public class ActivityMain extends Activity{

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		
	}
	
	public void openDigitalOuts(View v) {
		Global.openActivity(this, ActivityDigitalOuts.class);
	}
	
	public void openAnalogOuts(View v) {
		Global.openActivity(this, ActivityAnalogOuts.class);
	}

	public void openDigitalIns(View v) {
		Global.openActivity(this, ActivityDigitalIns.class);
	}

	public void openAnalogIns(View v) {
		Global.openActivity(this, ActivityAnalogIns.class);
	}
	
	public void openSensors(View v) {
		Global.openActivity(this, ActivitySensors.class);
	}
	
	public void openLCD(View v) {
		Global.openActivity(this, ActivityLCD.class);
	}
	
	public void openStepToStep(View v) {
		Global.openActivity(this, ActivityStepToStep.class);
	}
	
	public void openMotors(View v) {
		Global.openActivity(this, ActivityMotors.class);
	}
	
	
}
