package com.nebula.sketch.cmp;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbPin;

public class NbMotorDC extends NbMotor{
	
	public static final int MAX_VEL = 255;
	
	private int mPinIn1, mPinIn2;
	
	public NbMotorDC(int enabledPin, int in1Pin) {
		super(enabledPin, 0, MAX_VEL);
		init(in1Pin, NbPin.INVALID_PIN);
	}
	
	public NbMotorDC(int enabledPin, int in1Pin, int in2Pin) {
		super(enabledPin, 0, MAX_VEL);
		init(in1Pin, in2Pin);
	}
	
	public NbMotorDC(int enabledPin, int in1Pin, int in2Pin, long maxVel) {
		super(enabledPin, -maxVel, maxVel);
		init(in1Pin, in2Pin);
	}
	
	public NbMotorDC(int enabledPin, int in1Pin, int in2Pin, long minVel, long maxVel) {
		super(enabledPin, minVel, maxVel);
		init(in1Pin, in2Pin);
	}
	
	private void init(int in1Pin, int in2Pin) {
		mPinIn1 = in1Pin;
		mPinIn2 = in2Pin;
	}
	
	@Override
	public NbBytes getSetupBytes() {
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(getPin());
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinIn1);
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinIn2);
		return ret;
	}
	
	public NbBytes getSyncronizeBytes() {
		long vel = getVel();
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_MOTORDC_WRITE);
		ret.add(getPin());
		ret.add(mPinIn1);
		ret.add(mPinIn2);
		ret.add(Math.abs(vel));
		ret.add(vel>=0? 1 : 0);
		ret.add(vel<=0? 1 : 0);
		return ret;
	}
	
	public int getPinIn1(){
		return mPinIn1;
	}
	
	public int getPinIn2(){
		return mPinIn2;
	}
	
}
