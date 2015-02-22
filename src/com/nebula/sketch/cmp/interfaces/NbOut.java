package com.nebula.sketch.cmp.interfaces;

import com.nebula.NbBytes;

public interface NbOut {

	public boolean changed();
	public void synchronize();
	
	public NbBytes getSyncronizeBytes();
	
	public NbBytes getSyncronizeBytesIfChangeOrForce(boolean force);
	
}
