<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Winny Store</title>
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Firebase App -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js"></script>
    <style>
        body {
            background-color: #f9fafb;
        }
        .dashboard-card {
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-yellow-500 shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <h1 class="text-white text-lg font-bold">Winny Store Admin Panel</h1>
            <button id="logoutButton" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Logout</button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-20 pb-10">
        <div class="container mx-auto px-4">
            <!-- Section: Website Colors -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Website Colors</h2>
                <form id="colorForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Primary Color</label>
                        <input type="color" id="primaryColor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Secondary Color</label>
                        <input type="color" id="secondaryColor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <button type="submit" class="col-span-2 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Update Colors</button>
                </form>
            </div>

            <!-- Section: Product Management -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Product Management</h2>
                <div id="productList" class="space-y-4">
                    <!-- Products will be dynamically loaded here -->
                </div>
                <button id="addProductButton" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md mt-4">Add New Product</button>
            </div>

            <!-- Section: Revenue & Profit -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Revenue & Profit</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-bold text-gray-800">Total Revenue</h3>
                        <p id="totalRevenue" class="text-2xl text-yellow-500">$0.00</p>
                    </div>
                    <div class="dashboard-card bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-bold text-gray-800">Total Profit</h3>
                        <p id="totalProfit" class="text-2xl text-green-500">$0.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Add/Edit Product</h2>
            <form id="productForm" class="space-y-4">
                <input type="hidden" id="productId">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" id="productName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Price</label>
                    <input type="number" step="0.01" id="productPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" id="productStock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Image URL</label>
                    <input type="url" id="productImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md">Save Product</button>
                <button type="button" id="closeModalButton" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Firebase Configuration -->


        // Load Products
        function loadProducts() {
            db.collection('products').get().then(snapshot => {
                const productList = document.getElementById('productList');
                productList.innerHTML = '';
                snapshot.forEach(doc => {
                    const product = doc.data();
                    productList.innerHTML += `
                        <div class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">${product.name}</h3>
                                <p class="text-gray-600">Price: $${product.price} | Stock: ${product.stock}</p>
                            </div>
                            <div class="space-x-2">
                                <button onclick="editProduct('${doc.id}')" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md">Edit</button>
                                <button onclick="deleteProduct('${doc.id}')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md">Delete</button>
                            </div>
                        </div>
                    `;
                });
            });
        }

        // Add/Edit Product
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = document.getElementById('productId').value;
            const productData = {
                name: document.getElementById('productName').value,
                price: parseFloat(document.getElementById('productPrice').value),
                stock: parseInt(document.getElementById('productStock').value),
                image: document.getElementById('productImage').value
            };

            if (productId) {
                db.collection('products').doc(productId).update(productData).then(() => {
                    closeModal();
                    loadProducts();
                });
            } else {
                db.collection('products').add(productData).then(() => {
                    closeModal();
                    loadProducts();
                });
            }
        });

        // Delete Product
        function deleteProduct(id) {
            db.collection('products').doc(id).delete().then(() => {
                loadProducts();
            });
        }

        // Edit Product
        function editProduct(id) {
            db.collection('products').doc(id).get().then(doc => {
                const product = doc.data();
                document.getElementById('productId').value = doc.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock;
                document.getElementById('productImage').value = product.image;
                openModal();
            });
        }

        // Open/Close Modal
        function openModal() {
            document.getElementById('productModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productForm').reset();
        }

        // Logout
        document.getElementById('logoutButton').addEventListener('click', function() {
            firebase.auth().signOut().then(() => {
                window.location.href = 'login.php';
            });
        });

        // Load Data on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });
    </script>
</body>
</html>