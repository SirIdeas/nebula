package com.nebula.sketch.cmp.core;

import java.util.Vector;

public class NbCmpVector extends Vector<NbCmp>{

	/**
	 * 
	 */
	private static final long serialVersionUID = -2926277412147394490L;

	public void setState(boolean state){
		for (NbCmp cmp : this) {
			cmp.setState(state); //Asignar valor recibido.
		}
	}
	
	public void setValue(long value){
		for (NbCmp cmp : this) {
			cmp.setValue(value);	// Asignar valor recibido
		}
	}
	
}
