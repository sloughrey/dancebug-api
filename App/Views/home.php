<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <title>DRC Video / Dancebug Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Syncopate' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
</head>
<body>
    <div class="container" id="container">
        <header id="site-header">
            <h2 id="site-name">DRC Video / Dancebug API Test</h2>
            <h4 id="site-author">By: Sean Loughrey</h4>
        </header>
        <nav>

        </nav>
        <main id="site-main">
            <section id="create-user" class="col-lg-6 panel">
                <?php $addEdit = (isset($editUserID)) ? 'Edit' : 'Create New '; ?>
                <h4 class="section-title" id="user-form-title"><?= $addEdit; ?> User</h4>
                <div id="create-user-container">
                    <div class="form-group">
                        <label for="studio-name">Studio Name</label>
                        <input name="studioName" type="text" class="form-control" id="studio-name" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['studioName']); } ?>">
                    </div>
                    <div class="form-group">
                        <label for="studio-id">Studio ID</label>
                        <input name="studioID" type="text" class="form-control" id="studio-id" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['studioID']); } ?>">
                    </div>
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input name="firstName" type="text" class="form-control" id="first-name" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['firstName']); } ?>">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input name="lastName" type="text" class="form-control" id="last-name" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['lastName']); } ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <!-- <input name="gender" type="text" class="form-control" id="gender" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['gender']); } ?>"> -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="gender-dd-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a id="gender-male" class="dropdown-item">Male</a>
                                <a id="gender-female" class="dropdown-item">Female</a>
                            </div>
                        </div>
                        <input id="gender-val" name="gender" type="hidden" value="">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input name="dob" type="text" class="form-control" id="dob" placeholder="yyyy-mm-dd" value="<?php if (isset($editUser)) { echo htmlspecialchars($editUser['dob']); } ?>">
                    </div>
                    <footer>
                        <input id="user-edit-id" name="editUserID" type="hidden" value="<?php if (isset($editUserID)) { echo $editUserID; } ?>">
                        
                        <button id="create-user-btn" class="submit-btn btn btn-primary">Submit</button>
                        <button id="clear-user-btn" class="clear-btn btn btn-secondary">Clear</button>
                    </footer>
                </div>
            </section>
            <section id="entity-list-container" class="col-lg-6 panel">
                <h4 class="section-title">Show User by ID</h4>
                <div id="entity-list">
                    <ul class="list-group"><?php
                    foreach ($users as $user) { ?>
                        <li class="list-group-item">
                            <span><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></span>
                            <i data-id="<?php echo $user['DancerID']; ?>" class="entity-edit-btn fas fa-search fa-2x"></i>
                        </li><?php
                    } ?>
                    </ul>
                </div>
            </section>
        </main>
        <footer>

        </footer>
    </div>
    <script src="/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>