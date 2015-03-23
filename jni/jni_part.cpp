#include <jni.h>
#include <opencv2/core/core.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <opencv2/imgproc/imgproc_c.h>
#include <opencv2/features2d/features2d.hpp>
#include <vector>
#include <android/log.h>

#define APPNAME "MyApp"

using namespace std;
using namespace cv;

const int MAX_NUM_OBJECTS=20;
const int MIN_OBJECT_AREA = 20*20;
const int DATA_SIZE = 3;
//int MAX_OBJECT_AREA = FRAME_HEIGHT*FRAME_WIDTH/1.5;

void morphOps(Mat &thresh){

	//create structuring element that will be used to "dilate" and "erode" image.
	//the element chosen here is a 3px by 3px rectangle

	Mat erodeElement = getStructuringElement( MORPH_RECT, Size(5,5));
    //dilate with larger element so make sure object is nicely visible
	Mat dilateElement = getStructuringElement( MORPH_RECT, Size(8,8));

	erode(thresh,thresh,erodeElement);
	erode(thresh,thresh,erodeElement);

	dilate(thresh,thresh,dilateElement);
	dilate(thresh,thresh,dilateElement);

}

extern "C" {
JNIEXPORT jintArray JNICALL Java_com_nebula_sample_colorfollowing_MainActivity_Comenzar
(JNIEnv *env, jobject thiz, jlong addrGray, jlong addrRgba, jintArray hsv, jboolean debug){

	jint *jhsv;
	jhsv = env->GetIntArrayElements(hsv, NULL);

	jint data[DATA_SIZE];
	jintArray result;
	result = env->NewIntArray(DATA_SIZE);
	if (result == NULL) {
		return NULL; /* fuera de memoria */
	}

    Mat& mGr  = *(Mat*)addrGray;
    Mat& mRgb = *(Mat*)addrRgba;

    int x,y;

	Mat hsv_frame;
	Mat thresholded;

	// Convertimos a HSV
	cvtColor(mRgb, hsv_frame, CV_BGR2HSV);

	// Se filtran los colores dentro del rango de HSV
	inRange(hsv_frame, Scalar(jhsv[0],jhsv[1],jhsv[2], 0), Scalar(jhsv[3], jhsv[4], jhsv[5], 0), thresholded);

	if(debug) cvtColor(thresholded, mRgb, CV_GRAY2BGR);

	CvScalar blue = CV_RGB(64, 64, 255);

	morphOps(thresholded);

	for(int i=0; i<DATA_SIZE; i++)
		data[i]=0;

	jint menor_y = 0;
	vector< vector<Point> > contours;
	vector<Vec4i> hierarchy;

	double refArea = 0;

	findContours((Mat)thresholded, contours, hierarchy, CV_RETR_CCOMP, CV_CHAIN_APPROX_SIMPLE);

	if (hierarchy.size() > 0) {
		int numObjects = hierarchy.size();
		if(numObjects<MAX_NUM_OBJECTS){
			for (int index = 0; index >= 0; index = hierarchy[index][0]) {

				Moments moment = moments((cv::Mat)contours[index]);
				double area = moment.m00;

				if(area>MIN_OBJECT_AREA && area>refArea){
					data[0] = x = moment.m10/area;
					data[1] = y = moment.m01/area;
					data[2] = (int)(refArea = area);
					circle(mRgb, Point(x,y), 3, blue, 2);
					circle(mRgb, Point(x,y), sqrt(area), Scalar(0,255,0,255), 4);
				}

			}
		}
	}

//	env->ReleaseIntArrayElements(hsv, NULL, NULL);

//    __android_log_print(ANDROID_LOG_VERBOSE, APPNAME, "Error: %d. Area: %d, %d,%d,%d %d,%d,%d",
//    		data[0], data[1], jhsv[0], jhsv[1], jhsv[2], jhsv[3], jhsv[4], jhsv[5]);
	env->SetIntArrayRegion(result,0,DATA_SIZE,data);

	return result;

}

}