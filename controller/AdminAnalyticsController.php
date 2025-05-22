<?php
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../model/game.php';
require_once __DIR__ . '/../model/order.php';

class AdminAnalyticsController
{
    private PDO  $db;
    private User $userModel;
    private Game $gameModel;
    private Order $orderModel;

    public function __construct(PDO $db)
    {
        $this->db         = $db;
        $this->userModel  = new User($db);
        $this->gameModel  = new Game($db);
        $this->orderModel = new Order($db);
    }
    public function analytics(): array
    {
        // 1) Totals
        $totalUsers  = $this->userModel->getUsersCount();
        $totalGames  = $this->gameModel->getGamesCount();
        $totalOrders = $this->orderModel->getTotalOrders();

        // 2) Define 30-day window (including today)
        $endDate   = new DateTime();
        $startDate = (clone $endDate)->modify('-29 days');
        $format    = 'Y-m-d';

        // Build date labels
        $labels = [];
        $period = new DatePeriod(
            $startDate,
            new DateInterval('P1D'),
            (clone $endDate)->modify('+1 day')
        );
        foreach ($period as $dt) {
            $labels[] = $dt->format($format);
        }

        // 3) Fetch daily data series
        $dailyNewUsers  = $this->userModel->getDailyNewUsers(
            $startDate->format($format),
            $endDate->format($format)
        );
        $dailyNewOrders = $this->orderModel->getDailyNewOrders(
            $startDate->format($format),
            $endDate->format($format)
        );
        $dailyProfit    = $this->orderModel->getDailyProfit(
            $startDate->format($format),
            $endDate->format($format)
        );

        return [
            'totalUsers'    => $totalUsers,
            'totalGames'    => $totalGames,
            'totalOrders'   => $totalOrders,
            'labels'        => $labels,
            'dailyNewUsers'  => $this->fillZeroSeries($labels, $dailyNewUsers),
            'dailyNewOrders' => $this->fillZeroSeries($labels, $dailyNewOrders),
            'dailyProfit'    => $this->fillZeroSeries($labels, $dailyProfit),
        ];
    }

    /**
     * Ensure every date in labels has a value (0 if missing)
     * @param string[] $labels
     * @param array<string,int|float> $dataMap
     * @return array<string,int|float>
     */
    private function fillZeroSeries(array $labels, array $dataMap): array
    {
        $filled = [];
        foreach ($labels as $date) {
            $filled[$date] = $dataMap[$date] ?? 0;
        }
        return $filled;
    }
}
