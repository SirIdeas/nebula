package com.nebula.sketch.cmp;

import com.nebula.sketch.cmp.in.NbCmpInAnalog;

public class NbSHARP extends NbCmpInAnalog{
	
	public NbSHARP(int pin) {
		super(pin);
	}
	
	public float getValueAtCm(){
		long value = getValue();
		if(value != 9){
			return (6762/(value - 9)) - 4;
		}
		return Float.MAX_VALUE;
	}
}
