<?php
namespace hitbgbase\aicore;
/**
 * AI Diplomacy Handler
 *
 * Eine Klasse zur Simulation diplomatischer Beziehungen zwischen verschiedenen Fraktionen oder Staaten.
 * Verwaltet Verträge, Bündnisse, Handelsabkommen und diplomatische Spannungen basierend auf KI-gesteuerten
 * Entscheidungen und historischen Interaktionen.
 */
class aidiplomacyhandler{
    /** @var array $factions Informationen über alle Fraktionen */
    private $factions;

    /** @var array $relations Beziehungsmatrix zwischen den Fraktionen */
    private $relations;

    /** @var array $treaties Aktive Verträge zwischen Fraktionen */
    private $treaties;

    /** @var array $pendingOffers Ausstehende diplomatische Angebote */
    private $pendingOffers;


    /**
     * Konstruktor
     */
    public function __construct() {
        $this->factions = [];
        $this->relations = [];
        $this->treaties = [];
        $this->pendingOffers = [];

    }


    /**
     * Initialisiert die Schwellenwerte für verschiedene Beziehungsstufen
     *
     * @return void
     */
    private function initializeOpinionThresholds(): void {
        $this->opinionThresholds = [
            'war' => -75,           // Unter -75: Kriegszustand
            'hostile' => -25,       // -75 bis -25: Feindlich
            'unfriendly' => -5,     // -25 bis -5: Unfreundlich
            'neutral' => 5,         // -5 bis 5: Neutral
            'cordial' => 25,        // 5 bis 25: Herzlich
            'friendly' => 50,       // 25 bis 50: Freundlich
            'allied' => 75          // Über 75: Verbündet
        ];
    }






    /**
     * Gibt den Beziehungsstatus basierend auf dem Meinungswert zurück
     *
     * @param int $opinion Meinungswert
     * @return string Beziehungsstatus
     */
    private function getRelationshipStatus(int $opinion): string {
        if ($opinion <= $this->opinionThresholds['war']) {
            return 'war';
        } elseif ($opinion <= $this->opinionThresholds['hostile']) {
            return 'hostile';
        } elseif ($opinion <= $this->opinionThresholds['unfriendly']) {
            return 'unfriendly';
        } elseif ($opinion <= $this->opinionThresholds['neutral']) {
            return 'neutral';
        } elseif ($opinion <= $this->opinionThresholds['cordial']) {
            return 'cordial';
        } elseif ($opinion <= $this->opinionThresholds['friendly']) {
            return 'friendly';
        } else {
            return 'allied';
        }
    }

}