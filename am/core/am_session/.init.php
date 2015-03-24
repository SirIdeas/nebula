<?php

// Se debe iniciar la sesion
session_start();

// Asignar id de la sesion
Am::call("session.id", Am::getAttribute("session"));
