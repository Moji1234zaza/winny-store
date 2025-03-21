<!DOCTYPE html>
<html lang="en">
<head>
<?php       
    include __DIR__ . '/system/class.php';
    $use = new Bannawat;      
    $fetchsettingwebsitedata = $use->fetchsettingwebsitedata('1');      
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิธีซื้อ - <?php echo $fetchsettingwebsitedata['type1']?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'K2D', sans-serif;
            background-color: #fff9e6;
        }
        .yellow-btn {
            background-color: #FFCD00;
        }
        .yellow-btn:hover {
            background-color: #FFD833;
        }
        .step-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <a href="index.php" class="flex items-center">
            <img src="<?php echo $fetchsettingwebsitedata['type5']; ?>" alt="pimmytodgrob" class="w-10 h-10 rounded-full border-2 border-gray-200">
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-700 font-medium hover:text-yellow-500">หน้าแรก</a>
                <a href="howtobuy.php" class="text-yellow-500 font-medium hover:text-yellow-600">วิธีซื้อ</a>
            </div>
            <div>
                <button class="bg-white-400 hover:bg-white-500 text-white px-4 py-1 rounded-md transition-colors duration-200"></button>
            </div>
        </div>
    </nav>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6"></h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-6">ติดต่อ Ig drawi.ngfamily เพื่อสั่งซื้อ </p>
            </div>
        
        </div>
    </div>
</body>
</html>
