<?php
namespace hitbgbase\aicore;
/**
 * AI Stock Simulator
 *
 * Eine Klasse zur Simulation eines KI-gesteuerten Börsenhandels mit fiktiven Aktien.
 * Die KI trifft Handelsentscheidungen basierend auf Marktdaten, technischen Indikatoren
 * und verschiedenen Handelsstrategien.
 */
class aistocksim {
    /** @var array $portfolio Aktuelles Portfolio der KI */
    private array $portfolio;

    /** @var array $marketData Marktdaten der fiktiven Aktien */
    private array $marketData;

    /** @var array $stocksInfo Informationen über die fiktiven Aktien */
    private array $stocksInfo;

    /** @var string $tradingStrategy Aktuelle Handelsstrategie der KI */
    private string $tradingStrategy;

    /** @var float $cash Verfügbares Bargeld */
    private float $cash;

    /** @var array $tradeHistory Verlauf der getätigten Trades */
    private array $tradeHistory;

    /** @var float $riskTolerance Risikotoleranz der KI (0.1 bis 1.0) */
    private float $riskTolerance;

    /** @var array $technicalIndicators Berechnete technische Indikatoren */
    private array $technicalIndicators;

    /** @var array $marketTrends Erkannte Markttrends */
    private array $marketTrends;

    /** @var array $sectorPerformance Performance der verschiedenen Sektoren */
    private array $sectorPerformance;

    /** @var array $tradingRules Regeln für den Handel */
    private array $tradingRules;





    /**
     * Konstruktor
     *
     * @param float $initialCash Anfängliches Bargeld
     * @param float $riskTolerance Risikotoleranz (0.1 bis 1.0)
     * @param string $tradingStrategy Anfängliche Handelsstrategie
     */
    public function __construct(float $initialCash = 100000.0, float $riskTolerance = 0.5, string $tradingStrategy = 'balanced') {
        // Setzen aller Werte auf Default
        $this->portfolio = [];
        $this->marketData = [];
        $this->stocksInfo = [];
        $this->cash = $initialCash;
        $this->riskTolerance = max(0.1, min(1.0, $riskTolerance));
        $this->tradingStrategy = $tradingStrategy;
        $this->tradeHistory = [];
        $this->technicalIndicators = [];
        $this->marketTrends = [];
        $this->sectorPerformance = [];
        $this->tradingRules = [];


        // Initialisiere Standardregeln für den Handel der AI
        $this->InitDefaultTradingRules();
    }



    /**
     * Initialisiert Standardregeln für den Handel
     *
     * @return void
     */
    private function InitDefaultTradingRules(): void {

        // ToDo: Regelwerk implementation
    }



    /**
     * Berechnet den einfachen gleitenden Durchschnitt (SMA)
     *
     * @param array $prices Array mit Preisdaten
     * @param int $period Periode für den gleitenden Durchschnitt
     * @return float Berechneter SMA
     */
    private function calcSMA(array $prices, int $period): float {
        $priceCount = count($prices);
        if ($priceCount < $period) {
            return end($prices);
        }

        $sum = 0;
        for ($i = $priceCount - $period; $i < $priceCount; $i++) {
            $sum += $prices[$i];
        }

        return $sum / $period;
    }


    /**
     * Berechnet den exponentiellen gleitenden Durchschnitt (EMA)
     *
     * @param array $prices Array mit Preisdaten
     * @param int $period Periode für den gleitenden Durchschnitt
     * @return float Berechneter EMA
     */
    private function calcEMA(array $prices, int $period): float {
        $priceCount = count($prices);
        if ($priceCount < $period) {
            return end($prices);
        }

        // Multiplier: (2 / (period + 1))
        $multiplier = 2 / ($period + 1);

        // Start mit SMA für die erste EMA-Berechnung
        $ema = $this->calcSMA(array_slice($prices, 0, $period), $period);

        // Berechne EMA für die verbleibenden Preise
        for ($i = $period; $i < $priceCount; $i++) {
            $ema = ($prices[$i] - $ema) * $multiplier + $ema;
        }

        return $ema;
    }



}