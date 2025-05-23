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
     * Aktualisiert alle technischen Indikatoren für jede Aktie
     *
     * @return void
     */
    private function updateTechnicalIndicators(): void {
        foreach ($this->marketData as $symbol => $data) {
            // Nur berechnen, wenn wir genügend Daten haben
            if (count($data) < 20) {
                continue;
            }

            // Extrahiere die Schlusskurse
            $closePrices = array_column($data, 'close');

            // Berechne SMA (Simple Moving Average)
            $this->technicalIndicators[$symbol]['sma_20'] = $this->calcSMA($closePrices, 20);

            if (count($data) >= 50) {
                $this->technicalIndicators[$symbol]['sma_50'] = $this->calcSMA($closePrices, 50);
            }

            if (count($data) >= 200) {
                $this->technicalIndicators[$symbol]['sma_200'] = $this->calcSMA($closePrices, 200);
            }

            // Berechne EMA (Exponential Moving Average)
            $this->technicalIndicators[$symbol]['ema_12'] = $this->calcEMA($closePrices, 12);
            $this->technicalIndicators[$symbol]['ema_26'] = $this->calcEMA($closePrices, 26);

            // Berechne MACD (Moving Average Convergence Divergence)
            $this->technicalIndicators[$symbol]['macd'] =
                $this->technicalIndicators[$symbol]['ema_12'] - $this->technicalIndicators[$symbol]['ema_26'];

            // Berechne MACD Signal Line (9-day EMA of MACD)
            $macdHistory = [];
            $dataCount = count($data);
            for ($i = max(0, $dataCount - 20); $i < $dataCount; $i++) {
                $macdHistory[] = $this->technicalIndicators[$symbol]['macd'];
            }
            $this->technicalIndicators[$symbol]['macd_signal'] = $this->calcEMA($macdHistory, 9);

            // Berechne MACD Histogram
            $this->technicalIndicators[$symbol]['macd_histogram'] =
                $this->technicalIndicators[$symbol]['macd'] - $this->technicalIndicators[$symbol]['macd_signal'];

            // Berechne RSI (Relative Strength Index)
            $this->technicalIndicators[$symbol]['rsi'] = $this->calcRSI($closePrices, 14);

            // Berechne Bollinger Bands
            $this->calcBollingerBands($symbol, $closePrices, 20, 2);
        }
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


    /**
     * Berechnet den Relative Strength Index (RSI)
     *
     * @param array $prices Array mit Preisdaten
     * @param int $period Periode für den RSI
     * @return float Berechneter RSI
     */
    private function calcRSI(array $prices, int $period): float {
        $priceCount = count($prices);
        if ($priceCount <= $period) {
            return 50; // Standardwert, wenn nicht genügend Daten vorhanden sind
        }

        $gains = 0;
        $losses = 0;

        // Berechne anfängliche Gewinne und Verluste
        for ($i = 1; $i <= $period; $i++) {
            $change = $prices[$priceCount - $i] - $prices[$priceCount - $i - 1];
            if ($change >= 0) {
                $gains += $change;
            } else {
                $losses -= $change; // Betrag des Verlusts (daher -=)
            }
        }

        // Durchschnittliche Gewinne und Verluste
        $avgGain = $gains / $period;
        $avgLoss = $losses / $period;

        // Berechne RS und RSI
        if ($avgLoss == 0) {
            return 100;
        }

        $rs = $avgGain / $avgLoss;
        $rsi = 100 - (100 / (1 + $rs));

        return $rsi;
    }


    /**
     * Berechnet die Bollinger Bänder
     *
     * @param string $symbol Symbol der Aktie
     * @param array $prices Array mit Preisdaten
     * @param int $period Periode für die Bollinger Bänder
     * @param float $deviation Standardabweichungsfaktor
     * @return void
     */
    private function calcBollingerBands(string $symbol, array $prices, int $period, float $deviation): void {
        $priceCount = count($prices);
        if ($priceCount < $period) {
            $lastPrice = end($prices);
            $this->technicalIndicators[$symbol]['bollinger_middle'] = $lastPrice;
            $this->technicalIndicators[$symbol]['bollinger_upper'] = $lastPrice;
            $this->technicalIndicators[$symbol]['bollinger_lower'] = $lastPrice;
            return;
        }

        // Berechne SMA als mittleres Band
        $middle = $this->calcSMA($prices, $period);

        // Berechne Standardabweichung
        $sum = 0;
        for ($i = $priceCount - $period; $i < $priceCount; $i++) {
            $sum += pow($prices[$i] - $middle, 2);
        }
        $stdDev = sqrt($sum / $period);

        // Berechne oberes und unteres Band
        $upper = $middle + ($stdDev * $deviation);
        $lower = $middle - ($stdDev * $deviation);

        $this->technicalIndicators[$symbol]['bollinger_middle'] = $middle;
        $this->technicalIndicators[$symbol]['bollinger_upper'] = $upper;
        $this->technicalIndicators[$symbol]['bollinger_lower'] = $lower;
    }




    /**
     * Setzt die Handelsstrategie manuell
     *
     * @param string $strategy Die neue Handelsstrategie
     * @return void
     */
    public function setTradingStrategy(string $strategy): void {
        $validStrategies = ['aggressive_growth', 'growth', 'balanced', 'income', 'defensive'];
        if (in_array($strategy, $validStrategies)) {
            $this->tradingStrategy = $strategy;
        }
    }


    /**
     * Gibt den Basis-Portfolioanteil basierend auf der Strategie zurück
     *
     * @return float Basis-Portfolioanteil
     */
    private function getBasePortfolioPercentage(): float {
        switch ($this->tradingStrategy) {
            case 'aggressive_growth':
                return 0.20; // 20% pro Position
            case 'growth':
                return 0.15; // 15% pro Position
            case 'defensive':
                return 0.05; // 5% pro Position
            case 'income':
                return 0.10; // 10% pro Position
            default: // balanced
                return 0.10; // 10% pro Position
        }
    }



    /**
     * Gibt das aktuelle Portfolio zurück
     *
     * @return array Aktuelles Portfolio
     */
    public function getPortfolio(): array {
        return $this->portfolio;
    }

    /**
     * Gibt das verfügbare Bargeld zurück
     *
     * @return float Verfügbares Bargeld
     */
    public function getCash(): float {
        return $this->cash;
    }




    /**
     * Analysiert die Performance der verschiedenen Sektoren
     *
     * @return void
     */
    private function analyzeSectorPerformance(): void {
        $sectorReturns = [];
        $sectorVolume = [];

        // Sammle Daten für jeden Sektor
        foreach ($this->stocksInfo as $symbol => $info) {
            $sector = $info['sector'];

            if (!isset($this->marketData[$symbol]) || count($this->marketData[$symbol]) < 2) {
                continue;
            }

            // Berechne prozentuale Änderung für den letzten Tag
            $data = $this->marketData[$symbol];
            $dataCount = count($data);
            $currentPrice = $data[$dataCount - 1]['close'];
            $previousPrice = $data[$dataCount - 2]['close'];
            $return = ($currentPrice - $previousPrice) / $previousPrice;

            if (!isset($sectorReturns[$sector])) {
                $sectorReturns[$sector] = [];
                $sectorVolume[$sector] = 0;
            }

            $sectorReturns[$sector][] = $return;
            $sectorVolume[$sector] += $data[$dataCount - 1]['volume'];
        }

        // Berechne durchschnittliche Rendite für jeden Sektor
        foreach ($sectorReturns as $sector => $returns) {
            $avgReturn = array_sum($returns) / count($returns);

            $this->sectorPerformance[$sector] = [
                'avg_return' => $avgReturn,
                'volume' => $sectorVolume[$sector],
                'trend' => $avgReturn > 0 ? 'up' : 'down',
                'strength' => abs($avgReturn) * 100 // Stärke des Trends in Prozent
            ];
        }

        // Sortiere Sektoren nach Performance
        uasort($this->sectorPerformance, function($a, $b) {
            return $b['avg_return'] <=> $a['avg_return'];
        });
    }

    /**
     * Passt den Score basierend auf der aktuellen Handelsstrategie an
     *
     * @param float $score Ursprünglicher Score
     * @param string $action Handelstyp ('buy' oder 'sell')
     * @return float Angepasster Score
     */
    private function adjustScoreByStrategy(float $score, string $action): float {
        $adjustmentFactor = 1.0;

        switch ($this->tradingStrategy) {
            case 'aggressive_growth':
                if ($action === 'buy') {
                    $adjustmentFactor = 1.5;
                } else { // sell
                    $adjustmentFactor = 0.7;
                }
                break;

            case 'growth':
                if ($action === 'buy') {
                    $adjustmentFactor = 1.2;
                } else { // sell
                    $adjustmentFactor = 0.8;
                }
                break;

            case 'defensive':
                if ($action === 'buy') {
                    $adjustmentFactor = 0.7;
                } else { // sell
                    $adjustmentFactor = 1.3;
                }
                break;

            case 'income':
                if ($action === 'buy') {
                    $adjustmentFactor = 0.9;
                } else { // sell
                    $adjustmentFactor = 1.1;
                }
                break;

            default: // balanced
                $adjustmentFactor = 1.0;
                break;
        }

        return $score * $adjustmentFactor;
    }



      /**
     * Berechnet die ideale Positionsgröße für einen Kauf
     *
     * @param string $symbol Aktien-Symbol
     * @param float $score Kaufscore
     * @return int Anzahl der zu kaufenden Aktien
     */
    private function calculatePositionSize(string $symbol, float $score): int {
        if (!isset($this->marketData[$symbol])) {
            return 0;
        }

        $data = $this->marketData[$symbol];
        $currentPrice = end($data)['close'];

        // Basis-Portfolioanteil basierend auf der Strategie
        $basePortfolioPercentage = $this->getBasePortfolioPercentage();

        // Anpassung basierend auf dem Score
        $adjustedPercentage = $basePortfolioPercentage * ($score / 5.0);

        // Anpassung basierend auf der Risikotoleranz
        $adjustedPercentage *= $this->riskTolerance;

        // Begrenze den maximalen Prozentsatz pro Position
        $maxPercentage = 0.25; // Maximal 25% des Bargeldes für eine einzelne Position
        $finalPercentage = min($adjustedPercentage, $maxPercentage);

        // Berechne Anzahl der Aktien basierend auf verfügbarem Bargeld
        $amountToInvest = $this->cash * $finalPercentage;
        $quantity = floor($amountToInvest / $currentPrice);

        return $quantity;
    }

    /**
     * Berechnet den Gesamtwert des Portfolios (Bargeld + Aktienbesitz)
     *
     * @return float Gesamtwert des Portfolios
     */
    public function calculatePortfolioValue(): float {
        $stocksValue = 0;

        foreach ($this->portfolio as $symbol => $position) {
            if (!isset($this->marketData[$symbol])) {
                continue;
            }

            $data = $this->marketData[$symbol];
            $currentPrice = end($data)['close'];
            $stocksValue += $currentPrice * $position['quantity'];
        }

        return $this->cash + $stocksValue;
    }


}