package com.nebula.sketch.cmp.out;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbDigital;

public class NbCmpOutDigital extends NbCmpOutPin implements NbDigital{
	
	public static final int MIN_VALUE = 0;
	public static final int MAX_VALUE = 1;
	
	public NbCmpOutDigital(int pin){
		super(pin);
		setRange(MIN_VALUE, MAX_VALUE);
	}
	
	public boolean getDigitalValue(){
		return getValue()!=0;
	}
	
	public void setValue(boolean value){
		setValue(value ? 1 : 0);
	}
	
	public boolean isHigh(){
		return getDigitalValue();
	}
	
	public boolean isLow(){
		return !getDigitalValue();
	}
	
	public void high(){
		setValue(true);
	}
	
	public void low(){
		setValue(false);
	}
	
	public void toggle(){
		setValue(!getDigitalValue());
	}

	@Override
	public NbBytes getSyncronizeBytes() {
		NbBytes data = super.getSyncronizeBytes();
		data.add(0, NbDialect.MSG_DIGITAL_WRITE);
		return data;
	}
	
	
}
