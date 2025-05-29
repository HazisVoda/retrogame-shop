<?php
require_once __DIR__ . '/../model/game.php';

class GameController
{
    private PDO  $db;
    private Game $gameModel;

    public function __construct(PDO $db)
    {
        $this->db        = $db;
        $this->gameModel = new Game($db);
    }

    /**
     * Shop listing with filters, sort & 9-per-page pagination
     */
    public function index(): array
    {
        // 1) Pagination setup
        $page    = max((int)($_GET['page'] ?? 1), 1);
        $perPage = 9;
        $offset  = ($page - 1) * $perPage;

        // 2) Pull filter/sort inputs
        $search   = trim($_GET['search']   ?? '');
        $category = $_GET['category']      ?? '';
        $platform = $_GET['platform']      ?? '';
        $priceMin = isset($_GET['price_min']) && $_GET['price_min'] !== ''
            ? (float) $_GET['price_min']
            : null;
        $priceMax = isset($_GET['price_max']) && $_GET['price_max'] !== ''
            ? (float) $_GET['price_max']
            : null;

        $sort     = $_GET['sort']          ?? '';

        // 3) Build WHERE clause
        $where  = ['stock > 0'];
        $params = [];

        if ($search) {
            $where[]          = '(name LIKE :search OR details LIKE :search)';
            $params[':search'] = "%$search%";
        }
        if ($category) {
            $where[]             = 'category = :category';
            $params[':category'] = $category;
        }
        if ($platform) {
            $where[]              = 'platform = :platform';
            $params[':platform']  = $platform;
        }
        if ($priceMin !== null) {
            $where[]               = 'price >= :priceMin';
            $params[':priceMin']   = $priceMin;
        }
        if ($priceMax !== null) {
            $where[]               = 'price <= :priceMax';
            $params[':priceMax']   = $priceMax;
        }

        // 4) Determine ORDER BY
        $orderBy = 'dateReleased DESC';
        switch ($sort) {
            case 'price_asc':  $orderBy = 'price ASC';  break;
            case 'price_desc': $orderBy = 'price DESC'; break;
            case 'name_asc':   $orderBy = 'name ASC';   break;
            case 'name_desc':  $orderBy = 'name DESC';  break;
        }

        $whereSql = implode(' AND ', $where);

        // 5) Total count for pagination
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM games WHERE $whereSql");
        $countStmt->execute($params);
        $total     = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($total / $perPage);

        // 6) Fetch current page
        $sql = "
          SELECT * 
          FROM games 
          WHERE $whereSql 
          ORDER BY $orderBy 
          LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $val, $type);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 7) Sidebar lists
        $cats = $this->db
            ->query("SELECT DISTINCT category FROM games WHERE stock > 0")
            ->fetchAll(PDO::FETCH_COLUMN);

        $plats = $this->db
            ->query("SELECT DISTINCT platform FROM games WHERE stock > 0")
            ->fetchAll(PDO::FETCH_COLUMN);

        return [
            'games'       => $games,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'search'      => $search,
            'category'    => $category,
            'platform'    => $platform,
            'priceMin'    => $priceMin,
            'priceMax'    => $priceMax,
            'sort'        => $sort,
            'categories'  => $cats,
            'platforms'   => $plats,
        ];
    }

    /** unchanged **/
    public function show(): array
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: shop.php');
            exit;
        }
        $game = $this->gameModel->getGameById($id);
        if (!$game || $game['stock'] < 1) {
            header('Location: shop.php');
            exit;
        }
        return ['game' => $game];
    }
}
