package com.nebula.sample.colorfollowing;

import org.opencv.android.BaseLoaderCallback;
import org.opencv.android.CameraBridgeViewBase;
import org.opencv.android.OpenCVLoader;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewFrame;
import org.opencv.android.LoaderCallbackInterface;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewListener2;
import org.opencv.core.Mat;

import com.nebula.helpers.NbBtMainActivityHelper;

import android.os.Bundle;
import android.util.Log;
import android.view.WindowManager;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class MainActivity extends NbBtMainActivityHelper implements CvCameraViewListener2, OnSeekBarChangeListener{
	
    public static final int MAX_H = 180;
	public static final int MAX_S = 255;
	public static final int MAX_V = 255;

	public static enum HSVConfElements {MIN_H, MIN_S, MIN_V, MAX_H, MAX_S, MAX_V, };

	private boolean 				debug = true;
	private int[] 					hsv = { 90, 80, 30,	180,255,180};
	
	private Mat 					mRgba; 
	private Mat 					mGray;
	
    private CameraBridgeViewBase 	mOpenCvCameraView;
	private SeekBar 				sbMinH, sbMinS, sbMinV, sbMaxH, sbMaxS, sbMaxV;

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

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
        
        mOpenCvCameraView = (CameraBridgeViewBase)findViewById(R.id.cameraView);
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
        
        int[] result = Comenzar(mGray.getNativeObjAddr(), mRgba.getNativeObjAddr(), hsv);
        
        Log.d("resultado", String.format("resultado: error: %d, area: %d", result[0], result[1]));
    	
        return mRgba;
        
	}
	
	public static native int[] Comenzar(long matAddrGr, long matAddrRgba, int[] hsv);

}
