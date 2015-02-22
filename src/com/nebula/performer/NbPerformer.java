package com.nebula.performer;

import com.nebula.NbBuffer;

public interface NbPerformer {
	public void setup();
	public boolean received(NbBuffer data);
}
