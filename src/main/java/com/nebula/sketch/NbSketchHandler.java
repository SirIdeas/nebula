package com.nebula.sketch;

import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.core.NbCmpVector;

import android.os.Handler;
import android.os.Message;

public class NbSketchHandler extends Handler{
	
	@Override
	public void handleMessage(Message msg) {
		
		if(msg.what == NbSketchMessageEnum.CHANGED_DIGITAL_STATE.ordinal()){
			NbCmpVector obj = (NbCmpVector)msg.obj;
			changedDigitalState(obj, msg.arg1, msg.arg2==1);
		}else if(msg.what == NbSketchMessageEnum.CHANGED_ANALOG_STATE.ordinal()){
			NbCmpVector obj = (NbCmpVector)msg.obj;
			changedAnalogState(obj, msg.arg1, msg.arg2==1);
		}else if(msg.what == NbSketchMessageEnum.CHANGED_OBJECT_STATE.ordinal()){
			NbCmp obj = (NbCmp)msg.obj;
			changedObjectState(obj, msg.arg1, msg.arg2==1);
		}else if(msg.what == NbSketchMessageEnum.CHANGED_DIGITAL_VALUE.ordinal()){
			NbCmpVector obj = (NbCmpVector)msg.obj;
			changedDigitalValue(obj, msg.arg1, msg.arg2==1);
		}else if(msg.what == NbSketchMessageEnum.CHANGED_ANALOG_VALUE.ordinal()){
			NbCmpVector obj = (NbCmpVector)msg.obj;
			changedAnalogValue(obj, msg.arg1, msg.arg2);
		}else if(msg.what == NbSketchMessageEnum.CHANGED_OBJECT_VALUE.ordinal()){
			NbCmp obj = (NbCmp)msg.obj;
			changedObjectValue(obj, msg.arg1);
		}else if(msg.what == NbSketchMessageEnum.OBJECT_NO_FOUND.ordinal()){
			objectNotFount(msg.arg1);
		}
		super.handleMessage(msg);
		
	}
	
	public void changedDigitalState(NbCmpVector components, int pin, boolean state){}
	public void changedAnalogState(NbCmpVector components, int pin, boolean state){}
	public void changedObjectState(NbCmp component, int id, boolean state){}
	public void changedDigitalValue(NbCmpVector components, int pin, boolean value){}
	public void changedAnalogValue(NbCmpVector components, int pin, long value){}
	public void changedObjectValue(NbCmp component, int id){}
	public void objectNotFount(int id){}
	
}
