package com.nebula.sketch.cmp;

import com.nebula.sketch.cmp.out.NbCmpOutAnalog;

public class NbMotor extends NbCmpOutAnalog{
	
	public NbMotor(int pin, long minValue, long maxVel) {
		super(pin, minValue, maxVel);
		stop();
	}
	
	public long getVel(){
		return getValue();
	}
	
	public void setVel(long vel){
		setValue(vel);
	}
	
	public long getRealVel() {
		return getVel() - getMinValue();
	}
	
	public void stop(){
		setVel(0);
	}
	
}
