package com.nebula.sketch.cmp.in;

import com.nebula.NbBuffer;
import com.nebula.NbBytes;
import com.nebula.sketch.cmp.core.NbCmp;

public abstract class NbCmpIn extends NbCmp{
	
	private boolean mLastActive = true;
	
	public NbCmpIn() {
		super();
	}
	
	public void readData(NbBuffer data) throws NbBuffer.ReadException {}
	
	public boolean changedActiveState(){
		return isActive() != mLastActive;
	}
	
	public void synchronizeActiveState(){
		mLastActive = isActive();
	}
	
	public NbBytes getSyncronizeBytesActiveState(){
		NbBytes data = new NbBytes();
		data.add(isActive()? 1 : 0);
		return data;
	}
	
}
