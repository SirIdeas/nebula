package com.nebula.sketch.cmp.out;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbPin;

abstract public class NbCmpOutPin extends NbCmpOut implements NbPin{
	
	private long mMinValue = Long.MIN_VALUE;
	private long mMaxValue = Long.MAX_VALUE;
	private long mRealValue = -1;
	private int mPin = NbPin.INVALID_PIN;
	
	public NbCmpOutPin(int pin){
		super();
		setValue(0);
		setPin(pin);
	}
	
	public NbCmpOutPin(int pin, long min, long max){
		super();
		setValue(0);
		setPin(pin);
		setRange(min, max);
	}
	
	public int getPin(){
		return mPin;
	}
	
	public void setPin(int pin){
		mPin = pin;
	}
	
	public void setValue(long value){
		if(value < getMinValue()) value = getMinValue();
		if(value > getMaxValue()) value = getMaxValue();
		super.setValue(value);
	}
	
	@Override
	public NbBytes getSetupBytes() {
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_PIN_MODE_OUT);
		ret.add(getPin());
		return ret;
	}
	
	@Override
	public NbBytes getSyncronizeBytes() {
		NbBytes ret = new NbBytes();
		ret.add(getPin());
		ret.add(getValue());
		return ret;
	}
	
	public long getRealValue(){
		return mRealValue;
	}

	public boolean changed(){
		return mRealValue != getValue();
	}
	
	public void synchronize(){
		mRealValue = getValue();
	}
	
	public long getMaxValue(){
		return mMaxValue;
	}
	
	public long getMinValue(){
		return mMinValue;
	}

	protected void setMaxValue(long max){
		mMaxValue = max;
	}
	
	protected void setMinValue(long min){
		mMinValue = min;
	}
	
	protected void setRange(long min, long max){
		mMinValue = min;
		setMaxValue(max);
	}
	
}
