<?php
namespace hitbgbase\aicore;

/**
 * AI Handler
 *
 * Eine übergeordnete Klasse, die verschiedene KI-Subsysteme koordiniert und strategische
 * Gesamtentscheidungen für eine KI-gesteuerte Fraktion trifft. Der AIGovernor verwaltet
 * militärische, wirtschaftliche, diplomatische und entwicklungsbezogene Entscheidungen.
 */
class aihandler {
    /** @var string $factionName Name der Fraktion */
    private $factionName;

    /** @var string $personalityType Persönlichkeitstyp des Governors */
    private $personalityType;

    /** @var array $personalityTraits Persönlichkeitsmerkmale des Governors */
    private $personalityTraits;

    /** @var GameAI $gameAI KI-Entscheidungsengine für allgemeine Spielentscheidungen */
    private $gameAI;

    /** @var TroopMovementCalculator $troopMovement KI für militärische Bewegungen */
    private $troopMovement;

    /** @var AIStockTrader $economyManager KI für wirtschaftliche Entscheidungen */
    private $economyManager;

    /** @var array $subsystems Weitere KI-Subsysteme */
    private $subsystems;

    /** @var array $globalState Globaler Spielzustand */
    private $globalState;

    /** @var array $priorityMatrix Prioritätsmatrix für verschiedene Aspekte */
    private $priorityMatrix;

    /** @var array $decisionHistory Verlauf der getroffenen Entscheidungen */
    private $decisionHistory;

    /** @var array $factionObjectives Ziele der Fraktion */
    private $factionObjectives;

    /** @var array $turnPlan Aktionsplan für den aktuellen Spielzug */
    private $turnPlan;

    /** @var int $currentTurn Aktueller Spielzug */
    private $currentTurn;

    /** @var float $aggression Aggressionslevel (0.0 bis 1.0) */
    private $aggression;

    /** @var float $caution Vorsichtslevel (0.0 bis 1.0) */
    private $caution;

    /** @var float $expansion Expansionsdrang (0.0 bis 1.0) */
    private $expansion;

    /** @var float $adaptability Anpassungsfähigkeit (0.0 bis 1.0) */
    private $adaptability;



}