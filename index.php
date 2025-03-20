<?php include 'header.php'; ?>



<!-- Основной баннер страницы -->
<section class="hero">
    <div class="main-first-left">
        <h1>The widest range of e-cigarettes</h1>
        <p>Open the door to safe and stylish vaping with our high quality products and the widest selection.</p>
        <a href="products.php" class="btn">Product</a>
    </div>
    <div class="main-first-right">
        <img src="assets/two-vapes.png" alt="E-Cigarretes">
    </div>
</section>

<section class="products-section">
    <a class="card" href="products.php?type%5B%5D=vape">
        <img src="assets/product-vape.png" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="btn btn-primary open-product products-buttons">
                E-Cigarettes <span></span> <img src="assets/right-arrow.png">
            </p>
        </div>
    </a>
    <a class="card" href="products.php?type%5B%5D=liquids">
        <img src="assets/product-liquid.png" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="btn btn-primary open-product products-buttons">
                Liquids <span></span> <img src="assets/right-arrow.png">
            </p>
        </div>
    </a>
    <a class="card" href="products.php?type%5B%5D=snus">
        <img src="assets/product-snus.png" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="btn btn-primary open-product products-buttons">
                Snus <span></span> <img src="assets/right-arrow.png">
            </p>
        </div>
    </a>
    <a class="card" href="products.php">
        <img src="assets/product-snus.png" class="card-img-top" alt="...">
        <div class="card-body">
            <p class="btn btn-primary open-product products-buttons">
                All products <span></span> <img src="assets/right-arrow.png">
            </p>
        </div>
    </a>
</section>

<div class="container delivery-section">
    <div class="row align-items-center">
        <!-- Левая колонка с изображением коробок -->
        <div class="col-md-5 mb-4 mb-md-0">
            <img src="assets/packages.jpg" alt="Boxes" class="img-fluid" style="margin: 0 auto;display: flex">
        </div>

        <!-- Правая колонка с заголовком, описанием и вариантами доставки -->
        <div class="col-md-7">
            <h2>Delivery</h2>
            <p class="subtitle">
                Open the door to safe and stylish vaping with our high quality products
                and the widest selection.
            </p>

            <!-- Варианты доставки (3 карточки в одном ряду) -->
            <div class="row text-center row-cols-3">
                <div class="col delivery-option background-gray">
                    <img src="assets/plane-china.png" alt="Plane from China">
                    <p>Delivery by plane from Germany</p>
                </div>
                <div class="col delivery-option background-gray">
                    <img src="assets/germany-plane.png" alt="Plane from Germany">
                    <p>Delivery by plane from Germany</p>
                </div>
                <div class="col delivery-option background-gray">
                    <img src="assets/ship.png" alt="Ship from China">
                    <p>Delivery by sea from China</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="container payments-section">
    <div class="row align-items-center">
        <div class="col-md-1"></div>
        <!-- Левая колонка: заголовок, подзаголовок и варианты оплаты -->
        <div class="col-md-6">
            <h2>Payments</h2>
            <p class="subtitle">
                Open the door to safe and stylish vaping with our high quality products
                and the widest selection.
            </p>

            <!-- Блок с двумя вариантами оплаты -->
            <div class="row g-3">
                <!-- Оплата картой -->
                <div class="col-md-6">
                    <div class="payment-option">
                        <img src="assets/pay-card.png" alt="Payment by card">
                    </div>
                </div>
                <!-- Оплата криптовалютой -->
                <div class="col-md-6">
                    <div class="payment-option">
                        <img src="assets/pay-crypto.png" alt="Payment with crypto">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>

        <!-- Правая колонка: изображение телефона -->
        <div class="col-md-4 text-center">
            <img src="assets/iphone.png" alt="Phone" class="img-fluid">
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>
