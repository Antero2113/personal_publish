<?php
header('Content-Type: application/json; charset=utf-8');
$config = new stdClass();
$loader = require __DIR__ . '/../../config/config.php';
$loader($config);
try 
{
    $pdo = new PDO(
        "mysql:host={$config->db['host']};dbname={$config->db['name']};charset=utf8",
        $config->db['user'],
        $config->db['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} 
catch (Exception $exception) 
{
    echo json_encode(['error' => 'Database connection failed']);
    return;
}
$sql = "
SELECT 
    c.id AS category_id,
    c.title AS category_title,
    s.id AS site_id,
    s.title AS site_title,
    r.url,
    r.size,
    ci.title AS city_title
FROM categories c
LEFT JOIN site_categories sc ON sc.category_id = c.id
LEFT JOIN sites s 
    ON s.id = sc.site_id
    AND s.enabled = 1
    AND s.available = 1
LEFT JOIN resources r 
    ON r.site_id = s.id
    AND r.enabled = 1
    AND r.available = 1
LEFT JOIN cities ci ON ci.id = r.id_city
ORDER BY 
    c.id,
    s.position,
    r.size DESC
";
$statement = $pdo->query($sql);
$max_size = 50 * 1024 * 1024;
$categories = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) 
{
    $category_id = (int) $row['category_id'];
    $site_id = isset($row['site_id']) ? (int) $row['site_id'] : 0;
    if (!isset($categories[$category_id])) 
    {
        $categories[$category_id] = [
            'title' => $row['category_title'],
            'sites' => []
        ];
    }
    if ($site_id === 0) 
    {
        continue;
    }
    if (!isset($categories[$category_id]['sites'][$site_id])) 
    {
        $categories[$category_id]['sites'][$site_id] = [
            'title' => $row['site_title'],
            'resources' => [],
            'current_size' => 0,
            'is_full' => false
        ];
    }
    $site = &$categories[$category_id]['sites'][$site_id];
    if ($site['is_full']) 
    {
        unset($site);
        continue;
    }
    if ($row['url'] !== null) 
    {
        $resource_size = (int) $row['size'];
        if ($site['current_size'] + $resource_size <= $max_size) 
        {
            $site['resources'][] = [
                'url' => $row['url'],
                'size' => $resource_size,
                'city' => $row['city_title'] ?? null
            ];
            $site['current_size'] += $resource_size;
        } 
        else 
        {
            $site['is_full'] = true;
        }
    }
    unset($site);
}
$result = [];
foreach ($categories as $category) 
{
    foreach ($category['sites'] as $site) 
    {
        if (!empty($site['resources'])) 
        {
            shuffle($site['resources']);
        }
        unset($site['current_size'], $site['is_full']);
    }
    $category['sites'] = array_values($category['sites']);
}
unset($category, $site);
$result = array_values($categories);
echo json_encode(
    $result,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
