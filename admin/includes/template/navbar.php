<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang('home_admin') ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo lang('categories') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo lang('items') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"><?php echo lang('members') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php"><?php echo lang('comments') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('statistics') ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo lang('logs') ?></a>
        </li>
    </ul>
      <ul class="nav navbar-nav ms-auto ">
        <li class="nav-item dropdown d-flex flex-row-reverse">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php  echo  $_SESSION['Username'] ;?>
          </a>
      
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="../index.php">Visit Shop</a></li>
              <li><a class="dropdown-item" href="members.php?action=edit&userid=<?php echo $_SESSION['ID']?>">Edit Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </ul>   
    </div>
  </div>
</nav>