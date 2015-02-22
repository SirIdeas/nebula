package com.nebula.sketch.cmp.interfaces;

public interface NbDoubleDir {
	
	public static final int DIR_NONE 	= 0x01;
	public static final int DIR_LEFT 	= 0x02;
	public static final int DIR_RIGHT 	= 0x03;
	
	public int getDir();
	
	public void setDir(int dir);
	
}
