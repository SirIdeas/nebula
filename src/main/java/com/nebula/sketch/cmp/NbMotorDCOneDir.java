package com.nebula.sketch.cmp;

import com.nebula.sketch.cmp.interfaces.NbPin;

public class NbMotorDCOneDir extends NbMotorDC{
	
	public NbMotorDCOneDir(int pin, int in1Pin) {
		super(pin, in1Pin);
	}
	
	public NbMotorDCOneDir(int pin, int in1Pin, long maxVel) {
		super(pin, in1Pin, NbPin.INVALID_PIN,  0, maxVel);
	}
	
}
