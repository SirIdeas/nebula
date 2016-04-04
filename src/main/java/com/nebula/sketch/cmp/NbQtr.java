package com.nebula.sketch.cmp;

import com.nebula.NbBuffer;
import com.nebula.NbBuffer.ReadException;
import com.nebula.sketch.cmp.in.NbCmpInObj;

public class NbQtr extends NbCmpInObj{
	
	private int[] mSensorValues;
	
	public NbQtr(int id){
		super(id);
	}
	
	public int[] getValues(){
		if(mSensorValues!=null)
			return mSensorValues.clone();
		return null;
	}

	@Override
	public void readData(NbBuffer data) throws ReadException {
		super.readData(data);	// Leer el entero con el valor global
		byte sensorsCount = data.read();	// Leer el byte que indica la cantidad de valores
		mSensorValues = new int[sensorsCount];
		for(int i=0; i<sensorsCount; i++)
			mSensorValues[i] = data.read();	// Leer cada valor
	}
	
}
