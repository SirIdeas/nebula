<?php

// Asignar callbacks
Am::setCallback("session.all", "AmNormalSession::all");
Am::setCallback("session.get", "AmNormalSession::get");
Am::setCallback("session.set", "AmNormalSession::set");
Am::setCallback("session.has", "AmNormalSession::has");
Am::setCallback("session.delete", "AmNormalSession::delete");
Am::setCallback("session.id", "AmNormalSession::id");
