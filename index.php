<html data-theme="light"> <!-- ダークモード対策用 -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- スマホ表示対応用 -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" /> <!-- daisyui対応用 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet"> <!-- フォント対応用 -->
    <title>Ski♡Skin</title>
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: small;
        }
        .card img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-gray-50">
<header class="navbar bg-base-100 shadow-md">
  <div class="navbar-start"></div>
  <div class="navbar-center mx-auto">
    <a href="./index.php" class="btn btn-ghost normal-case text-4xl font-bold text-secondary">Ski♡Skin</a>
  </div>
  <div class="navbar-end"></div>
</header>
<div class="flex flex-col items-center mt-8 space-y-6 px-4">
  <div class="card bg-base-100 w-full max-w-md shadow-xl">
    <div class="card-body">
      <h2 class="card-title">お気に入りの化粧水がある</h2>
      <p>化粧水と相性の良い乳液を調べます</p>
      <div class="card-actions justify-end">
        <a href="./lotion.php"><button class="btn btn-primary">化粧水から選ぶ</button></a>
      </div>
    </div>
  </div>
  <div class="card bg-base-100 w-full max-w-md shadow-xl">
    <div class="card-body">
      <h2 class="card-title">お気に入りの乳液がある</h2>
      <p>乳液と相性の良い化粧水を調べます</p>
      <div class="card-actions justify-end">
        <a href="./milk.php"><button class="btn btn-primary">乳液から選ぶ</button></a>
      </div>
    </div>
  </div>
  <div class="card bg-base-100 w-full max-w-md shadow-xl">
    <div class="card-body">
      <h2 class="card-title">化粧水も乳液も決めていない</h2>
      <p>お肌のお悩みからおすすめの化粧水と相性の良い乳液を調べます</p>
      <div class="card-actions justify-end">
        <a href="./trouble.php"><button class="btn btn-primary">お悩みから選ぶ</button></a>
      </div>
    </div>
  </div>
</div>
</body>

</html>
