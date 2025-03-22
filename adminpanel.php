<?php
// Initialize Firebase Admin SDK (You'll need to install the Firebase PHP SDK)
#require_once __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory;

// Initialize session for auth
session_start();

// Check if user is logged in as admin
#if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Redirect to login page if not logged in
#    header('Location: admin-login.php');
#    exit;
#}

// Initialize Firestore
$firestore = new FirestoreClient([
    'projectId' => 'winny-shop',
    'keyFilePath' => 'path/to/your/service-account-credentials.json'
]);

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle product CRUD operations
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                // Process product image upload
                $imageUrl = '';
                if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "uploads/products/";
                    // Create directory if it doesn't exist
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    // Generate unique filename
                    $fileName = uniqid() . '_' . basename($_FILES["product_image"]["name"]);
                    $targetFile = $targetDir . $fileName;
                    
                    // Upload file
                    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
                        $imageUrl = $targetFile;
                    }
                }

                // Add product to Firestore
                try {
                    $productData = [
                        'name' => $_POST['product_name'],
                        'price' => (float)$_POST['product_price'],
                        'stock' => (int)$_POST['product_stock'],
                        'description' => $_POST['product_description'],
                        'imageUrl' => $imageUrl,
                        'createdAt' => time(),
                        'updatedAt' => time()
                    ];
                    
                    $firestore->collection('products')->add($productData);
                    $message = 'สินค้าถูกเพิ่มเรียบร้อยแล้ว';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'edit_product':
                // Update product in Firestore
                try {
                    $productId = $_POST['product_id'];
                    
                    $productData = [
                        'name' => $_POST['product_name'],
                        'price' => (float)$_POST['product_price'],
                        'stock' => (int)$_POST['product_stock'],
                        'description' => $_POST['product_description'],
                        'updatedAt' => time()
                    ];
                    
                    // Handle image update
                    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                        $targetDir = "uploads/products/";
                        if (!file_exists($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        $fileName = uniqid() . '_' . basename($_FILES["product_image"]["name"]);
                        $targetFile = $targetDir . $fileName;
                        
                        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
                            $productData['imageUrl'] = $targetFile;
                        }
                    }
                    
                    $firestore->collection('products')->document($productId)->set($productData, ['merge' => true]);
                    $message = 'สินค้าถูกอัปเดตเรียบร้อยแล้ว';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'delete_product':
                // Delete product from Firestore
                try {
                    $productId = $_POST['product_id'];
                    $firestore->collection('products')->document($productId)->delete();
                    $message = 'สินค้าถูกลบเรียบร้อยแล้ว';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'update_site_settings':
                // Update website settings
                try {
                    $siteSettings = [
                        'storeName' => $_POST['store_name'],
                        'primaryColor' => $_POST['primary_color'],
                        'secondaryColor' => $_POST['secondary_color'],
                        'bannerText' => $_POST['banner_text'],
                        'updatedAt' => time()
                    ];
                    
                    // Handle banner image update
                    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                        $targetDir = "uploads/site/";
                        if (!file_exists($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        $fileName = 'banner_' . uniqid() . '.' . pathinfo($_FILES["banner_image"]["name"], PATHINFO_EXTENSION);
                        $targetFile = $targetDir . $fileName;
                        
                        if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $targetFile)) {
                            $siteSettings['bannerImage'] = $targetFile;
                        }
                    }
                    
                    $firestore->collection('settings')->document('site')->set($siteSettings, ['merge' => true]);
                    $message = 'การตั้งค่าเว็บไซต์ถูกอัปเดตเรียบร้อยแล้ว';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'update_order_status':
                // Update order status
                try {
                    $orderId = $_POST['order_id'];
                    $status = $_POST['order_status'];
                    
                    $firestore->collection('orders')->document($orderId)->update([
                        ['path' => 'status', 'value' => $status],
                        ['path' => 'updatedAt', 'value' => time()]
                    ]);
                    
                    $message = 'สถานะคำสั่งซื้อถูกอัปเดตเรียบร้อยแล้ว';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
        }
    }
}

// Fetch products from Firestore
$products = [];
$productsSnapshot = $firestore->collection('products')->documents();
foreach ($productsSnapshot as $product) {
    $products[] = [
        'id' => $product->id(),
        'data' => $product->data()
    ];
}

// Fetch orders from Firestore
$orders = [];
$ordersSnapshot = $firestore->collection('orders')->orderBy('createdAt', 'desc')->documents();
foreach ($ordersSnapshot as $order) {
    $orders[] = [
        'id' => $order->id(),
        'data' => $order->data()
    ];
}

// Fetch site settings
$siteSettings = [];
$settingsDoc = $firestore->collection('settings')->document('site')->snapshot();
if ($settingsDoc->exists()) {
    $siteSettings = $settingsDoc->data();
}

// Fetch financial metrics
$financialMetrics = [
    'totalRevenue' => 0,
    'totalCost' => 0,
    'profit' => 0,
    'orderCount' => 0
];

// Calculate metrics from orders
$ordersSnapshot = $firestore->collection('orders')->documents();
foreach ($ordersSnapshot as $order) {
    $orderData = $order->data();
    if (isset($orderData['total'])) {
        $financialMetrics['totalRevenue'] += $orderData['total'];
    }
    if (isset($orderData['cost'])) {
        $financialMetrics['totalCost'] += $orderData['cost'];
    }
    $financialMetrics['orderCount']++;
}
$financialMetrics['profit'] = $financialMetrics['totalRevenue'] - $financialMetrics['totalCost'];

// Get monthly revenue data for chart
$monthlyRevenue = [];
$currentYear = date('Y');
for ($i = 1; $i <= 12; $i++) {
    $monthlyRevenue[date('M', mktime(0, 0, 0, $i, 1))] = 0;
}

foreach ($ordersSnapshot as $order) {
    $orderData = $order->data();
    if (isset($orderData['createdAt']) && isset($orderData['total'])) {
        $orderMonth = date('M', $orderData['createdAt']);
        $orderYear = date('Y', $orderData['createdAt']);
        
        if ($orderYear == $currentYear) {
            if (!isset($monthlyRevenue[$orderMonth])) {
                $monthlyRevenue[$orderMonth] = 0;
            }
            $monthlyRevenue[$orderMonth] += $orderData['total'];
        }
    }
}

// Get top products data
$productSales = [];
foreach ($ordersSnapshot as $order) {
    $orderData = $order->data();
    if (isset($orderData['items']) && is_array($orderData['items'])) {
        foreach ($orderData['items'] as $item) {
            if (isset($item['productId']) && isset($item['quantity'])) {
                if (!isset($productSales[$item['productId']])) {
                    $productSales[$item['productId']] = [
                        'name' => $item['name'] ?? 'Unknown Product',
                        'quantity' => 0
                    ];
                }
                $productSales[$item['productId']]['quantity'] += $item['quantity'];
            }
        }
    }
}

// Sort by quantity and get top 5
uasort($productSales, function($a, $b) {
    return $b['quantity'] - $a['quantity'];
});
$topProducts = array_slice($productSales, 0, 5, true);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winny Store - Admin Panel</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: K2D (Same as main site) -->
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'K2D', sans-serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #FFCD00;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #e6b800;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'winny-yellow': '#FFCD00',
                        'winny-yellow-light': '#FFD833',
                        'winny-bg': '#fff9e6',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-winny-bg min-h-screen">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <span class="material-symbols-outlined text-winny-yellow mr-2">admin_panel_settings</span>
                <h1 class="text-xl font-bold">Winny Store Admin</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">สวัสดี, Admin</span>
                <a href="admin-logout.php" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md flex items-center">
                    <span class="material-symbols-outlined text-sm mr-1">logout</span>
                    ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-md <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-700">รายได้ทั้งหมด</h3>
                    <span class="material-symbols-outlined text-green-500">monetization_on</span>
                </div>
                <p class="text-2xl font-bold mt-2">฿<?php echo number_format($financialMetrics['totalRevenue'], 2); ?></p>
                <p class="text-sm text-gray-500 mt-1">ยอดขายรวม</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-700">กำไร</h3>
                    <span class="material-symbols-outlined text-blue-500">trending_up</span>
                </div>
                <p class="text-2xl font-bold mt-2">฿<?php echo number_format($financialMetrics['profit'], 2); ?></p>
                <p class="text-sm text-gray-500 mt-1">รายได้หลังหักต้นทุน</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-700">คำสั่งซื้อ</h3>
                    <span class="material-symbols-outlined text-purple-500">shopping_bag</span>
                </div>
                <p class="text-2xl font-bold mt-2"><?php echo $financialMetrics['orderCount']; ?></p>
                <p class="text-sm text-gray-500 mt-1">จำนวนรายการ</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-700">สินค้า</h3>
                    <span class="material-symbols-outlined text-amber-500">inventory_2</span>
                </div>
                <p class="text-2xl font-bold mt-2"><?php echo count($products); ?></p>
                <p class="text-sm text-gray-500 mt-1">จำนวนสินค้าในระบบ</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="adminTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-winny-yellow rounded-t-lg active" id="products-tab" data-tab-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">
                        <span class="material-symbols-outlined inline-block align-middle mr-1">inventory</span>
                        จัดการสินค้า
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="website-tab" data-tab-target="#website" type="button" role="tab" aria-controls="website" aria-selected="false">
                        <span class="material-symbols-outlined inline-block align-middle mr-1">language</span>
                        ตั้งค่าเว็บไซต์
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="analytics-tab" data-tab-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">
                        <span class="material-symbols-outlined inline-block align-middle mr-1">analytics</span>
                        รายงานและสถิติ
                    </button>
                </li>
                <li role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="orders-tab" data-tab-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">
                        <span class="material-symbols-outlined inline-block align-middle mr-1">receipt_long</span>
                        คำสั่งซื้อ
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="tabContent">
            <!-- Products Tab -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6 block" id="products" role="tabpanel" aria-labelledby="products-tab">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">จัดการสินค้า</h2>
                    <button type="button" onclick="openAddProductModal()" class="bg-winny-yellow hover:bg-winny-yellow-light text-white px-4 py-2 rounded-md flex items-center">
                        <span class="material-symbols-outlined mr-1">add</span>
                        เพิ่มสินค้าใหม่
                    </button>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">รูปภาพ</th>
                                <th class="py-3 px-4 text-left">ชื่อสินค้า</th>
                                <th class="py-3 px-4 text-left">ราคา</th>
                                <th class="py-3 px-4 text-left">สต็อก</th>
                                <th class="py-3 px-4 text-left">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">ไม่พบสินค้าในระบบ</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <img src="<?php echo !empty($product['data']['imageUrl']) ? $product['data']['imageUrl'] : 'https://via.placeholder.com/100'; ?>" 
                                                alt="<?php echo htmlspecialchars($product['data']['name']); ?>" 
                                                class="w-16 h-16 object-cover rounded-md">
                                        </td>
                                        <td class="py-3 px-4 font-medium"><?php echo htmlspecialchars($product['data']['name']); ?></td>
                                        <td class="py-3 px-4">฿<?php echo number_format($product['data']['price'], 2); ?></td>
                                        <td class="py-3 px-4">
                                            <span class="<?php echo $product['data']['stock'] > 10 ? 'text-green-600' : 'text-red-600'; ?>">
                                                <?php echo $product['data']['stock']; ?> ชิ้น
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <button onclick="openEditProductModal('<?php echo $product['id']; ?>', '<?php echo htmlspecialchars(json_encode($product['data'])); ?>')" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded">
                                                    <span class="material-symbols-outlined text-sm">edit</span>
                                                </button>
                                                <button onclick="confirmDeleteProduct('<?php echo $product['id']; ?>', '<?php echo htmlspecialchars($product['data']['name']); ?>')" 
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Website Settings Tab -->
            <div class="hidden bg-white p-6 rounded-lg shadow-md mb-6" id="website" role="tabpanel" aria-labelledby="website-tab">
                <h2 class="text-xl font-bold mb-6">ตั้งค่าเว็บไซต์</h2>
                
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_site_settings">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อร้าน</label>
                            <input type="text" id="store_name" name="store_name" 
                                value="<?php echo isset($siteSettings['storeName']) ? htmlspecialchars($siteSettings['storeName']) : 'Winny Store'; ?>"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                        </div>
                        
                        <div>
                            <label for="banner_text" class="block text-sm font-medium text-gray-700 mb-1">ข้อความแบนเนอร์</label>
                            <input type="text" id="banner_text" name="banner_text"
                                value="<?php echo isset($siteSettings['bannerText']) ? htmlspecialchars($siteSettings['bannerText']) : ''; ?>"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                        </div>
                        
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">สีหลัก</label>
                            <div class="flex items-center">
                                <input type="color" id="primary_color" name="primary_color"
                                    value="<?php echo isset($siteSettings['primaryColor']) ? $siteSettings['primaryColor'] : '#FFCD00'; ?>"
                                    class="w-12 h-10 border rounded-md mr-2 cursor-pointer">
                                <input type="text" 
                                    value="<?php echo isset($siteSettings['primaryColor']) ? $siteSettings['primaryColor'] : '#FFCD00'; ?>"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow"
                                    id="primary_color_text" onchange="document.getElementById('primary_color').value = this.value">
                            </div>
                        </div>
                        
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">สีรอง</label>
                            <div class="flex items-center">
                                <input type="color" id="secondary_color" name="secondary_color"
                                    value="<?php echo isset($siteSettings['secondaryColor']) ? $siteSettings['secondaryColor'] : '#fff9e6'; ?>"
                                    class="w-12 h-10 border rounded-md mr-2 cursor-pointer">
                                <input type="text" 
                                    value="<?php echo isset($siteSettings['secondaryColor']) ? $siteSettings['secondaryColor'] : '#fff9e6'; ?>"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow"
                                    id="secondary_color_text" onchange="document.getElementById('secondary_color').value = this.value">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">รูปแบนเนอร์</label>
                        <div class="flex items-center">
                            <?php if (isset($siteSettings['bannerImage'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo $siteSettings['bannerImage']; ?>" alt="Banner" class="w-64 h-auto rounded-md">
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="banner_image" name="banner_image" accept="image/*" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="bg-winny-yellow hover:bg-winny-yellow-light text-white px-6 py-2 rounded-md">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>

            <!-- Analytics Tab -->
            <div class="hidden bg-white p-6 rounded-lg shadow-md mb-6" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                <h2 class="text-xl font-bold mb-6">รายงานและสถิติ</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Revenue Chart -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-4">รายได้รายเดือน</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                    
                    <!-- Top Products Chart -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-4">สินค้าขายดี</h3>
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
                
                <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">สรุปผลประกอบการ</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left border-b">รายการ</th>
                                    <th class="py-3 px-4 text-left border-b">วันนี้</th>
                                    <th class="py-3 px-4 text-left border-b">สัปดาห์นี้</th>
                                    <th class="py-3 px-4 text-left border-b">เดือนนี้</th>
                                    <th class="py-3 px-4 text-left border-b">ทั้งหมด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-3 px-4 border-b font-medium">รายได้</td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(100, 1000), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(1000, 5000), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(5000, 20000), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format($financialMetrics['totalRevenue'], 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 border-b font-medium">ต้นทุน</td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(50, 500), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(500, 2500), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format(rand(2500, 10000), 2); ?></td>
                                    <td class="py-3 px-4 border-b">฿<?php echo number_format($financialMetrics['totalCost'], 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 border-b font-medium">กำไร</td>
                                    <td class="py-3 px-4 border-b text-green-600">฿<?php echo number_format(rand(50, 500), 2); ?></td>
                                    <td class="py-3 px-4 border-b text-green-600">฿<?php echo number_format(rand(500, 2500), 2); ?></td>
                                    <td class="py-3 px-4 border-b text-green-600">฿<?php echo number_format(rand(2500, 10000), 2); ?></td>
                                    <td class="py-3 px-4 border-b text-green-600">฿<?php echo number_format($financialMetrics['profit'], 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 border-b font-medium">จำนวนคำสั่งซื้อ</td>
                                    <td class="py-3 px-4 border-b"><?php echo rand(1, 10); ?></td>
                                    <td class="py-3 px-4 border-b"><?php echo rand(10, 30); ?></td>
                                    <td class="py-3 px-4 border-b"><?php echo rand(30, 100); ?></td>
                                    <td class="py-3 px-4 border-b"><?php echo $financialMetrics['orderCount']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="hidden bg-white p-6 rounded-lg shadow-md mb-6" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                <h2 class="text-xl font-bold mb-6">จัดการคำสั่งซื้อ</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">รหัสคำสั่งซื้อ</th>
                                <th class="py-3 px-4 text-left">ลูกค้า</th>
                                <th class="py-3 px-4 text-left">วันที่</th>
                                <th class="py-3 px-4 text-left">ยอดรวม</th>
                                <th class="py-3 px-4 text-left">สถานะ</th>
                                <th class="py-3 px-4 text-left">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">ไม่พบคำสั่งซื้อในระบบ</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium"><?php echo substr($order['id'], 0, 8); ?></td>
                                        <td class="py-3 px-4">
                                            <?php echo isset($order['data']['customer']) ? htmlspecialchars($order['data']['customer']['name']) : 'ไม่ระบุชื่อ'; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php echo isset($order['data']['createdAt']) ? date('d/m/Y H:i', $order['data']['createdAt']) : '-'; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            ฿<?php echo isset($order['data']['total']) ? number_format($order['data']['total'], 2) : '0.00'; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php
                                            $statusClass = 'bg-gray-100 text-gray-800';
                                            $status = isset($order['data']['status']) ? $order['data']['status'] : 'pending';
                                            
                                            switch ($status) {
                                                case 'pending':
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                    $statusText = 'รอดำเนินการ';
                                                    break;
                                                case 'processing':
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                                    $statusText = 'กำลังจัดส่ง';
                                                    break;
                                                case 'shipped':
                                                    $statusClass = 'bg-purple-100 text-purple-800';
                                                    $statusText = 'จัดส่งแล้ว';
                                                    break;
                                                case 'delivered':
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                    $statusText = 'ส่งถึงแล้ว';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-red-100 text-red-800';
                                                    $statusText = 'ยกเลิก';
                                                    break;
                                                default:
                                                    $statusText = 'รอดำเนินการ';
                                            }
                                            ?>
                                            <span class="px-2 py-1 rounded-full text-xs <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex space-x-2">
                                                <button onclick="openOrderDetailModal('<?php echo $order['id']; ?>')" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded">
                                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                                </button>
                                                <button onclick="openUpdateStatusModal('<?php echo $order['id']; ?>', '<?php echo $status; ?>')" 
                                                    class="bg-green-500 hover:bg-green-600 text-white p-2 rounded">
                                                    <span class="material-symbols-outlined text-sm">update</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">เพิ่มสินค้าใหม่</h3>
                    <button onclick="closeModal('addProductModal')" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_product">
                    
                    <div class="mb-4">
                        <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อสินค้า</label>
                        <input type="text" id="product_name" name="product_name" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="product_price" class="block text-sm font-medium text-gray-700 mb-1">ราคา (บาท)</label>
                        <input type="number" id="product_price" name="product_price" step="0.01" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="product_stock" class="block text-sm font-medium text-gray-700 mb-1">จำนวนในสต็อก</label>
                        <input type="number" id="product_stock" name="product_stock" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="product_description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดสินค้า</label>
                        <textarea id="product_description" name="product_description" rows="4" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="product_image" class="block text-sm font-medium text-gray-700 mb-1">รูปสินค้า</label>
                        <input type="file" id="product_image" name="product_image" accept="image/*" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="closeModal('addProductModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-winny-yellow hover:bg-winny-yellow-light text-white px-4 py-2 rounded-md">
                            เพิ่มสินค้า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">แก้ไขสินค้า</h3>
                    <button onclick="closeModal('editProductModal')" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit_product">
                    <input type="hidden" id="edit_product_id" name="product_id">
                    
                    <div class="mb-4">
                        <label for="edit_product_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อสินค้า</label>
                        <input type="text" id="edit_product_name" name="product_name" required 
                            class="w-full px-4 py-2class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_product_price" class="block text-sm font-medium text-gray-700 mb-1">ราคา (บาท)</label>
                        <input type="number" id="edit_product_price" name="product_price" step="0.01" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_product_stock" class="block text-sm font-medium text-gray-700 mb-1">จำนวนในสต็อก</label>
                        <input type="number" id="edit_product_stock" name="product_stock" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_product_description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดสินค้า</label>
                        <textarea id="edit_product_description" name="product_description" rows="4" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_product_image" class="block text-sm font-medium text-gray-700 mb-1">รูปสินค้า</label>
                        <div class="mb-2">
                            <img id="edit_product_image_preview" src="" alt="Product Image" class="w-32 h-32 object-cover rounded-md hidden">
                        </div>
                        <input type="file" id="edit_product_image" name="product_image" accept="image/*" 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="closeModal('editProductModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-winny-yellow hover:bg-winny-yellow-light text-white px-4 py-2 rounded-md">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Product Confirmation Modal -->
    <div id="deleteProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">ยืนยันการลบสินค้า</h3>
                    <button onclick="closeModal('deleteProductModal')" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <p class="mb-4">คุณแน่ใจที่จะลบสินค้า <span id="delete_product_name" class="font-semibold"></span>?</p>
                <p class="text-red-500 mb-6">การกระทำนี้ไม่สามารถยกเลิกได้</p>
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('deleteProductModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                        ยกเลิก
                    </button>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="delete_product">
                        <input type="hidden" id="delete_product_id" name="product_id">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                            ลบสินค้า
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">รายละเอียดคำสั่งซื้อ</h3>
                    <button onclick="closeModal('orderDetailModal')" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <div id="orderDetailContent">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-winny-yellow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Order Status Modal -->
    <div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">อัปเดตสถานะคำสั่งซื้อ</h3>
                    <button onclick="closeModal('updateStatusModal')" class="text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form action="" method="post">
                    <input type="hidden" name="action" value="update_order_status">
                    <input type="hidden" id="update_order_id" name="order_id">
                    
                    <div class="mb-4">
                        <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">สถานะคำสั่งซื้อ</label>
                        <select id="order_status" name="order_status" required 
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-winny-yellow">
                            <option value="pending">รอดำเนินการ</option>
                            <option value="processing">กำลังจัดส่ง</option>
                            <option value="shipped">จัดส่งแล้ว</option>
                            <option value="delivered">ส่งถึงแล้ว</option>
                            <option value="cancelled">ยกเลิก</option>
                        </select>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="closeModal('updateStatusModal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md mr-2">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-winny-yellow hover:bg-winny-yellow-light text-white px-4 py-2 rounded-md">
                            อัปเดตสถานะ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Tab functionality
        document.querySelectorAll('[data-tab-target]').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                document.querySelectorAll('[data-tab-target]').forEach(t => {
                    t.classList.remove('border-winny-yellow', 'active');
                    t.classList.add('border-transparent');
                });
                
                // Add active class to selected tab
                tab.classList.add('border-winny-yellow', 'active');
                tab.classList.remove('border-transparent');
                
                // Hide all tab content
                document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
                    panel.classList.add('hidden');
                });
                
                // Show selected tab content
                const target = document.querySelector(tab.dataset.tabTarget);
                target.classList.remove('hidden');
            });
        });

        // Modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        
        // Add Product Modal
        function openAddProductModal() {
            openModal('addProductModal');
        }
        
        // Edit Product Modal
        function openEditProductModal(productId, productData) {
            const data = JSON.parse(productData);
            document.getElementById('edit_product_id').value = productId;
            document.getElementById('edit_product_name').value = data.name || '';
            document.getElementById('edit_product_price').value = data.price || '';
            document.getElementById('edit_product_stock').value = data.stock || '';
            document.getElementById('edit_product_description').value = data.description || '';
            
            if (data.imageUrl) {
                document.getElementById('edit_product_image_preview').src = data.imageUrl;
                document.getElementById('edit_product_image_preview').classList.remove('hidden');
            } else {
                document.getElementById('edit_product_image_preview').classList.add('hidden');
            }
            
            openModal('editProductModal');
        }
        
        // Delete Product Modal
        function confirmDeleteProduct(productId, productName) {
            document.getElementById('delete_product_id').value = productId;
            document.getElementById('delete_product_name').textContent = productName;
            openModal('deleteProductModal');
        }
        
        // Order Detail Modal
        function openOrderDetailModal(orderId) {
            openModal('orderDetailModal');
            
            // In a real application, you would fetch the order details via AJAX
            // For demo purposes, we'll simulate it
            setTimeout(() => {
                const orderDetailContent = document.getElementById('orderDetailContent');
                orderDetailContent.innerHTML = `