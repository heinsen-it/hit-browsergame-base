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


    /**
     * Setzt die Truppeninformationen
     *
     * @param array $troopData Informationen über die Truppen
     * @return void
     */
    public function setTroopData(array $troopData): void {
        $this->troops = $troopData;

        // Aktualisiere die Truppenanzahl in den Regionen
        foreach ($troopData as $regionId => $count) {
            if (isset($this->regions[$regionId])) {
                $this->regions[$regionId]['troops'] = $count;
            }
        }
    }

    /**
     * Setzt die KI-Strategie
     *
     * @param string $strategy Strategie ('aggressive', 'defensive', 'balanced', 'expand', 'consolidate')
     * @return void
     */
    public function setAIStrategy(string $strategy): void {
        $validStrategies = ['aggressive', 'defensive', 'balanced', 'expand', 'consolidate'];
        $this->aiStrategy = in_array($strategy, $validStrategies) ? $strategy : 'balanced';
    }

    /**
     * Berechnet den Grenzsicherheitswert einer Region
     *
     * @param string $regionId ID der Region
     * @return float Grenzsicherheitswert
     */
    private function calculateBorderSecurity(string $regionId): float {
        if (!isset($this->map[$regionId])) {
            return 0;
        }

        $connections = $this->map[$regionId];
        $ownedConnections = 0;

        foreach ($connections as $connectedRegion) {
            if (isset($this->regions[$connectedRegion]) &&
                $this->regions[$connectedRegion]['owner'] === 'ai') {
                $ownedConnections++;
            }
        }

        // Verhältnis der eigenen Verbindungen zur Gesamtzahl der Verbindungen
        return $connections ? $ownedConnections / count($connections) : 0;
    }


    /**
     * Berechnet die Bedrohungslevel für alle Regionen
     *
     * @return void
     */
    public function calculateThreatLevels(): void {
        foreach ($this->regions as $regionId => $region) {
            $threatLevel = 0;

            // Nur für KI-kontrollierte oder neutrale Regionen berechnen
            if ($region['owner'] !== 'player') {
                // Prüfe angrenzende Regionen auf feindliche Truppen
                if (isset($this->map[$regionId])) {
                    foreach ($this->map[$regionId] as $connectedRegion) {
                        if (isset($this->regions[$connectedRegion]) &&
                            $this->regions[$connectedRegion]['owner'] === 'player') {
                            // Bedrohung basierend auf feindlichen Truppen und deren Entfernung
                            $enemyTroops = $this->regions[$connectedRegion]['troops'] ?? 0;
                            $threatLevel += $enemyTroops; // Direkt angrenzend

                            // Sekundäre Bedrohung von weiter entfernten Regionen
                            $secondaryThreats = $this->calculateSecondaryThreats($connectedRegion, $regionId);  //Offener Aspekt TODO
                            $threatLevel += $secondaryThreats * 0.5; // Halbierter Einfluss
                        }
                    }
                }
            }

            $this->threatLevels[$regionId] = $threatLevel;
        }
    }



}