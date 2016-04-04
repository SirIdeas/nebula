package com.nebula.sketch.cmp;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbPin;
import com.nebula.sketch.cmp.out.NbCmpOut;


public class NbStepToStep extends NbCmpOut{
	
	private NbBytes cmdBytes;
	
	private int mPinA = NbPin.INVALID_PIN;
	private int mPinB = NbPin.INVALID_PIN;
	private int mPinC = NbPin.INVALID_PIN;
	private int mPinD = NbPin.INVALID_PIN;
	private int currentStep = 0;
	
	public NbStepToStep(int pinA, int pinB, int pinC, int pinD){
		mPinA = pinA;
		mPinB = pinB;
		mPinC = pinC;
		mPinD = pinD;
		synchronize();
	}
	
	public void move(int steps, int vel, int dir){
		if(isActive()){
			cmdBytes.addAll(getMoveBytes(steps, vel, dir));
			currentStep = (currentStep + steps)%4;
		}
	}
	
	public void move(int steps, int vel){
		move(Math.abs(steps), vel, steps>0? 1 : 0);
	}
	
	public boolean changed() {
		return cmdBytes.size()>0;
	}
	
	public void synchronize() {
		cmdBytes = new NbBytes();
	}
	
	protected NbBytes getMoveBytes(int steps, int vel, int dir){
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_STEPTOSTEP_MOVE);
		ret.add(mPinA);
		ret.add(mPinB);
		ret.add(mPinC);
		ret.add(mPinD);
		ret.addInt(steps);
		ret.addInt(vel);
		ret.add(dir);
		ret.add(currentStep);
		return ret;
	}

	@Override
	public NbBytes getSyncronizeBytes() {
		return cmdBytes;
	}

	@Override
	public NbBytes getSetupBytes() {
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinA);
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinB);
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinC);
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(mPinD);
		return ret;
	}
	
	public int getPinA(){
		return mPinA;
	}
	
	public int getPinB(){
		return mPinB;
	}
	
	public int getPinC(){
		return mPinC;
	}
	
	public int getPinD(){
		return mPinD;
	}
	
}
