<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/751f7f0eaa.js" crossorigin="anonymous"></script>


<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <strong>Store</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="#" class="nav-link active">Catalogo</a>
          </li>
          <li class="nav-item">
            <a href="https://wa.me/+573222437765" target="_blank" class="nav-link active">Contacto</a>
          </li>
        </ul>

        <a href="checkout.php" class="btn btn-primary btn-sm me-2">Carrito <span id="num_cart" class="badge bg-secondary"><i class="fas fa-shopping-cart"></i> <?php echo $num_cart; ?></span></a>

        <?php if (isset($_SESSION['user_id'])) { ?>

          <div class="dropdown">
            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user" style="margin-right: 5px;"></i> <?php echo $_SESSION['user_name']; ?>

            </button>
            <ul class="dropdown-menu" aria-labelledby="btn_session">
              <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
              <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
          </div>

        <?php } else { ?>
          <a href="login.php" class="btn btn-success btn-sm"><i class="fas fa-user"></i>Ingresar</a>
        <?php } ?>
      </div>
    </div>
  </div>
</header>