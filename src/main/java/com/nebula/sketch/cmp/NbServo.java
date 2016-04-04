package com.nebula.sketch.cmp;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.interfaces.NbDoubleDir;

public class NbServo extends NbMotor implements NbDoubleDir{

	public static final int MAX_VEL = 180;
	public static final int SET_POINT = MAX_VEL/2;
	
	public NbServo(int id){
		super(-1, 0, MAX_VEL);
		setId(id);
	}
	
	@Override
	public NbBytes getSetupBytes() {
		return new NbBytes();
	}
	
	@Override
	public NbBytes getSyncronizeBytes(){
		NbBytes ret = new NbBytes();
		ret.add(NbDialect.MSG_OBJECT_CMD);
		ret.addInt(getId());
		ret.add(getRealVel());
		return ret;
	}

	@Override
	public int getDir() {
		long vel = getVel();
		return vel > SET_POINT ? NbDoubleDir.DIR_LEFT : vel <SET_POINT ? NbDoubleDir.DIR_RIGHT : NbDoubleDir.DIR_NONE;
	}

	@Override
	public void setDir(int dir) {
		long vel = Math.abs(getVel() - SET_POINT);
		if(dir == NbDoubleDir.DIR_LEFT){
			setVel(SET_POINT + vel);
		}else if(dir == NbDoubleDir.DIR_RIGHT){
			setVel(SET_POINT - vel);
		}else{
			stop();
		}
	}
	
	@Override
	public void stop() {
		setVel(SET_POINT);
	}
	
}
