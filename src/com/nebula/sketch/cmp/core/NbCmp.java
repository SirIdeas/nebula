package com.nebula.sketch.cmp.core;

import com.nebula.NbBytes;
import com.nebula.sketch.NbSketch;

import android.os.Handler;
import android.os.Message;


/**
 * Clase base para representar los componentes electrónicos.
 * 
 * @version 1.0
 */
public class NbCmp {
	
	/**
	 * Id del componente.
	 */
	private int mId = 0;
	
	/**
	 * Librerías necesarias para el funcionamiento del dispositivo.
	 * Cuando esta propiedad es igual a -1 entonces no es un valor inválido. 
	 */
//	private int mLibNeed = 0;
	
	/**
	 * Valor de componente.
	 * Cuando esta propiedad es igual a -1 entonces no es un valor inválido. 
	 */
	private long mValue = -1;
	
	/**
	 * Indica si el dispositivo esta activo o no.
	 * Si el dispositivo esta inactivo entonces se no se enviaran sus datos a microcontrolador. 
	 */
	private boolean mState = true;
	
	/**
	 * Oído para manejar los cambios de valor.
	 */
	private OnValueChangeListener mOnValueChangeListener;
	
	/**
	 * Oiído para manejar los cambios de estados.
	 */
	private OnStateChangeListener mOnStateChangeListener;
	
	/**
	 * Constructor.
	 * Genera un ID sin utilizar para el componente que esta siendo instanciado. 
	 */
	public NbCmp(){
	}
	
	/**
	 * Obtiene el ID del componente.
	 * @return	Retorna el ID del componente.
	 */
	public int getId(){
		return mId;
	}
	
	/**
	 * Asigna un Id al componente.
	 * 
	 * @param id	Id a asignar.
	 */
	protected void setId(int id) {
		mId = id;
	}
	
	/**
	 * Obtiene el valor del componente.
	 * 
	 * @return	Retorna el valor del componente
	 */
	public long getValue(){
		return mValue;
	}
	
	/**
	 * Obiene el valor interpolado entre min y max 
	 * 
	 * @param min		Minimo valor posible
	 * @param max		Maximo valor posible
	 * @param srcMin	Minimo valor posible del origen
	 * @param srcMax	Maximo valor posible del origen
	 * @return 			Valor interpolado
	 */
	public float getValueMap(float pMin, float pMax, float pSrcMin, float pSrcMax){
		float min = Math.min(pMin, pMax);
		float max = Math.max(pMin, pMax) - min;
		float srcMin = Math.min(pSrcMin, pSrcMax);
		float srcMax = Math.max(pSrcMin, pSrcMax) - srcMin;
		float ret = getValue() - srcMin;
		
		ret = ret * max / srcMax;
		
		return min + ret;
	}
	
	/**
	 * Asigna un valor al componente.
	 * 
	 * @param value		Valor a asignar.
	 */
	public void setValue(long value) {
		if(mValue != value) mHandler.obtainMessage(NbCmpMsgEnum.CHANGE_VALUE.ordinal(), (int)value, (int)mValue, this).sendToTarget();
		mValue = value;
	}
	
	/**
	 * Asigan un oído para para cuando el componente cambie de valor.
	 * 
	 * @param listener	Listener a asignar
	 */
	public void setOnValueChangeListener(OnValueChangeListener listener){
		mOnValueChangeListener = listener;
	}

	/**
	 * Obtiene el Listener del cambio de valor.
	 * 
	 * @return 	Listener para el cambio de valor
	 */
	public OnValueChangeListener getOnValueChangeListener(){
		return mOnValueChangeListener;
	}
	
	/**
	 * Indica si las se cargaron las librerias necesarias para el funcionamiento dle dispositivo.
	 * 
	 * @param 	libsLoaded		Indica que librerias estan cargadas.
	 * @return					Retorna el valor del componente
	 */
//	public boolean isLibLoaded(int libsLoaded){
//		return mLibNeed == (libsLoaded & mLibNeed);
//	}
	
	/**
	 * Asigna las librerias necesarias.
	 * 
	 * @param libNeed		Valor a asignar.
	 */
//	protected void setLibNeed(int libNeed) {
//		mLibNeed = libNeed;
//	}
	
	/**
	 * Asigna un estado del ispositivo.
	 * 
	 * @param active	Valor de activación a asignar.
	 */
	public void setState(boolean state) {
		if(mState != state) mHandler.obtainMessage(NbCmpMsgEnum.CHANGE_STATE.ordinal(), state ? 1 : 0, -1, this).sendToTarget();
		mState = state;
	}
	
	/**
	 * Indica si el dispositivo esta activo o no.
	 * 
	 * @return	Retorna <code>true</code> si el dispositivo esta activo de lo contrario <code>false</code>.
	 */
	public boolean isActive(){
		return mState;
	}
	
	/**
	 * Activa el dispositivo.
	 */
	public void activate() {
		setState(true);
	}
	
	/**
	 * Desactiva el dispositivo.
	 */
	public void desactivate() {
		setState(false);
	}
	
	/**
	 * Asigan un oído para para cuando el componente cambie de estado.
	 * 
	 * @param listener	Listener a asignar
	 */
	public void setOnStateChangeListener(OnStateChangeListener listener){
		mOnStateChangeListener = listener;
	}

	/**
	 * Obtiene el Listener del cambio de estado.
	 * 
	 * @return 	Listener para el cambio de estado
	 */
	public OnStateChangeListener getOnStateChangeListener(){
		return mOnStateChangeListener;
	}
	
	/**
	 * Función para configurar el componente dentro del Sketch.
	 * 
	 * @param sketch	Instancia de Sketch al que se esta agregando el componente. 
	 */
	public void configure(NbSketch sketch) {}
	
	/**
	 * Obtiene un vector con los bytes para la configuración del componente. 
	 * @return	Un vector con los bytes de configuración.
	 */
	public NbBytes getSetupBytes(){
		return null;
	}
	
	/**
	 * Interface para generar eventos que indiquen los cambios en compoentens de entrada.
	 * 
	 * @version 1.0
	 */
	public interface OnValueChangeListener{
		
		/**
		 * Método llamado cuando se genera un cambio en un compoentende entrada.
		 * 
		 * @param component		Instancia del componente que cambió.
		 * @param value			Nuevo valor del componente.
		 */
		public void onValueChange(NbCmp component, int newValue, int oldValue);
		
	}

	/**
	 * Interface para generar eventos que indiquen los cambios de estado de los componentes.
	 * 
	 * @version 1.0
	 */
	public interface OnStateChangeListener{
		
		/**
		 * Método llamado cuando se genera un cambio en un compoentende entrada.
		 * 
		 * @param component		Instancia del componente que cambió.
		 * @param value			Nuevo valor del componente.
		 */
		public void onStateChange(NbCmp component, boolean newState);
		
	}
	
	/**
	 * Manejador de eventos en el componente.
	 */
	private static final Handler mHandler = new Handler(){
		
		/**
		 * Sobre escribir el manejador de mensajes.
		 */
		public void handleMessage(Message msg) {
			
			// Cambio en el estado de un componente.
			// La instancia del componente que cambio de estado es recivida en el argumento objeto del
			// mensaje. El nuevo estado asignado es == true si el arg1 del mensaje es == 1.
			if(msg.what == NbCmpMsgEnum.CHANGE_STATE.ordinal()){
				
				NbCmp component = (NbCmp)msg.obj;
				OnStateChangeListener listener = component.getOnStateChangeListener();
				if(listener!=null) listener.onStateChange(component, msg.arg1==1);
				
			}
			
			// Cambio el valor de un componente.
			// La instancia del componente que cambio es recivida en el argumento objeto del mensaje.
			// El nuevo valor del componente es enviado por el primer argumento entero del mensaje
			// El antiguo valor del componente es enviado por el segundo argumento entero del mensaje
			if(msg.what == NbCmpMsgEnum.CHANGE_VALUE.ordinal()){
				
				NbCmp component = (NbCmp)msg.obj;
				OnValueChangeListener listener = component.getOnValueChangeListener();
				if(listener!=null) listener.onValueChange(component, msg.arg1, msg.arg2);
				
			}
			
		}
		
	};
	
}
