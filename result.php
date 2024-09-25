<html>

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            text-align: center;
            justify-content: center;
        }
        /* 画像中央 */
        .center-image {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body class="bg-gray-50">
    <header class="flex items-center p-4 bg-white shadow-md">
        <a href="./index.php">
            <h1 class="text-secondary text-4xl font-bold">Ski♡Skin</h1>
        </a>
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
                    <li><br><img class="center-image" src="data:image/jpeg;base64,<?= $imageData ?>" alt="Lotion Image" style="max-width:200px;"></li>
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
                    <li><span class="text-xl font-bold"><?= htmlspecialchars($componentData['name'], ENT_QUOTES, 'UTF-8') ?></span></li>
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
                        <div class="collapse bg-base-200">
                            <!-- すべてのラジオボタンに同じ name 属性を設定 -->
                            <input type="radio" name="accordion" id="accordion-<?= $index ?>" />

                            <!-- nameをクリックで展開 -->
                            <?php if (isset($row['name'])): ?>
                                <div class="collapse-title text-xl font-bold"><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></div>
                            <?php endif; ?>

                            <!-- descriptionを表示 -->
                            <?php if (isset($row['description'])): ?>
                                <div class="collapse-content">
                                    <p><?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?></p>

                                    <!-- 相性の良い理由を追加 -->
                                    <h4 class="mt-2 text-lg font-semibold">相性の良い理由</h4>
                                    <p>
                                        <?php
                                        // 相性の良い理由（chemistryデータのdescription）を表示
                                        $reason = array_filter($ChemistryData, function($chem) use ($row) {
                                            return $row['id'] == $chem['no2'];
                                        });
                                        if (!empty($reason)) {
                                            echo htmlspecialchars(array_values($reason)[0]['description'], ENT_QUOTES, 'UTF-8');
                                        }
                                        ?>
                                    </p>

                                    <!-- 乳液を表示 -->
                                    <h4 class="mt-4 text-lg font-semibold">相性の良い乳液</h4>
                                    <ul>
                                        <?php foreach ($milkData as $milkIndex => $milkRow): ?>
                                            <?php if ($milkRow['component'] == $row['id']): ?>
                                                <li class="mt-2">
                                                    <?= htmlspecialchars($milkRow['name'], ENT_QUOTES, 'UTF-8') ?>
                                                    <?php if (isset($milkRow['image'])): 
                                                        $imageData = base64_encode($milkRow['image']); ?>
                                                        <br><img class="center-image mt-2" src="data:image/jpeg;base64,<?= $imageData ?>" alt="Milk Image" style="max-width:200px;">
                                                    <?php endif; ?>
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

        <?php } // このifをここで閉じる ?>
    </div>
</body>

</html>
