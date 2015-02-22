package com.nebula.sketch.cmp;

import com.nebula.sketch.cmp.interfaces.NbDoubleDir;


public class NbMotorDCTwoDir extends NbMotorDC  implements NbDoubleDir{
	
	public NbMotorDCTwoDir(int pin, int in1Pin, int in2Pin) {
		super(pin, in1Pin, in2Pin, NbMotorDC.MAX_VEL);
	}
	
	public NbMotorDCTwoDir(int pin, int in1Pin, int in2Pin, long maxVel) {
		super(pin, in1Pin, in2Pin, maxVel);
	}

	@Override
	public int getDir() {
		long vel = getVel();
		return vel > 0 ? NbDoubleDir.DIR_LEFT : vel <0 ? NbDoubleDir.DIR_RIGHT : NbDoubleDir.DIR_NONE;
	}

	@Override
	public void setDir(int dir) {
		long vel = Math.abs(getVel());
		if(dir == NbDoubleDir.DIR_LEFT){
			setVel(vel);
		}else if(dir == NbDoubleDir.DIR_RIGHT){
			setVel(-vel);
		}else{
			stop();
		}
	}
	
}
