# Nébula Librería Android

Librería Android para desarrollo de aplicaciónes basadas en el proyecto Nébula.

## Requerimientos
- Android Studio

## Instalación

Descargar desde [GitHub](https://github.com/SirIdeas/nebula/archive/android.zip) y descomprimir en la carpeta deseada.

## Uso Básico

### Comunicación por BT
```java
// Actividad para elegir el dispositivo al que se conectará
public class BtDevicesListActivity extends NbBtDeviceListActivityHelper {
}

// Actividad principal
public class MainActivity extends NbBtMainActivityHelper{
  
  // Instanciar un LED Digital
  private NbLedDigital led = new NbLedDigital(13);
  
  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
    getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
    
    // Indicar la actividad a utilizar para listar los accesorios BT
    setBtDeviceListActivityClass(BtDevicesListActivity.class);
    
    // Conectar el led al Sketch
    getSketch().connect(led);
    
    // Asignar listener al ToggleButton para encender y apagar el LED
    ((ToggleButton)findViewById(R.id.toggleButton1))
      .setOnCheckedChangeListener(new OnCheckedChangeListener() {
      
        @Override
        public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
          led.setValue(isChecked);
        }
        
      });
    
  }
  
}
```

### Comunicación por ADK
```java
public class MainActivity extends NbAdkMainActivityHelper {
    
  // Instanciar un LED Digital
  private NbLedDigital led = new NbLedDigital(13);

  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
    getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
      
    // Conectar el led al Sketch
    getSketch().connect(led);
      
    // Asignar listener al ToggleButton para encender y apagar el LED
    ((ToggleButton)findViewById(R.id.toggleButton1))
      .setOnCheckedChangeListener(new OnCheckedChangeListener() {
          
        @Override
        public void onCheckedChanged(CompoundButton bv, boolean isChecked) {
          led.setValue(isChecked);
        }
          
      });
      
  }
  
}
```

## Ejemplos

- [Control de led por BT](https://github.com/SirIdeas/nebula/tree/sample.led.blink.bt)
- [Control de led por ADK](https://github.com/SirIdeas/nebula/tree/sample.led.blink.adk)
- [Comando por mensajes de textos](https://github.com/SirIdeas/nebula/tree/sample.messages)
- [Prueba de control de NebulaBot](https://github.com/SirIdeas/nebula/tree/sample.test)
- [Control con Acelerómetro](https://github.com/SirIdeas/nebula/tree/sample.accelerometer)
- [Seguimiento de color - OpenCV](https://github.com/SirIdeas/nebula/tree/sample.follow.color)
- [Seguimiento de rostro - OpenCV](https://github.com/SirIdeas/nebula/tree/sample.follow.face)

## Mas información
Visita el Website con la documentación del [Proyecto Nébula](http://nebula.sirideas.com/) o el repositorio en [GitHub](https://github.com/SirIdeas/nebula)

##Licencia
Liberado bajo licencia [MIT](https://github.com/SirIdeas/nebula/blob/master/LICENSE).