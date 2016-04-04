package com.nebula.sketch.cmp.in;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;

public abstract class NbCmpInObj extends NbCmpIn{
	
	public NbCmpInObj(int id) {
		super();
		setId(id);
	}
	
	@Override
	public NbBytes getSyncronizeBytesActiveState(){
		return new NbBytes();
	}
	
	public void readData(NbBuffer data) throws NbBuffer.ReadException{
		setValue(data.readInt());
	}
	
}
