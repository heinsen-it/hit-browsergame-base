<?php

namespace hitbgbase\aicore;

/**
 *
 * Eine Klasse zur Berechnung von KI-gesteuerten Truppenbewegungen zwischen verschiedenen
 * Regionen auf einer Landkarte, basierend auf strategischen Kriterien.
 */
class aimovement {
    /** @var array $map Karte mit Regionen und deren Verbindungen */
    private array $map;







    /**
     * Lädt die Kartendaten / Polygone basierte Daten
     *
     * @param array $mapData Kartendaten mit Regionen und Verbindungen
     * @return void
     */
    public function loadMap(array $mapData): void {
      //ToDo: Behandlung von Polygondaten
    }


}