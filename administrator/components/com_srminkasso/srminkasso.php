<?php
/**
 * Haupt-Einstiegspunkt in srminkasso Backend.
 *
 * Einstiegspunkt im Backend
 *
 * @package    srminkasso
 * @subpackage Backend
 * @author     hanspeter salvisberg
 * @license    GNU/GPL
 */
defined('_JEXEC') or die;
JLoader::import('joomla.application.component.controller');

/* Einstieg in die Komponente - MyThingsController instanziieren */
$controller = JController::getInstance('srminkasso');

/* Das Anwendungsobjekt holen  */
$app = JFactory::getApplication();

/* Aufgabe (task) ausführen. Hier ist das die Ausgabe der Standardview */
$controller->execute($app->input->get('task'));

/* Dialogsteuerung */
$controller->redirect();
