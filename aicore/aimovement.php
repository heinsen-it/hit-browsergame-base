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


    /**
     * Setzt die Detailinformationen für Regionen
     *
     * @param array $regionDetails Details für die Regionen
     * @return void
     */
    public function setRegionDetails(array $regionDetails): void {
        foreach ($regionDetails as $regionId => $details) {
            if (isset($this->regions[$regionId])) {
                $this->regions[$regionId] = array_merge($this->regions[$regionId], $details);

                // Aktualisiere die Besitzerlisten
                $this->updateRegionOwnership($regionId, $details['owner'] ?? $this->regions[$regionId]['owner']);
            }
        }
    }

    /**
     * Aktualisiert die Besitzlisten der Regionen
     *
     * @param string $regionId ID der Region
     * @param string $owner Besitzer ('ai', 'player', 'neutral')
     * @return void
     */
    private function updateRegionOwnership(string $regionId, string $owner): void {
        // Entferne die Region aus allen Besitzlisten
        $this->aiControlledRegions = array_diff($this->aiControlledRegions, [$regionId]);
        $this->playerControlledRegions = array_diff($this->playerControlledRegions, [$regionId]);
        $this->neutralRegions = array_diff($this->neutralRegions, [$regionId]);

        // Füge die Region zur entsprechenden Besitzliste hinzu
        switch ($owner) {
            case 'ai':
                $this->aiControlledRegions[] = $regionId;
                break;
            case 'player':
                $this->playerControlledRegions[] = $regionId;
                break;
            default:
                $this->neutralRegions[] = $regionId;
                break;
        }

        // Aktualisiere den Besitzer in der Region
        $this->regions[$regionId]['owner'] = $owner;
    }

}