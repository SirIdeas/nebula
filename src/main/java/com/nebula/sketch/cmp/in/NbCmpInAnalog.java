package com.nebula.sketch.cmp.in;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbAnalog;

public class NbCmpInAnalog extends NbCmpInPin implements NbAnalog{
	
	public NbCmpInAnalog(int pin){
		super(pin);
	}
	
	@Override
	public NbBytes getSetupBytes() {
		NbBytes data = super.getSetupBytes();
		data.add(0, NbDialect.MSG_ANALOG_IN_CONF);
		return data;
	}
	
	@Override
	public NbBytes getSyncronizeBytesActiveState() {
		NbBytes data = super.getSyncronizeBytesActiveState();
		data.add(0, NbDialect.MSG_SET_STATE_ANALOG);
		return data;
	}
	
}
