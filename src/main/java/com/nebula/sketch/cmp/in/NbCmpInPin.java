package com.nebula.sketch.cmp.in;

import com.nebula.NbBytes;
import com.nebula.sketch.cmp.interfaces.NbPin;

abstract public class NbCmpInPin extends NbCmpIn implements NbPin{
	
	private int mPin = NbPin.INVALID_PIN;

	public NbCmpInPin(int pin){
		mPin = pin;
	}
	
	public int getPin(){
		return mPin;
	}
	
	public void setPin(int pin){
		mPin = pin;
	}
	
	@Override
	public NbBytes getSetupBytes() {
		NbBytes ret = new NbBytes();
		ret.add(mPin);
		return ret;
	}
	
	@Override
	public NbBytes getSyncronizeBytesActiveState() {
		NbBytes data = super.getSyncronizeBytesActiveState();
		data.add(getPin());
		return data;
	}
	
}
