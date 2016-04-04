package com.nebula.sketch.cmp;

import com.nebula.sketch.cmp.in.NbCmpInAnalog;

public class NbLM35 extends NbCmpInAnalog{
	
	public NbLM35(int pin) {
		super(pin);
	}
	
	public float getValueAtCelcius(){
		return getValue() * 5 * 100/1024;
	}
	
}
