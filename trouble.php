<html data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: small;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .center-content {
            text-align: center;
            justify-content: center;
        }
        .accordion-content {
            font-size: large;
            text-align: center;
        }
        .section-title {
            font-size: 1rem;
            font-weight: normal;
            margin-bottom: 10px;
        }
        .content-header {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-image {
            display: block;
            margin: 0 auto;
            max-width: 150px;
        }
        .spacing {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .between-spacing {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        header {
            text-align: left;
            padding-left: 20px;
        }
        .description-text {
            font-size: 0.9rem;
            font-weight: normal;
            color: #4a4a4a;
            margin-top: 5px;
        }
    </style>
</head>

<body class="bg-gray-50">
    
<header class="navbar bg-base-100 shadow-md">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h7" />
        </svg>
      </div>
      <ul
        tabindex="0"
        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
        <li><a href="./lotion.php">化粧水から選ぶ</a></li>
        <li><a href="./milk.php">乳液から選ぶ</a></li>
        <li><a href="./trouble.php">お悩みから選ぶ</a></li>
      </ul>
    </div>
  </div>
  <div class="navbar-center mx-auto">
    <a href="./index.php" class="btn btn-ghost normal-case text-4xl font-bold text-secondary">Ski♡Skin</a>
  </div>

  <div class="navbar-end">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost btn-circle invisible"></div>
    </div>
  </div>
</header>

    <div class="text-center my-4">
        <h2 class="text-lg text-gray-600">あなたのお肌のお悩みは？</h2>
    </div>

    <div class="container mx-auto mt-8 p-4 bg-white shadow-lg rounded-lg">
        <?php
        // DB接続
        try {
            $pdo = new PDO('mysql:dbname=match;charset=utf8;host=localhost', 'root');
        } catch (PDOException $e) {
            exit('DB_CONNECT:' . $e->getMessage());
        }

        // componentデータを取得
        function getComponentsByClass($pdo, $classId) {
            $query1 = "SELECT * FROM component WHERE class = :class";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->bindValue(':class', $classId, PDO::PARAM_INT);
            $stmt1->execute();
            $components = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            return $components;
        }

        // lotionデータを取得
        function getLotionsByComponentId($pdo, $componentId) {
            $query2 = "SELECT * FROM lotion WHERE component = :component";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->bindValue(':component', $componentId, PDO::PARAM_INT);
            $stmt2->execute();
            $lotions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            return $lotions;
        }

        // 数値のclassに対応する表示名
        $classMapping = [
            1 => '美白',
            2 => 'ニキビ・毛穴ケア',
            3 => '保湿',
            4 => 'エイジングケア'
        ];
        ?>

        <div class="accordion">
            <?php foreach ($classMapping as $classId => $className): ?>
                <div class="collapse collapse-arrow bg-base-100 shadow-xl mb-4">
                    <input type="radio" name="accordion" id="accordion-<?= $classId ?>" class="peer" />
                    <label for="accordion-<?= $classId ?>" class="collapse-title text-xl font-bold peer-checked:bg-secondary peer-checked:text-black text-center">
                        <?= $className ?>
                    </label>
                    <div class="collapse-content peer-checked:block accordion-content">
                        <div class="text-lg text-gray-600 mb-6">
                            あなたのお悩みにぴったりの成分は
                        </div>
                        <ul class="list-disc list-inside">
                            <?php
                            $components = getComponentsByClass($pdo, $classId);
                            if (!empty($components)):
                                foreach ($components as $component):
                                    $lotions = getLotionsByComponentId($pdo, $component['id']);
                                    if (!empty($lotions)):
                                        ?>
                                        <li class="spacing">
                                            <strong class="content-header"><?= htmlspecialchars($component['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <p class="description-text"><?= htmlspecialchars($component['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                            <p class="between-spacing section-title">おすすめの化粧水は</p>
                                            <ul class="mt-2 flex flex-wrap justify-center gap-4">
    <?php foreach ($lotions as $lotion): ?>
        <li class="spacing">
            <div class="card bg-base-100 shadow-xl w-96">
                <figure>
                    <img class="h-48 w-full object-contain" style="max-height: 200px;" src="data:image/jpeg;base64,<?= base64_encode($lotion['image']) ?>" alt="<?= htmlspecialchars($lotion['name'], ENT_QUOTES, 'UTF-8') ?>">
                </figure>
                <div class="card-body">
                    <h2 class="card-title"><?= htmlspecialchars($lotion['name'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <div class="card-actions justify-end">
                        <a href="./lotionresult.php?id=<?= $lotion['id'] ?>">
                            <button class="btn btn-primary">詳細を見る</button>
                        </a>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>




                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>
