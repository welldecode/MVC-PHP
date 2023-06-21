<!DOCTYPE html>
<html lang="pt-br"> 
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/assets/css/utils.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="/public/assets/css/style.css?v=<?php echo time(); ?>">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <title><?php echo $this->getTitlePage(); ?></title>
</head>

<body>
  <header>
    <div class="container">
      <div class="menu_nav">
        <div class="menu_logo">
          <img src="/public/assets/img/logo.png" alt="">
        </div>
        <nav>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">List</a></li>
            <li><a href="#">Browse</a></li>
          </ul>
        </nav>
        <div class="menu_right">
          <div class="search_content">
          <i class="ri-search-2-line"></i>
          </div>
          <a href="#" class="btn_login">
            Login
          </a>
        </div>
      </div>
    </div>
  </header>
  
  <?php echo $this->viewContent(); ?>

</body> 
</html>