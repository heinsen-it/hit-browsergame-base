<?php
namespace hitbgbase\aicore;

class aigamebase {
    /** @var array $gameState Aktueller Spielzustand */
    private $gameState;

    /** @var array $rules Regelwerk für die KI */
    private $rules;

    /** @var string $currentState Aktueller Zustand für den Zustandsautomaten */
    private $currentState;

    /** @var array $stateTransitions Zustandsübergänge für den Zustandsautomaten */
    private $stateTransitions;

    /** @var array $decisionHistory Verlauf der getroffenen Entscheidungen */
    private $decisionHistory;

    /** @var int $difficultyLevel Schwierigkeitsgrad der KI (1-10) */
    private $difficultyLevel;

    /**
     * Konstruktor für die GameAI Klasse
     *
     * @param array $initialGameState Anfänglicher Spielzustand
     * @param int $difficultyLevel Schwierigkeitsgrad der KI (1-10)
     */
    public function __construct(array $initialGameState = [], int $difficultyLevel = 5) {
        $this->gameState = $initialGameState;
        $this->rules = [];
        $this->currentState = 'idle';
        $this->stateTransitions = [];
        $this->decisionHistory = [];
        $this->difficultyLevel = max(1, min(10, $difficultyLevel));
    }

}