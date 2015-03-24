<?php

// Agregar callback de agregar carpetas
Am::setCallback("command.addPath", "AmCommand::addPath");

// Agregar ruta para atender peticiones por consola
Am::setRoute(":arguments(am\.php/.*)", "AmCommand::asTerminal");

// Agregar ruta para atender petidicones HTTP
Am::setRoute("/:arguments(am-command/.*)", "AmCommand::asRequest");

// Agregar carpeta principal
AmCommand::addPath(dirname(__FILE__) . "/cmds/");