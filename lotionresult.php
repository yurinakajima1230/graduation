<html data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <title>Ski♡Skin</title>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            text-align: center;
            justify-content: center;
            }
        .center-image {
            display: block;
            margin: 0 auto;
            }
        .card img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            }
        .card {
            margin: 0 auto;
            max-width: 400px;
            }
        .collapse input:checked~.collapse-content {
            display: block;
            }
            .small-text {
        font-size: 0.8rem;
    }
    </style>
</head>
<body class="bg-gray-50">
    <header class="navbar bg-base-100 shadow-md">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a href="./lotion.php">化粧水から選ぶ</a></li>
                <li><a href="./milk.php">乳液から選ぶ</a></li>
                <li><a href="./trouble.php">お悩みから選ぶ</a></li>
                </ul>
            </div>
        </div>
        <div class="navbar-center mx-auto">
            <a href="./index.php" class="btn btn-ghost normal-case text-4xl font-bold text-secondary">Ski♡Skin</a>
        </div>
        <div class="navbar-end"></div>
    </header>
    <div class="container mx-auto mt-8 p-4 bg-white shadow-lg rounded-lg">
        <?php
        // DB接続
        try {
            $pdo = new PDO('mysql:dbname=match;charset=utf8;host=localhost', 'root');
        } catch (PDOException $e) {
            exit('DB_CONNECT:' . $e->getMessage());
        }
        // idを取得
        $id = $_GET['id'];

        // lotionテーブルからidデータを取得
        $query1 = "SELECT * FROM lotion WHERE id = :id";
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        $lotionData = $stmt1->fetch(PDO::FETCH_ASSOC);
        ?>


        <?php if ($lotionData): ?>
        <h3 class="mb-2">あなたが選んだ化粧水は</h3>
        <ul class="text-xl list font-bold list-inside">
            <?php if (isset($lotionData['name'])): ?>
            <li><?= htmlspecialchars($lotionData['name'], ENT_QUOTES, 'UTF-8') ?></li>
            <?php endif; ?>

            <?php if (isset($lotionData['image'])): 
                $imageData = base64_encode($lotionData['image']); ?>
            <li><br><img class="center-image" src="data:image/jpeg;base64,<?= $imageData ?>" alt="Lotion Image"
                    style="max-width:200px;"></li>
            <?php endif; ?>
        </ul>
        <?php else: ?>
        <p class="text-red-500">No data found in lotion table for ID: <?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php
        // lotionテーブルからcomponentデータを取得
        $component = $lotionData['component'] ?? null;
        // componentテーブルからデータを取得
        if ($component) {
            $query2 = "SELECT * FROM component WHERE id = :componentid";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->bindValue(':componentid', $component, PDO::PARAM_INT);
            $stmt2->execute();
            $componentData = $stmt2->fetch(PDO::FETCH_ASSOC);
        }
        ?>

        <?php if ($componentData): ?>
        <h3 class="mt-6 mb-2">最も多い成分は</h3>
        <ul class="list-inside">
            <?php if (isset($componentData['name'])): ?>
            <li><span class="text-xl font-bold"><?= htmlspecialchars($componentData['name'], ENT_QUOTES, 'UTF-8') ?></span>
            </li>
            <div class="mb-2"></div>
            <?php endif; ?>
            <?php if (isset($componentData['description'])): ?>
            <li><?= htmlspecialchars($componentData['description'], ENT_QUOTES, 'UTF-8') ?></li>
            <?php endif; ?>
        </ul>
        <?php else: ?>
        <p class="text-red-500">No component data found for component ID: <?= htmlspecialchars($component, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php
        // chemistryテーブルからデータを取得
        $query3 = "SELECT * FROM chemistry WHERE no1 = :no1";
        $stmt3 = $pdo->prepare($query3);
        $stmt3->bindValue(':no1', $component, PDO::PARAM_INT);
        $stmt3->execute();
        $ChemistryData = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        // chemistryテーブルのNo2データを取得
        $milkcomponentid = [];
        foreach ($ChemistryData as $row) {
            $milkcomponentid[] = $row['no2'];
        }
        ?>

        <?php
        // componentテーブルからmilkcomponentデータを取得
        if (!empty($milkcomponentid)) {
            $placeholders = implode(',', array_fill(0, count($milkcomponentid), '?'));
            $query4 = "SELECT * FROM component WHERE id IN ($placeholders)";
            $stmt4 = $pdo->prepare($query4);
            $stmt4->execute($milkcomponentid);
            $componentData = $stmt4->fetchAll(PDO::FETCH_ASSOC);

            // milkテーブルからデータを取得
            $query5 = "SELECT * FROM milk WHERE component IN ($placeholders)";
            $stmt5 = $pdo->prepare($query5);
            $stmt5->execute($milkcomponentid);
            $milkData = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if ($componentData): ?>
        <h3 class="mt-6 mb-2">相性の良い成分は</h3>
        <ul class="list-inside">
            <?php foreach ($componentData as $index => $row): ?>
            <li class="mb-6">
                <div class="collapse collapse-arrow bg-base-200">
                    <input type="radio" name="accordion-<?= $index ?>" id="accordion-<?= $index ?>" />

                    <?php if (isset($row['name'])): ?>
                    <div class="collapse-title text-xl font-bold"><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($row['description'])): ?>
                    <div class="collapse-content">
                        <p><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></p>

                        <h4 class="mt-2 text-lg font-semibold">相性の良い理由</h4>
                        <p class="small-text">
    <?php
    $reason = array_filter($ChemistryData, function($chem) use ($row) {
        return $row['id'] == $chem['no2'];
    });
    if (!empty($reason)) {
        $description = htmlspecialchars(array_values($reason)[0]['description'], ENT_QUOTES, 'UTF-8');
        $formattedDescription = str_replace('、', '、<br>', $description);
        echo $formattedDescription;
    }
    ?>
</p>
                        <h4 class="mt-4 text-lg font-semibold">相性の良い乳液</h4>
                        <ul>
                            <?php foreach ($milkData as $milkIndex => $milkRow): ?>
                            <?php if ($milkRow['component'] == $row['id']): ?>
                            <li class="mt-4">
                                <div class="card bg-base-100 shadow-xl">
                                    <figure>
                                        <?php if (isset($milkRow['image'])):
                                            $imageData = base64_encode($milkRow['image']); ?>
                                        <img class="center-image" src="data:image/jpeg;base64,<?= $imageData ?>" alt="Milk Image" />
                                        <?php endif; ?>
                                    </figure>
                                    <div class="card-body">
                                        <h2 class="card-title"><?= htmlspecialchars($milkRow['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                                        <div class="card-actions justify-end">
                                            <?php if (isset($milkRow['url'])): ?>
                                            <a href="<?= htmlspecialchars($milkRow['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                                <button class="btn btn-primary">公式HP</button>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p class="text-red-500">No related component data found.</p>
        <?php endif; ?>

        <?php }  ?>
    </div>
</body>

</html>
