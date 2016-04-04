package com.nebula.sketch.cmp;

import com.nebula.NbBytes;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.out.NbCmpOutObj;

public class NbLiquidCrystal extends NbCmpOutObj{
	
	private int mCol=0, mRow=0, mCols;
	
	public NbLiquidCrystal(int id, int cols){
		super(id);
		mCols = cols;
		synchronize();
	}
	
	protected NbBytes getClearBytes(){
		NbBytes list = new NbBytes();
		list.add(NbDialect.CMD_LCD_CLEAR);
		return list;
	}
	
	protected NbBytes getSetCursorBytes(int col, int row) {
		NbBytes list = new NbBytes();
		list.add(NbDialect.CMD_LCD_SET_CURSOR);
		list.add(col);
		list.add(row);
		return list;
	}
	
	protected NbBytes getPrintBytes(String str) {
		NbBytes list = new NbBytes();
		list.add(NbDialect.CMD_LCD_PRINT);
		list.add(str.length());
		for(int i=0; i<str.length(); i++){
			list.add(str.charAt(i));
		}
		return list;
	}
	
	public int getCursorX(){
		return mCol;
	}
	
	public int getCursorY(){
		return mRow;
	}
	
	public int getCols(){
		return mCols;
	}
	
	public void clear(){
		if(isActive()){
			mCol = mRow = 0;
			addCmd(getClearBytes());
		}
	}
	
	public void setCursor(int x, int y){
		if(isActive()){
			mCol = x;
			mRow = y;
			addCmd(getSetCursorBytes(x, y));
		}
	}
	
	public String print(String str){
		if(isActive()){
			
			// Truncar la cadena al tamaño maximo de la columna
			if(mCols-mCol<str.length())
				str = str.substring(0, mCols-mCol);
			
			addCmd(getPrintBytes(str));
			mCol+=str.length();
			
			return str;
			
		}
		
		return null;
		
	}
	
}
