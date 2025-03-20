<?php
//products.php

// Массив с продуктами (описан вручную)
$products = [];

// Список прилагательных для имен
$adjectives = ['Alpha', 'Beta', 'Gamma', 'Delta', 'Epsilon', 'Zeta', 'Eta', 'Theta', 'Iota', 'Kappa'];
// Список вкусов из первых 10 вариантов
$flavors = ['mint', 'apple', 'strawberry', 'banana', 'cherry', 'orange', 'lemon', 'grape', 'watermelon', 'blueberry'];

for ($i = 1; $i <= 50; $i++) {
    // Определяем тип: по циклу (vape, liquid, snus)
    if ($i % 3 === 1) {
        $type = 'vape';
    } elseif ($i % 3 === 2) {
        $type = 'liquid';
    } else {
        $type = 'snus';
    }

    // Бренд: нечетное — vozol, четное — gnidavape
    $brand = ($i % 2 === 1) ? 'vozol' : 'gnidavape';

    // Имя: составляем из типа и прилагательного (циклично по списку)
    $adjective = $adjectives[($i - 1) % count($adjectives)];
    $name = ucfirst($type) . " " . $adjective;

    // Цена: вычисляем с десятичными значениями, округляя до двух знаков
    $price = round(20 + $i * 3.47, 2);

    // Puffs: уникальное значение, начиная с 200 и увеличивая на 50 для каждого товара
    $puffs = 200 + ($i - 1) * 50;

    // Вкус: выбираем циклично из списка вкусов
    $flavor = $flavors[($i - 1) % count($flavors)];

    $products[] = [
        'id'      => $i,
        'name'    => $name,
        'price'   => $price,
        'type'    => $type,
        'brand'   => $brand,
        'image'   => 'assets/product-example.png',
        'puffs'   => $puffs,
        'flavor'  => $flavor
    ];
}
// Получаем фильтры из URL (если они заданы)
$typeFilter   = isset($_GET['type'])   ? array_map('strtolower', $_GET['type'])   : ['all'];
$brandFilter = isset($_GET['brand']) ? array_map('strtolower', $_GET['brand']) : ['all'];
$puffFilter = isset($_GET['puffs']) ? array_map('strtolower', $_GET['puffs']) : ['all'];
$flavorFilter = isset($_GET['flavor']) ? array_map('strtolower', $_GET['flavor']) : ['all'];
$priceMin      = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$priceMax      = isset($_GET['price_max']) ? $_GET['price_max'] : '';

// Фильтрация массива продуктов
$filteredProducts = array_filter($products, function($product) use ($typeFilter, $brandFilter, $puffFilter, $flavorFilter, $priceMin, $priceMax) {
    $matchType   = (in_array('all', $typeFilter)   || in_array(strtolower($product['type']), $typeFilter));
    $matchBrand  = (in_array('all', $brandFilter)  || in_array(strtolower($product['brand']), $brandFilter));
    $matchPuffs  = (in_array('all', $puffFilter)   || in_array((string)$product['puffs'], $puffFilter));
    $matchFlavor = (in_array('all', $flavorFilter) || in_array(strtolower($product['flavor']), $flavorFilter));

    $matchPrice = true;
    if ($priceMin !== '') {
        $matchPrice = $matchPrice && ($product['price'] >= $priceMin);
    }
    if ($priceMax !== '') {
        $matchPrice = $matchPrice && ($product['price'] <= $priceMax);
    }

    return $matchType && $matchBrand && $matchPuffs && $matchFlavor && $matchPrice;
});

// Сортировка (по цене или по названию)
$sort = isset($_GET['sort']) ? strtolower($_GET['sort']) : '';

switch ($sort) {
    case 'price_asc':
        usort($filteredProducts, function($a, $b) {
            return $a['price'] <=> $b['price'];
        });
        break;
    case 'price_desc':
        usort($filteredProducts, function($a, $b) {
            return $b['price'] <=> $a['price'];
        });
        break;
    case 'name_asc':
        usort($filteredProducts, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        break;
    case 'name_desc':
        usort($filteredProducts, function($a, $b) {
            return strcmp($b['name'], $a['name']);
        });
        break;
    default:
        usort($filteredProducts, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
}

if ($sort === 'price') {
    usort($filteredProducts, function($a, $b) {
        return $a['price'] <=> $b['price'];
    });
} elseif ($sort === 'name') {
    usort($filteredProducts, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
}

// 1) Определяем, сколько товаров выводить на страницу
$itemsPerPage = 24;

// 2) Определяем, какой номер страницы сейчас запрошен (по умолчанию 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// 3) Считаем общее количество товаров
$totalItems = count($filteredProducts);

// 4) Считаем, сколько будет всего страниц
$totalPages = ceil($totalItems / $itemsPerPage);

// 5) Индекс, с которого брать товары для текущей страницы
$startIndex = ($page - 1) * $itemsPerPage;

// 6) Берём срез массива товаров на текущую страницу
$pagedProducts = array_slice($filteredProducts, $startIndex, $itemsPerPage);

?>
<?php include 'header.php'; ?>

<div class="container my-4">
    <div class="row">
        <!-- Левая колонка: Фильтры -->

        <div class="col-md-3">
            <div class="search-bar">
                <h3>Search</h3>
                <input type="text" id="searchInput" class="form-control" placeholder="Write">
            </div>


            <form id="filterForm" method="GET" action="products.php">
                <div class="accordion" id="filterAccordion"  style="border-radius: 20px; background-color: #f8f9fa">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPrice">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="false" aria-controls="collapsePrice">
                                Price
                            </button>
                        </h2>
                        <div id="collapsePrice" class="accordion-collapse collapse" aria-labelledby="headingPrice">
                            <div class="accordion-body">
                                <div style="display: flex;flex-direction: row; gap: 3px">
                                    <div class="mb-2">
                                        <input type="number" name="price_min" id="priceMin" class="form-control" placeholder="Min Price" value="<?php echo isset($_GET['price_min']) ? htmlspecialchars($_GET['price_min']) : ''; ?>">
                                    </div>
                                    <div class="mb-2">
                                        <input type="number" name="price_max" id="priceMax" class="form-control" placeholder="Max Price" value="<?php echo isset($_GET['price_max']) ? htmlspecialchars($_GET['price_max']) : ''; ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%">Submit</button>
                            </div>
                        </div>
                    </div>
                    <!-- Brand -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingBrand">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
                                Brand
                            </button>
                        </h2>
                        <div id="collapseBrand" class="accordion-collapse collapse show" aria-labelledby="headingBrand">
                            <div class="accordion-body">
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="brandVozol">Vozol</label>
                                    <input class="form-check-input" type="checkbox" name="brand[]" value="vozol" id="brandVozol"
                                        <?php echo (isset($_GET['brand']) && in_array('vozol', $_GET['brand'])) ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="brandGnidavape">Gnidavape</label>
                                    <input class="form-check-input" type="checkbox" name="brand[]" value="gnidavape" id="brandGnidavape"
                                        <?php echo (isset($_GET['brand']) && in_array('gnidavape', $_GET['brand'])) ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Puffs -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPuffs">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePuffs" aria-expanded="false" aria-controls="collapsePuffs">
                                Puff
                            </button>
                        </h2>
                        <div id="collapsePuffs" class="accordion-collapse collapse" aria-labelledby="headingPuffs">
                            <div class="accordion-body">
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="puffs200">200</label>
                                    <input class="form-check-input" type="checkbox" name="puffs[]" value="200" id="puffs200"
                                        <?php echo (isset($_GET['puffs']) && in_array('200', $_GET['puffs'])) ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="puffs250">250</label>
                                    <input class="form-check-input" type="checkbox" name="puffs[]" value="250" id="puffs250"
                                        <?php echo (isset($_GET['puffs']) && in_array('250', $_GET['puffs'])) ? 'checked' : ''; ?>>
                                </div>
                                <!-- Add more puffs options as needed -->
                            </div>
                        </div>
                    </div>
                    <!-- Flavor -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFlavor">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFlavor" aria-expanded="false" aria-controls="collapseFlavor">
                                Flavor
                            </button>
                        </h2>
                        <div id="collapseFlavor" class="accordion-collapse collapse" aria-labelledby="headingFlavor">
                            <div class="accordion-body">
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="flavorMint">Mint</label>
                                    <input class="form-check-input" type="checkbox" name="flavor[]" value="mint" id="flavorMint"
                                        <?php echo (isset($_GET['flavor']) && in_array('mint', $_GET['flavor'])) ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="flavorApple">Apple</label>
                                    <input class="form-check-input" type="checkbox" name="flavor[]" value="apple" id="flavorApple"
                                        <?php echo (isset($_GET['flavor']) && in_array('apple', $_GET['flavor'])) ? 'checked' : ''; ?>>
                                </div>
                                <!-- Add more flavor options as needed -->
                            </div>
                        </div>
                    </div>
                    <!-- Type -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingType">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseType" aria-expanded="false" aria-controls="collapseType">
                                Type
                            </button>
                        </h2>
                        <div id="collapseType" class="accordion-collapse collapse" aria-labelledby="headingType">
                            <div class="accordion-body">
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="typeVape">Vape</label>
                                    <input class="form-check-input" type="checkbox" name="type[]" value="vape" id="typeVape"
                                        <?php echo (isset($_GET['type']) && in_array('vape', $_GET['type'])) ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="typeLiquid">Liquid</label>
                                    <input class="form-check-input" type="checkbox" name="type[]" value="liquid" id="typeLiquid"
                                        <?php echo (isset($_GET['type']) && in_array('liquid', $_GET['type'])) ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-check d-flex justify-content-between align-items-center no-padding">
                                    <label class="form-check-label" for="typeSnus">Snus</label>
                                    <input class="form-check-input" type="checkbox" name="type[]" value="snus" id="typeSnus"
                                        <?php echo (isset($_GET['type']) && in_array('snus', $_GET['type'])) ? 'checked' : ''; ?>>
                                </div>
                                <!-- Add more type options as needed -->
                            </div>
                        </div>

                    </div>
                    <!-- Кнопка сброса фильтров -->
                    <div class="mt-3">
                        <a href="products.php" class="btn btn-outline-secondary btn-sm" style="width: 100%;">Clear Filters</a>
                    </div>
                </div>
            </form>

            <script>
                document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                });
            </script>

        </div>

        <!-- Правая колонка: Сортировка и список продуктов -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Products</h4>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Sort by
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- Сортировка по цене -->
                        <li><a class="dropdown-item" href="products.php?sort=price_asc">Price (Low → High)</a></li>
                        <li><a class="dropdown-item" href="products.php?sort=price_desc">Price (High → Low)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <!-- Сортировка по названию -->
                        <li><a class="dropdown-item" href="products.php?sort=name_asc">Name (A → Z)</a></li>
                        <li><a class="dropdown-item" href="products.php?sort=name_desc">Name (Z → A)</a></li>
                    </ul>
                </div>
            </div>


            <div class="row">
                <?php if (count($pagedProducts) > 0): ?>
                    <?php foreach ($pagedProducts as $product): ?>
                        <!-- Ваш код вывода карточек товаров -->
                        <div class="col-md-3 mb-4">
                            <div class="product-card">
                                <div class="card-body">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="...">
                                    <h4 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                                    <div class="product-info">
                                        <p class="card-text space">Brand: <?php echo htmlspecialchars($product['brand']); ?></p>
                                        <p class="card-text space">
                                            <?php if ($product['type'] === 'vape') echo "Puffs: " . htmlspecialchars($product['puffs']); ?>
                                        </p>
                                        <p class="card-text space">Flavor: <?php echo htmlspecialchars($product['flavor']); ?></p>
                                    </div>
                                    <h3 class="card-text space"><?php echo htmlspecialchars($product['price']); ?> $ <img src="assets/right-arrow.png"></h3>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Нет продуктов по заданным фильтрам.</p>
                <?php endif; ?>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php
                        // Собираем GET-параметры заново, чтобы не потерять сортировку/фильтры
                        // но меняем параметр page на $i
                        $queryParams = $_GET;
                        $queryParams['page'] = $i;
                        $pageUrl = 'products.php?' . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo htmlspecialchars($pageUrl); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    // Для чекбоксов автосабмит
    document.querySelectorAll('.form-check-input').forEach(function(checkbox) {
        // Если чекбокс относится к текстовым полям цены, не автосабмитим
        if (checkbox.name.indexOf('price_') === -1) {
            checkbox.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        var searchQuery = this.value.toLowerCase();
        var productCards = document.querySelectorAll('.product-card');

        productCards.forEach(function(card) {
            var productName = card.querySelector('.card-title').textContent.toLowerCase();
            if (productName.includes(searchQuery)) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
</script>
<?php include 'footer.php'; ?>
