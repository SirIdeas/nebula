package com.nebula.sketch.cmp.out;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;


public abstract class NbCmpOutObj extends NbCmpOut{
	
	private NbBytes cmdBytes;
	
	public NbCmpOutObj(int id) {
		super();
		setId(id);
	}
	
	public void addCmd(NbBytes cmd){
		cmdBytes.add(NbDialect.MSG_OBJECT_CMD);
		cmdBytes.addInt(getId());
		cmdBytes.addAll(cmd);
	}

	@Override
	public NbBytes getSetupBytes(){
		return new NbBytes();
	}

	@Override
	public NbBytes getSyncronizeBytes() {
		return cmdBytes;
	}
	
	@Override
	public boolean changed() {
		return cmdBytes.size()>0;
	}
	
	@Override
	public void synchronize() {
		cmdBytes = new NbBytes();
	}
	
}
