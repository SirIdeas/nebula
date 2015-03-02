package com.nebula.samples.facedetection;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;

import org.opencv.android.BaseLoaderCallback;
import org.opencv.android.CameraBridgeViewBase;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewFrame;
import org.opencv.android.CameraBridgeViewBase.CvCameraViewListener2;
import org.opencv.android.LoaderCallbackInterface;
import org.opencv.android.OpenCVLoader;
import org.opencv.core.Core;
import org.opencv.core.Mat;
import org.opencv.core.MatOfRect;
import org.opencv.core.Point;
import org.opencv.core.Rect;
import org.opencv.core.Scalar;
import org.opencv.core.Size;
import org.opencv.objdetect.CascadeClassifier;
import org.opencv.samples.facedetect.DetectionBasedTracker;

import com.nebula.helpers.NbBtMainActivityHelper;
import com.nebula.samples.facedetection.R;
import com.nebula.sketch.cmp.NbServo;

import android.content.Context;
import android.os.Bundle;
import android.util.Log;
import android.view.WindowManager;

public class MainActivity extends NbBtMainActivityHelper implements CvCameraViewListener2 {
	
	private static final int       	ID_SERVO_IZQ = 1;
	private static final int       	ID_SERVO_DER = 2;

    private static final int 		MAX_VEL_MID 			= 40;
    private static final int 		CONSTANTE_CALIBRACION 	= 20;
    private static final long 		FACE_SIZE_SET_POINT 	= 90000;

	private File 					mCascadeFile;
	private CascadeClassifier 		mJavaDetector;
	private DetectionBasedTracker 	mNativeDetector;
	
	private static final Scalar		FACE_LINE_COLOR     	= new Scalar(0, 255, 0, 255);
    private Mat                  	mRgba;
    private Mat                 	mGray;
	private CameraBridgeViewBase 	mOpenCvCameraView;

    private float                  	mRelativeFaceSize   	= 0.2f;
    private int                    	mAbsoluteFaceSize  		= 0;

	private NbServo 				sIz 					= new NbServo(ID_SERVO_IZQ);
	private NbServo 				sDe 					= new NbServo(ID_SERVO_DER);

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		setContentView(R.layout.activity_main);

		// Indicar la actividad a utilizar para listar los accesorios BT
		setBtDeviceListActivityClass(BtDevicesListActivity.class);

		// Conectar el led al Sketch
		getSketch().connect(sIz);
		getSketch().connect(sDe);
		
		getCom().setAutoConnectToDevice("NebulaBoard");

		mOpenCvCameraView = (CameraBridgeViewBase) findViewById(R.id.fd_activity_surface_view);
		mOpenCvCameraView.setCameraIndex(1);
		mOpenCvCameraView.setCvCameraViewListener(this);

	}

	@Override
	public void onPause() {
		super.onPause();
		if (mOpenCvCameraView != null)
			mOpenCvCameraView.disableView();
	}

	@Override
	protected void onResume() {
		super.onResume();
		OpenCVLoader.initAsync(OpenCVLoader.OPENCV_VERSION_2_4_9, this, mLoaderCallback);
	}

	@Override
	protected void onDestroy() {
		mOpenCvCameraView.disableView();
		super.onDestroy();
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
        
        if (mAbsoluteFaceSize == 0) {
            int height = mGray.rows();
            if (Math.round(height * mRelativeFaceSize) > 0) {
                mAbsoluteFaceSize = Math.round(height * mRelativeFaceSize);
            }
            mNativeDetector.setMinFaceSize(mAbsoluteFaceSize);
        }

        MatOfRect faces = new MatOfRect();
        
        // Obtener El listado de caras detecadas
        if (mJavaDetector != null)
            mJavaDetector.detectMultiScale(mGray, faces, 1.1, 2, 2, // TODO: objdetect.CV_HAAR_SCALE_IMAGE
                    new Size(mAbsoluteFaceSize, mAbsoluteFaceSize), new Size());
        
        // Obtener la de mayor área.
        Rect[] facesArray = faces.toArray();
        Rect mayor = null;
        double areaMayor = 0;
        for (int i = 0; i < facesArray.length; i++){
        	if(facesArray[i].area() > areaMayor){
        		areaMayor = facesArray[i].area();
        		mayor = facesArray[i];
        	}
        }
        
        if(mayor!=null){
        	
        	
        	int x = (int)((double)mayor.x + (double)mayor.width/2),
        		y = (int)((double)mayor.y + (double)mayor.height/2);
        			
        	Core.line(mRgba, new Point(x, 0), new Point(x, mRgba.height()), FACE_LINE_COLOR, 3);
        	Core.line(mRgba, new Point(0, y), new Point(mRgba.width(), y), FACE_LINE_COLOR, 3);
        	
        	int eFace = (int)mapear(areaMayor, 0, FACE_SIZE_SET_POINT*2, -MAX_VEL_MID, MAX_VEL_MID);
        	int e = (int)mapear(x + CONSTANTE_CALIBRACION, 0, mRgba.width(), -MAX_VEL_MID, MAX_VEL_MID);
        	
        	int velIzq = 90 + e - eFace;
        	int velDer = 90 + e + eFace;
        	
    		sIz.setVel(velIzq);
    		sDe.setVel(velDer);
	        
            Log.d("facesArray", String.format("facesArray: x=%d, e=%d, eFace=%d, velIzq=%d, velDer=%d", x, e, eFace, velIzq, velDer));
            
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

	private BaseLoaderCallback mLoaderCallback = new BaseLoaderCallback(this) {
		@Override
		public void onManagerConnected(int status) {
			switch (status) {
			case LoaderCallbackInterface.SUCCESS: {

				// Load native library after(!) OpenCV initialization
				System.loadLibrary("detection_based_tracker");

				try {
					// load cascade file from application resources
					InputStream is = getResources().openRawResource(R.raw.lbpcascade_frontalface);
					File cascadeDir = getDir("cascade", Context.MODE_PRIVATE);
					mCascadeFile = new File(cascadeDir, "lbpcascade_frontalface.xml");
					FileOutputStream os = new FileOutputStream(mCascadeFile);

					byte[] buffer = new byte[4096];
					int bytesRead;
					while ((bytesRead = is.read(buffer)) != -1) {
						os.write(buffer, 0, bytesRead);
					}
					is.close();
					os.close();

					mJavaDetector = new CascadeClassifier(
							mCascadeFile.getAbsolutePath());
					if (mJavaDetector.empty()) {
						mJavaDetector = null;
					}

					mNativeDetector = new DetectionBasedTracker(
							mCascadeFile.getAbsolutePath(), 0);

					cascadeDir.delete();

				} catch (IOException e) {
					e.printStackTrace();
				}

				mOpenCvCameraView.enableView();
			}
				break;
			default: {
				super.onManagerConnected(status);
			}
				break;
			}
		}
	};

}
