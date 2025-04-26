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



}