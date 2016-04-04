package com.nebula.sketch.cmp.out;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbAnalog;


public class NbCmpOutAnalog extends NbCmpOutPin implements NbAnalog{
	
	public static final int MIN_VALUE = 0;
	public static final int MAX_VALUE = 255;
	
	public NbCmpOutAnalog(int pin){
		super(pin);
		setRange(MIN_VALUE, MAX_VALUE);
	}
	
	public NbCmpOutAnalog(int pin, long min, long max){
		super(pin, min, max);
	}

	@Override
	public NbBytes getSyncronizeBytes() {
		NbBytes data = super.getSyncronizeBytes();
		data.add(0, NbDialect.MSG_ANALOG_WRITE);
		return data;
	}
	
}
