package com.android.mdw.demo;

import java.util.List;

import com.nebula.helpers.NbBtMainActivityHelper;
import com.nebula.sketch.NbDialect;
import com.nebula.sketch.cmp.NbServo;

import android.content.pm.ActivityInfo;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.util.Log;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.Toast;

public class MainActivity extends NbBtMainActivityHelper implements SensorEventListener {
	
	private static final int ID_SERVO_IZQ = 4;
	private static final int ID_SERVO_DER = 5;
	private static final int INIT_SERVOS  = NbDialect.__LAST_MSG_CODE + 3;
    
	private long last_update = 0, last_movement = 0;
    private float prevX = 0, prevY = 0, prevZ = 0;
    private float curX = 0, curY = 0, curZ = 0;

	private NbServo sIz = new NbServo(ID_SERVO_IZQ);
	private NbServo sDe = new NbServo(ID_SERVO_DER);
    
    @Override
    public void onCreate(Bundle savedInstanceState) {
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
		
    }
    
    @Override
    protected void onResume() {
        super.onResume();
        SensorManager sm = (SensorManager) getSystemService(SENSOR_SERVICE);
        List<Sensor> sensors = sm.getSensorList(Sensor.TYPE_ACCELEROMETER);        
        if (sensors.size() > 0) {
        	sm.registerListener(this, sensors.get(0), SensorManager.SENSOR_DELAY_GAME);
        }
    }
    
    @Override
    protected void onStop() {
    	SensorManager sm = (SensorManager) getSystemService(SENSOR_SERVICE);    	
        sm.unregisterListener(this);
        super.onStop();
    }

	@Override
	public void onAccuracyChanged(Sensor sensor, int accuracy) {}

	@Override
	public void onSensorChanged(SensorEvent event) {
        synchronized (this) {
        	long current_time = event.timestamp;
            
            curX = event.values[0];
            curY = event.values[1];
            curZ = event.values[2];
            
            if (prevX == 0 && prevY == 0 && prevZ == 0) {
                last_update = current_time;
                last_movement = current_time;
                prevX = curX;
                prevY = curY;
                prevZ = curZ;
            }

            long time_difference = current_time - last_update;
            if (time_difference > 0) {
                float movement = Math.abs((curX + curY + curZ) - (prevX - prevY - prevZ)) / time_difference;
                int limit = 1500;
                float min_movement = 1E-6f;
                if (movement > min_movement) {
                    if (current_time - last_movement >= limit) {                    	
//                        Toast.makeText(getApplicationContext(), "Hay movimiento de " + movement, Toast.LENGTH_SHORT).show();
                    }
                    last_movement = current_time;
                }
                prevX = curX;
                prevY = curY;
                prevZ = curZ;
                last_update = current_time;
            }
            
            ((TextView) findViewById(R.id.txtAccX)).setText("Acelerómetro X: " + curX);
            ((TextView) findViewById(R.id.txtAccY)).setText("Acelerómetro Y: " + curY);
            ((TextView) findViewById(R.id.txtAccZ)).setText("Acelerómetro Z: " + curZ);
            
            mover(curX, curY, curZ);
            
        }
		
	}
	
	private void mover(float x, float y, float z) {
		
		int velIzq = 90 + (int)(z * 4) + (int)(y * 4);
		int velDer = 90 - (int)(z * 4) + (int)(y * 4);
		
		sIz.setVel(velIzq);
		sDe.setVel(velDer);
		
		Log.d("resultado", String.format("resultado: x=%d, y=%d, z=%d, i=%d, d=%d", (int)x, (int)y, (int)z, velIzq, velDer));
		
	}
    
}