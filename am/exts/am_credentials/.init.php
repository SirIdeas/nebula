<?php

// Funcion para obtener una instancia de para manejar crendenciales
Am::setCallback("credentials.handler", "AmCredentialsHandler::getInstance");

// Inicializar sesion
Am::startSession();