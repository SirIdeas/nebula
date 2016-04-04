package com.nebula.sketch.cmp.out;

import com.nebula.NbBytes;
import com.nebula.sketch.cmp.core.NbCmp;
import com.nebula.sketch.cmp.interfaces.NbOut;

public abstract class NbCmpOut extends NbCmp implements NbOut{
	
	public NbCmpOut(){
		super();
	}
	
	@Override
	public NbBytes getSyncronizeBytesIfChangeOrForce(boolean force) {
		if(force || changed()) return getSyncronizeBytes();
		return new NbBytes();
	}
	
}
