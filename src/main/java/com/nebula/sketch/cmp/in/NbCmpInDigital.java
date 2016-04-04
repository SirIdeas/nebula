package com.nebula.sketch.cmp.in;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbDigital;


public class NbCmpInDigital extends NbCmpInPin implements NbDigital{

	public NbCmpInDigital(int pin) {
		super(pin);
	}
	
	@Override
	public NbBytes getSetupBytes() {
		NbBytes data = super.getSetupBytes();
		data.add(0, NbDialect.MSG_DIGITAL_IN_CONF);
		return data;
	}
	
	@Override
	public NbBytes getSyncronizeBytesActiveState() {
		NbBytes data = super.getSyncronizeBytesActiveState();
		data.add(0, NbDialect.MSG_SET_STATE_DIGITAL);
		return data;
	}
	
	public boolean getDigitalValue() {
		return getValue()!=0;
	}
	
	public boolean isHigh(){
		return getDigitalValue();
	}
	
	public boolean isLow(){
		return !getDigitalValue();
	}
	
}
