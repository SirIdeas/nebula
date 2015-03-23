package com.nebula.sample.colorfollowing;

import org.opencv.android.BaseLoaderCallback;
import org.opencv.android.CameraBridgeViewBase;
import org.opencv.android.OpenCVLoader;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewFrame;
import org.opencv.android.LoaderCallbackInterface;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewListener2;
import org.opencv.core.Core;
import org.opencv.core.Mat;
import org.opencv.core.Point;
import org.opencv.core.Scalar;

import com.nebula.helpers.NbBtMainActivityHelper;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.NbServo;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class MainActivity extends NbBtMainActivityHelper implements CvCameraViewListener2, OnSeekBarChangeListener{
	
	private static final int 	KD 						=  50;
	
	private static final int	ID_SERVO_IZQ 			= 4;
	private static final int    ID_SERVO_DER 			= 5;
	private static final int 	INIT_SERVOS 			= NbDialect.__LAST_MSG_CODE + 3;
	
	private static final Scalar	FACE_LINE_COLOR     	= new Scalar(0, 255, 0, 255);
    private static final int 	MAX_VEL_MID 			= 30;
    private static final long 	OBJECT_SIZE_SET_POINT 	= 8000;
	
    public static final int MAX_H = 180;
	public static final int MAX_S = 255;
	public static final int MAX_V = 255;

	public static enum HSVConfElements {MIN_H, MIN_S, MIN_V, MAX_H, MAX_S, MAX_V, };

	private boolean	debug 	= true;
	private int[] 	hsv 	= { 0, 0, 0, MAX_H, MAX_S, MAX_V};
	private int eLast       = 0;
	private int eObjectLast = 0;
	private long timeLast   = 0;
	
	private Mat 	mRgba; 
	private Mat 	mGray;
	
    private CameraBridgeViewBase 	mOpenCvCameraView;
	private SeekBar 				sbMinH, sbMinS, sbMinV, sbMaxH, sbMaxS, sbMaxV;
	
	public static SharedPreferences sp = null;
	public static final String PREFERENCES_NAME = ".PREFERENCES";
	
	@SuppressLint("InlinedApi")
	public static int preferencesMode = Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB ?
			Context.MODE_MULTI_PROCESS : 
				Context.MODE_WORLD_WRITEABLE;

	private BaseLoaderCallback mLoaderCallback = new BaseLoaderCallback(this) {
		@Override
		public void onManagerConnected(int status) {
	    	if(status == LoaderCallbackInterface.SUCCESS){
	    		System.loadLibrary("mixed_sample");
	    		mOpenCvCameraView.enableView();
	    	}else{
	    		super.onManagerConnected(status);
	        }
	    }
	};

	private NbServo sIz	= new NbServo(ID_SERVO_IZQ);
	private NbServo sDe = new NbServo(ID_SERVO_DER);

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		
		// Inicializar Servos
		getSketch().addSetupByte(INIT_SERVOS);

		// Indicar la actividad a utilizar para listar los accesorios BT
		setBtDeviceListActivityClass(BtDevicesListActivity.class);

		// Conectar el led al Sketch
		getSketch().connect(sIz);
		getSketch().connect(sDe);
		
		getCom().setAutoConnectToDevice("NebulaBoard");
        
		loadConf();
		
        mOpenCvCameraView = (CameraBridgeViewBase)findViewById(R.id.cameraView);
		mOpenCvCameraView.setCameraIndex(1);
        mOpenCvCameraView.setCvCameraViewListener(this);
        
        sbMinH = (SeekBar)findViewById(R.id.seekMinH);
        sbMinS = (SeekBar)findViewById(R.id.seekMinS);
        sbMinV = (SeekBar)findViewById(R.id.seekMinV);
        sbMaxH = (SeekBar)findViewById(R.id.seekMaxH);
        sbMaxS = (SeekBar)findViewById(R.id.seekMaxS);
        sbMaxV = (SeekBar)findViewById(R.id.seekMaxV);
        
        sbMinH.setMax(MAX_H);
        sbMinS.setMax(MAX_S);
        sbMinV.setMax(MAX_V);
        sbMaxH.setMax(MAX_H);
        sbMaxS.setMax(MAX_S);
        sbMaxV.setMax(MAX_V);

        sbMinH.setProgress(hsv[0]);
        sbMinS.setProgress(hsv[1]);
        sbMinV.setProgress(hsv[2]);
        sbMaxH.setProgress(hsv[3]);
        sbMaxS.setProgress(hsv[4]);
        sbMaxV.setProgress(hsv[5]);
        
        sbMinH.setOnSeekBarChangeListener(this);
        sbMinS.setOnSeekBarChangeListener(this);
        sbMinV.setOnSeekBarChangeListener(this);
        sbMaxH.setOnSeekBarChangeListener(this);
        sbMaxS.setOnSeekBarChangeListener(this);
        sbMaxV.setOnSeekBarChangeListener(this);
        
        findViewById(R.id.butDebug).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				debug = !debug;
				saveConf();
			}
		});
        
        
        
	}
	
	public boolean getDebugCamera(){
		return debug;
	}
	
	public void setDebugCamera(boolean pDebugCamera){
		debug = pDebugCamera;
	}
	
	public boolean toogleDebugCamera(){
		debug = !debug;
		return debug;
	}
	
	public int getHSVConfValue(HSVConfElements index){
		return hsv[index.ordinal()];
	}
	
	public void setHSVConfValue(HSVConfElements index, int value){
		hsv[index.ordinal()] = value;
	}

	@Override
	public void onProgressChanged(SeekBar sb, int arg1, boolean arg2) {
		switch (sb.getId()) {
			case R.id.seekMinH: setHSVConfValue(HSVConfElements.MIN_H, sb.getProgress()); break;
			case R.id.seekMinS: setHSVConfValue(HSVConfElements.MIN_S, sb.getProgress()); break;
			case R.id.seekMinV: setHSVConfValue(HSVConfElements.MIN_V, sb.getProgress()); break;
			case R.id.seekMaxH: setHSVConfValue(HSVConfElements.MAX_H, sb.getProgress()); break;
			case R.id.seekMaxS: setHSVConfValue(HSVConfElements.MAX_S, sb.getProgress()); break;
			case R.id.seekMaxV: setHSVConfValue(HSVConfElements.MAX_V, sb.getProgress()); break;
		}
		saveConf();
	}

	@Override
	public void onStartTrackingTouch(SeekBar arg0) {
	}

	@Override
	public void onStopTrackingTouch(SeekBar arg0) {
	}
    
    @Override
    public void onPause(){
        super.onPause();
        if (mOpenCvCameraView != null)
        	mOpenCvCameraView.disableView();
    }
    
    @Override
    public void onResume(){
        super.onResume();
        OpenCVLoader.initAsync(OpenCVLoader.OPENCV_VERSION_2_4_9, this, mLoaderCallback);
    }
    
    public void onDestroy() {
        super.onDestroy();
        if (mOpenCvCameraView != null)
        	mOpenCvCameraView.disableView();
    }

	@Override
	public void onCameraViewStarted(int width, int height) {
	}

	@Override
	public void onCameraViewStopped() {
	}

	@Override
	public Mat onCameraFrame(CvCameraViewFrame inputFrame) {
		
		mRgba = inputFrame.rgba();
        mGray = inputFrame.gray();
        
        int[] result = Comenzar(mGray.getNativeObjAddr(), mRgba.getNativeObjAddr(), hsv, debug);
        
        if(result != null){
        	
        	int x = result[0],
        		y = result[1];
        	long area = result[2];
            
        	Core.line(mRgba, new Point(x, 0), new Point(x, mRgba.height()), FACE_LINE_COLOR, 3);
        	Core.line(mRgba, new Point(0, y), new Point(mRgba.width(), y), FACE_LINE_COLOR, 3);

        	long time = (System.currentTimeMillis());
        	long realArea = Math.min((long)area, OBJECT_SIZE_SET_POINT*2);
        	int eObject = (int)mapear(realArea, 0, OBJECT_SIZE_SET_POINT*2, -MAX_VEL_MID, MAX_VEL_MID);
        	int e = (int)mapear(x, 0, mRgba.width(), -MAX_VEL_MID, MAX_VEL_MID);
        	
        	int eDer = 0;
        	int eDerObject = 0;
        	
        	if(timeLast!=0){
        		eDer = (int)(KD*((double)(e - eLast))/((double)(time - timeLast)));
        		eDerObject = (int)(KD*((double)(eObject - eObjectLast))/((double)(time - timeLast)));
        	}
        	
        	int velIzq = 90 + (e + eDer) - (eObject + eDerObject);
        	int velDer = 90 + (e + eDer) + (eObject + eDerObject);
    		
    		eLast = e;
    		eObjectLast = eObject;
    		timeLast = time;
        	
    		sIz.setVel(velIzq);
    		sDe.setVel(velDer);
        	
            Log.d("resultado", String.format("resultado: x=%d, e=%d, a=%d, o=%d, i=%d, d=%d, ed=%d, od=%d",
            		x, e, area, eObject, velIzq, velDer, eDer, eDerObject));
        	
        }else{
        	
    		sIz.stop();
    		sDe.stop();
    		
        }
        
        Core.flip(mRgba, mRgba, 1);
    	
        return mRgba;
        
	}
    
    public double mapear(double value, double minFrom, double maxFrom, double minTo, double maxTo){
    	return minTo + value * (maxTo - minTo) / (maxFrom - minFrom);
    }
    
    
    public SharedPreferences getSharedPreferences(){
    	if(sp == null)
			sp = getSharedPreferences(getPackageName() + PREFERENCES_NAME, preferencesMode);
    	return sp;
    }
    
	public void loadConf(){
		
		HSVConfElements[] hsvConfElements = HSVConfElements.values();
		
		sp = getSharedPreferences();
		
		for(int j=0; j<hsvConfElements.length; j++)
			hsv[j] = sp.getInt(hsvConfElements[j].name(), hsv[j]);
		
		debug = sp.getBoolean("debug", debug);
		
	}
	
	public void saveConf(){

		SharedPreferences.Editor spe = getSharedPreferences().edit();
		HSVConfElements[] hsvConfElements = HSVConfElements.values();
		
		for(int j=0; j<hsvConfElements.length; j++)
			spe.putInt(hsvConfElements[j].name(), hsv[j]);
		
		spe.putBoolean("debug", debug);
		spe.commit();
		
	}
	
	public static native int[] Comenzar(long matAddrGr, long matAddrRgba, int[] hsv, boolean debug);

}
