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
    <title><?php echo $fetchsettingwebsitedata['type1']; ?></title>     
    <link rel="icon" type="image/x-icon" href="assets/image01.jpg">     
    <!-- Material Icons -->     
     <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />     
     <!-- Tailwind CSS -->     
      <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>     
      <!-- AOS Library -->     
       <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'K2D', sans-serif;
            background-color: #fff9e6;
        }
        .product-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .yellow-btn {
            background-color: #FFCD00;
        }
        .yellow-btn:hover {
            background-color: #FFD833;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <a href="" class="flex items-center">
            <img src="<?php echo $fetchsettingwebsitedata['type5']; ?>" alt="pimmytodgrob" class="w-10 h-10 rounded-full border-2 border-gray-200">
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="index.html" class="text-yellow-500 font-medium hover:text-yellow-600">หน้าแรก</a>
                <a href="howtobuy.php" class="text-gray-700 font-medium hover:text-yellow-500">วิธีซื้อ</a>
            </div>
            <div>
                <button class="bg-white-400 hover:bg-white-500 text-white px-4 py-1 rounded-md transition-colors duration-200"></button>
            </div>
                
        </div>
    </nav>

    <!-- Main Banner Area -->
    <div class="w-full pt-16 pb-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-center mb-4">
            <img src="<?php echo $fetchsettingwebsitedata['type6']; ?>" alt="Banner" class="w-full rounded-lg shadow-md">
            </div>
        </div>
    </div>

    <!-- Product Recommendations -->
    <!-- Product Gallery Section -->
    <div class="container mx-auto px-4 py-6 bg-amber-50 max-w-6xl">
        <!-- Header section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">สินค้าแนะนำ</h2>
                <p class="text-lg text-yellow-500">Product Recommended</p>
            </div>
            
        </div>
        
        <!-- Product Gallery Grid - Fixed Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Product 1 -->
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Product Carousel -->
                <div class="product-carousel relative">
                    <!-- Slides container -->
                    <div class="slides-container overflow-hidden">
                        <div class="slides-track flex transition-transform duration-300">
                            <!-- Slide 1: Collage -->
                            <div class="slide-item w-full flex-shrink-0" style="background-color: #ffecf9;">
                                <div class="slide-item w-full flex-shrink-0">
                                    <img src="<?php echo $fetchsettingwebsitedata['product1']?>" alt="Product Image" class="w-full h-auto">
                                </div>
                            </div>
                            <!-- Additional slides -->
                            <div class="slide-item w-full flex-shrink-0">
                                <img src="/api/placeholder/product/cardpin 1012.jpg" alt="Product Image1" class="w-full h-auto">
                            </div>
                            <div class="slide-item w-full flex-shrink-0">
                                <img src="/api/placeholder/product/cardpin 1151.jpg" alt="Product Image2" class="w-full h-auto">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation arrows -->
                    <button class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black bg-opacity-40 text-white rounded-full flex items-center justify-center z-10">
                        <span>&lt;</span>
                    </button>
                    <button class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black bg-opacity-40 text-white rounded-full flex items-center justify-center z-10">
                        <span>&gt;</span>
                    </button>
                    
                    <!-- Pagination dots -->
                    <div class="carousel-dots absolute bottom-2 left-0 right-0 flex justify-center gap-2">
                        <span class="dot w-2 h-2 rounded-full bg-pink-300"></span>
                        <span class="dot w-2 h-2 rounded-full bg-gray-300"></span>
                        <span class="dot w-2 h-2 rounded-full bg-gray-300"></span>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">การ์ดพิน</h3>
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-yellow-500">เหลือ : 30 ชิ้น</p>
                        <span class="bg-yellow-400 text-white px-3 py-1 rounded-md">฿18</span>
                    </div>
                    <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-md flex justify-center items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                        </svg>
                        <span>สั่งซื้อตอนนี้เลย</span>
                    </button>
                </div>
            </div>
            
            <!-- Product 2 -->
             
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Product Carousel -->
                <div class="product-carousel relative">
                    <!-- Slides container -->
                    <div class="slides-container overflow-hidden">
                        <div class="slides-track flex transition-transform duration-300">
                            <!-- Slide 1 -->
                            <div class="slide-item w-full flex-shrink-0">
                                <img src="/api/placeholder/product/ลายน้ำ 206_20250314215453.png" alt="Product Example" class="w-full h-auto">
                            </div>
                            <!-- Slide 2 -->
                            <div class="slide-item w-full flex-shrink-0">
                                <img src="api/placeholder/product/ลายน้ำ 2583_20250315111134.png" alt="Product Example 2" class="w-full h-auto">
                            </div>
                            <!-- Slide 3 -->
                        
                        </div>
                    </div>
                    
                    <!-- Navigation arrows -->
                    <button class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black bg-opacity-40 text-white rounded-full flex items-center justify-center z-10">
                        <span>&lt;</span>
                    </button>
                    <button class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black bg-opacity-40 text-white rounded-full flex items-center justify-center z-10">
                        <span>&gt;</span>
                    </button>
                    
                    <!-- Pagination dots -->
                    <div class="carousel-dots absolute bottom-2 left-0 right-0 flex justify-center gap-2">
                        <span class="dot w-2 h-2 rounded-full bg-pink-300"></span>
                        <span class="dot w-2 h-2 rounded-full bg-gray-300"></span>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">ลายน้ำ</h3>
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-yellow-500">เหลือ : 30 ชิ้น</p>
                        <span class="bg-yellow-400 text-white px-3 py-1 rounded-md">฿5</span>
                    </div>
                    <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-md flex justify-center items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                        </svg>
                        <span>สั่งซื้อตอนนี้เลย</span>
                    </button>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <img src="/api/placeholder/product/readawrite.png" alt="Product Example" class="w-full h-auto">
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">เติมเหรียญนิยาย ReadAwrite</h3>
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-yellow-500">เหลือ : 30 ชิ้น</p>
                        <span class="bg-yellow-400 text-white px-3 py-1 rounded-md">฿5</span>
                    </div>
                    <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-md flex justify-center items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                        </svg>
                        <span>สั่งซื้อตอนนี้เลย</span>
                    </button>
                </div>
            </div>
    <script>
        // Carousel functionality
        document.addEventListener('DOMContentLoaded', function() {
            const carousels = document.querySelectorAll('.product-carousel');
            
            carousels.forEach(carousel => {
                const track = carousel.querySelector('.slides-track');
                const slides = carousel.querySelectorAll('.slide-item');
                const nextButton = carousel.querySelector('.carousel-next');
                const prevButton = carousel.querySelector('.carousel-prev');
                const dots = carousel.querySelectorAll('.dot');
                
                let currentIndex = 0;
                const slideWidth = 100; // percentage
                
                // Set initial state
                updateCarousel();
                
                // Update carousel position based on currentIndex
                function updateCarousel() {
                    track.style.transform = `translateX(-${currentIndex * slideWidth}%)`;
                    
                    // Update dots
                    dots.forEach((dot, index) => {
                        if (index === currentIndex) {
                            dot.classList.add('bg-pink-300');
                            dot.classList.remove('bg-gray-300');
                        } else {
                            dot.classList.add('bg-gray-300');
                            dot.classList.remove('bg-pink-300');
                        }
                    });
                }
                
                // Next slide
                nextButton.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % slides.length;
                    updateCarousel();
                });
                
                // Previous slide
                prevButton.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                    updateCarousel();
                });
                
                // Click on dots
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentIndex = index;
                        updateCarousel();
                    });
                });
            });
        });
    </script>

            
            

<!-- JavaScript for Product Gallery Functionality -->
<script>
    // Function to change gallery image
    function changeGalleryImage(dotElement, imageIndex) {
        // Get container and images
        const container = dotElement.closest('.product-card').querySelector('.product-gallery-container');
        const images = container.querySelectorAll('.product-gallery-image');
        const dots = container.parentElement.querySelectorAll('.gallery-dot');
        
        // Hide all images and deactivate all dots
        images.forEach(img => img.classList.add('hidden'));
        images.forEach(img => img.classList.remove('showing'));
        dots.forEach(dot => dot.classList.remove('active'));
        dots.forEach(dot => dot.classList.add('bg-opacity-40'));
        
        // Show selected image and activate corresponding dot
        images[imageIndex].classList.remove('hidden');
        images[imageIndex].classList.add('showing');
        dots[imageIndex].classList.add('active');
        dots[imageIndex].classList.add('bg-opacity-70');
    }
    
    // Initialize automatic gallery slideshow
    document.addEventListener('DOMContentLoaded', function() {
        const autoGalleries = document.querySelectorAll('.product-auto-gallery-container');
        
        autoGalleries.forEach(gallery => {
            const images = gallery.querySelectorAll('.product-auto-image');
            let currentIndex = 0;
            
            // Set interval for slideshow
            setInterval(() => {
                images.forEach(img => img.classList.remove('opacity-100'));
                images.forEach(img => img.classList.add('opacity-0'));
                
                currentIndex = (currentIndex + 1) % images.length;
                
                images[currentIndex].classList.remove('opacity-0');
                images[currentIndex].classList.add('opacity-100');
            }, 3000);
        });
        
        // Initialize AOS animation if not already done
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    });
</script>

<!-- Additional Styles for Gallery -->
<style>
    .product-gallery-image.showing {
        display: block;
    }
    
    .product-gallery-image.hidden {
        display: none;
    }
    
    .gallery-dot.active {
        transform: scale(1.5);
    }
    
    .product-auto-image {
        transition: opacity 0.7s ease-in-out;
    }
</style>

            <!-- More products can be added here following the same pattern -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-yellow-500 text-white mt-12 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-xl font-bold mb-4"><?php echo $fetchsettingwebsitedata['type1']; ?></h3>
					
                    <p class="max-w-md">👇เช็คคิวที่นี่</p>
					<a href='https://tinyurl.com/tayfan9c' class="text-white hover:text-yellow-200">
					<span class="material-symbols-outlined">arrow_selector_tool</span>
					
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">ติดต่อเรา</h3>
                    <p><?php echo $fetchsettingwebsitedata['type9']?> <?php echo $fetchsettingwebsitedata['type7']; ?></p>
                    <p><?php echo $fetchsettingwebsitedata['type10']?> <?php echo $fetchsettingwebsitedata['type8']; ?></p>
                    <div class="flex space-x-4 mt-4">
                        <a href="<?php echo $fetchsettingwebsitedata['type3']; ?>" class="text-white hover:text-yellow-200">
                            <span class="material-icons">facebook</span>
                        </a>
                    </div>
                    <div class="flex justify-end mt-6">
                </div>
                <a href="admin-login.php" class="inline-block bg-white text-yellow-500 font-medium py-2 px-6 rounded-lg hover:bg-yellow-100 transition-colors">
                สำหรับผู้ดูแลระบบ
            </a>
            </div>
        </div>          
        <div class="border-t border-yellow-400 mt-6 pt-6 text-center">                 
            <p>© 2025 <?php echo $fetchsettingwebsitedata['type1']; ?>. All rights reserved.</p>             
        </div>         
    </div>     
</footer>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.min.css" rel="stylesheet">
    
    <!-- AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Cart functionality
        let cart = [];

        function addToCart(productId) {
    // Get product details from the productDetails object
/*    const productDetails = {
        1: {
            name: 'การ์ดพิน',
            price: 18,
            stock: 30,
            description: '18 thb( การบรีฟ )หัวข้อ ()ชื่อ ( ใส่หรือไม่ใส่ก้ได้ ):ข้อความรอบๆงาน ข้อความในกรอบขาว',
            image: '/api/placeholder/400/300'
        },
        2: {
            name: 'ลายน้ำ',
            price: 5,
            stock: 30,
            description: 'โด 5',
            image: '/api/placeholder/400/300'
        },
        3: {
            name: 'เติมเหรียญนิยาย ReadAwrite',
            price: 5,
            stock: 30,
            description: 'รับเติมเหรียญนิยาย ReadAwrite',
            image: '/api/placeholder/400/300'
        },
    };
    
    const product = productDetails[productId];
    
    if (!product) return;
    
*/    // Simulate adding to cart with the actual product name and price
    cart.push({
        id: productId,
        name: product.name,
        price: product.price,
        quantity: 1
    });
    
    // Show success message with SweetAlert
    Swal.fire({
        title: 'เพิ่มสินค้าสำเร็จ!',
        text: `เพิ่ม "${product.name}" ลงในตะกร้าแล้ว`,
        icon: 'success',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#FFCD00'
    });
}

        // Simulated checkout process
        function checkout() {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'ตะกร้าว่างเปล่า',
                    text: 'กรุณาเลือกสินค้าก่อนทำการสั่งซื้อ',
                    icon: 'info',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#FFCD00'
                });
                return;
            }

            // Show loading during backend processing
            Swal.fire({
                title: 'กำลังดำเนินการ',
                text: 'กรุณารอสักครู่...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate backend processing
            setTimeout(() => {
                Swal.fire({
                    title: 'สั่งซื้อสำเร็จ!',
                    text: 'ขอบคุณสำหรับการสั่งซื้อ คุณจะได้รับอีเมลยืนยันการสั่งซื้อเร็วๆ นี้',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#FFCD00'
                });
                
                // Clear cart after successful purchase
                cart = [];
            },
            2000);
        }

        // View cart function
        function viewCart() {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'ตะกร้าว่างเปล่า',
                    text: 'คุณยังไม่มีสินค้าในตะกร้า',
                    icon: 'info',
                    confirmButtonText: 'เลือกซื้อสินค้า',
                    confirmButtonColor: '#FFCD00'
                });
                return;
            }

            // Calculate total
            let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            // Create cart content HTML
            let cartHTML = '<div class="text-left">';
            cart.forEach((item, index) => {
                cartHTML += `
                <div class="flex justify-between items-center border-b pb-2 mb-2">
                    <div>
                        <h4 class="font-medium">${item.name}</h4>
                        <p class="text-sm text-gray-600">฿${item.price} x ${item.quantity}</p>
                    </div>
                    <div>
                        <button onclick="removeItem(${index})" class="text-red-500 text-sm">ลบ</button>
                    </div>
                </div>`;
            });
            cartHTML += `
                <div class="font-bold mt-4 text-right">
                    รวมทั้งหมด: ฿${total}
                </div>
            </div>`;

            Swal.fire({
                title: 'ตะกร้าสินค้า',
                html: cartHTML,
                showCancelButton: true,
                confirmButtonText: 'ชำระเงิน',
                cancelButtonText: 'ปิด',
                confirmButtonColor: '#FFCD00',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                icon: "error",
                title: "การสั่งซื้อผิดพลาด!",
                text: "การสั่งซื้อเกิดปัญหา! โปรดติดต่อ dm ig: drawi.ngfamily เพื่อสั่งซื้อ",
                footer: "<a href='https://www.instagram.com/drawi.ngfamily/' target='_blank'>ติดต่อสั่งซื้อ</a>",
            });
                }
            });
        }

        // Remove item from cart
        function removeItem(index) {
            cart.splice(index, 1);
            viewCart();
        }

        // Function to handle user login
        

        // Function to handle registration
        

        // Function to handle product details view
        // First, update the buy buttons to call viewProductDetails function
document.addEventListener('DOMContentLoaded', function() {
    // Get all "สั่งซื้อตอนนี้เลย" (Order Now) buttons and add data-product-id
    const orderButtons = document.querySelectorAll('.product-card button:last-child');
    
    // Add click event listeners to each button and set data-product-id
    orderButtons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            // Prevent default button behavior
            e.preventDefault();
            // Stop event propagation to prevent card click from triggering
            e.stopPropagation();
            // Call viewProductDetails with the product ID (index+1)
            const productId = index + 1;
            addToCart(productId);
        });
    });
    
    // The rest of your existing DOMContentLoaded code...
    // Add click listeners to product cards for detailed view
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.addEventListener('click', function(e) {
            // Prevent event from bubbling to the buy button
            if (!e.target.closest('button:last-child')) {
                viewProductDetails(index + 1);
            }
        });
    });

    // Add cart icon to navbar
    const nav = document.querySelector('nav .container');
    const cartButton = document.createElement('button');
    cartButton.className = 'ml-4 text-yellow-500 hover:text-yellow-600';
    cartButton.innerHTML = '<span class="material-icons">shopping_cart</span>';
    cartButton.onclick = viewCart;
    
    const profileButton = document.querySelector('nav .container button');
    profileButton.parentNode.insertBefore(cartButton, profileButton);

    // Handle profile button click
    profileButton.addEventListener('click', function() {
    });
});

// Keep the viewProductDetails function as is and use the same data from addToCart
//function viewProductDetails(productId) {
    // Example product data - in a real app, you'd fetch this from your database
/*    const productDetails = {
        1: {
            name: 'การ์ดพิน',
            price: 18,
            stock: 30,
            description: '18 thb( การบรีฟ )หัวข้อ ()ชื่อ ( ใส่หรือไม่ใส่ก้ได้ ):ข้อความรอบๆงาน ข้อความในกรอบขาว',
            image: '/api/placeholder/400/300'
        },
        2: {
            name: 'ลายน้ำ',
            price: 5,
            stock: 30,
            description: 'โด 5',
            image: '/api/placeholder/product/ลายน้ำ 206_20250314215453.png'
        },
        3: {
            name: 'เติมเหรียญนิยาย ReadAwrite',
            price: 5,
            stock: 30,
            description: 'เติมเหรียญนิยาย ReadAwrite เรท 1 ต่อ 1 คือ 1 เหรียญ mebcoin = 1 บาท ขั้นต่ำ 5 บาท',
            image: '/api/placeholder/product/readawrite.png'
        },
    };
    
    const product = productDetails[productId];
    
    if (!product) return;
    
    const html = `
    <div class="flex flex-col items-center">
        <img src="${product.image}" alt="${product.name}" class="mb-4 rounded-lg max-w-full h-auto">
        <div class="text-left w-full">
            <p class="mb-2">${product.description}</p>
            <p class="mb-2 text-yellow-500">เหลือ: ${product.stock} ชิ้น</p>
            <p class="font-bold text-lg">ราคา: ฿${product.price}</p>
        </div>
    </div>
    `;
*/    Swal.fire({
        title: product.name,
        html: html,
        showCancelButton: true,
        confirmButtonText: 'เพิ่มลงตะกร้า',
        cancelButtonText: 'ปิด',
        confirmButtonColor: '#FFCD00',
    }).then((result) => {
        if (result.isConfirmed) {
            addToCart(productId);
        }
    });
}
    </script>
</body>
</html>
