package com.nebula.sketch.cmp;

import com.nebula.NbBuffer;
import com.nebula.sketch.cmp.in.NbCmpInObj;

public class NbHCSR04 extends NbCmpInObj{
	
	public static final int MAX_RANGE = 11640;
	public static final int MIN_RANGE = 0;
	
	public NbHCSR04(int id){
		super(id); 
	}

	public float getValueAtCm(int unit){
		long value = getValue();
		if(value >= MIN_RANGE && value<= MAX_RANGE)
			return (float) (value/58.2);
		return -1;
	}

	@Override
	public void readData(NbBuffer data) throws NbBuffer.ReadException {
		setValue(data.readLong());
	}
	
}
