/* ========================================================================
 * Nebula Android Lib: NbTrace v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

package com.nebula;

import java.util.ArrayList;

import android.app.Activity;
import android.util.Log;
import android.widget.ArrayAdapter;

/**
 * Clase para imprimir logs por cada clases, si el log de esa clases se
 * encuentra activo. Ademas permite agregar los logs a un adapter si asi
 * se desea.
 * 
 */
public class NbTrace {
	
	public static boolean DEBUG_DEF = false;	// Se imprimirán los logs de las clases que no se encuentran en atributo tags
	public static boolean DEBUG = true;			// Se imprimiran los logs de las clases que se encuentran en tags.
	private static ArrayList<String> tags =		// Lista de clases gebernadas por el atributo DEBUG.
			new ArrayList<String>();
	
	/**
	 * Para insertar los tags en un adapter
	 */
	public static Activity activity;				// Necesaria para ejecutar modificar la interfaz de usuario con runOnUiThread
	public static ArrayAdapter<String> mAdapter;	// Adapter donde se agregaran los logs
	
	/**
	 * Obtiene el nombre de clase del objeto que intentan realizar un log.
	 * Si el objeto es null, se trata obj como una instancia de Object.
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 * @return		Nombre de la clase del objeto
	 */
	private static String getClass(Object obj){
		if(obj!=null){
			if(obj instanceof String)
				return (String)obj;
			return obj.getClass().getSimpleName();
		}
		return Object.class.getSimpleName();
	}
	
	/**
	 * Agrega la clase del parámetro obj al atributo tags
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 */
	public static void add(Object obj){
		String tag = getClass(obj);
		if(!tags.contains(tags)) tags.add(tag);	// S
	}
	
	/**
	 * Remueve la clase del parámetro obj al atributo tags
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 */
	public static void remove(Object obj){
		String tag = getClass(obj);
		if(tags.contains(tags)) tags.remove(tag);
	}
	
	/**
	 * Devuelve si la clases del parámetro obj tiene activo el Log.
	 * El log de una clase esta activo si:
	 * - el nombre de la clase está en tags y DEBUG es verdadero.
	 * - el nombre de la clases no está en tags y DEBUG es falso.
	 * - el nombre de la clase no está en tags y DEBUG_DEF es verdaderos
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 * @return		Si la clase del obj tiene activo el log.
	 */
	private static boolean active(Object obj){
		String tag = getClass(obj);
		boolean contains = tags.contains(tag);
		return contains == DEBUG || (!contains && DEBUG_DEF); 
	}
	
	/**
	 * Debug del parámetro Obj con el mensaje msg.
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 * @param msg	Mensaje a imprimir
	 */
	public static final void d(Object obj, String msg){
		if(!active(obj)) return;
		String tag = getClass(obj);
		Log.d(tag, msg);
		addMsg(tag + ": " + msg);
	}
	
	/**
	 * Error del parámetro Obj con el mensaje msg.
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 * @param msg	Mensaje a imprimir.
	 */
	public static final void e(Object obj, String msg){
		e(obj, msg, null);
	}
	
	/**
	 * Debug del parámetro Obj con el mensaje msg.
	 * 
	 * @param obj	Objeto que intenta realizar un log.
	 * @param msg	Mensaje a imprimir.
	 * @param e		Error del mensaje.
	 */
	public static final void e(Object obj, String msg, Exception e){
		String tag = obj.getClass().getSimpleName();
		Log.e(tag, msg, e);
		addMsg(tag + ": " + msg + (e!=null? " - " + e.toString() : ""));
	}
	
	/**
	 * Agrega el mensaje  al adapter.
	 * Si la actividad no ha sido asignada, no se agregará el mensaje a
	 * adapter.
	 * 
	 * @param msg	Mensaje a agregar
	 */
	private static final void addMsg(final String msg){
		
		if(activity !=null && mAdapter!=null){
			// Como se debe modificar la interfaz de usuario se realiza
			// mediante la funcion runOnUiThread de la actividad configurada.
			activity.runOnUiThread(new Runnable(){
				@Override
				public void run() {
					mAdapter.insert(msg, 0);
				}
			});
		}
	}
	
}
