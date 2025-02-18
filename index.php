<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jay Montclaire - Fine Art</title>
    <link rel="stylesheet" href="styles.css"
    >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Georgia', serif;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 50px;
    background: white;
    font-size: 18px;
}

.nav-links a {
    margin-right: 20px;
    text-decoration: none;
    color: black;
}

.logo {
    font-size: 22px;
    font-weight: bold;
}

.cart {
    font-size: 20px;
}

/* Hero Section */
.hero {
    text-align: center;
    margin: 100px 0;
    font-size: 36px;
    font-weight: lighter;
}

/* Image Gallery */
.gallery {
    display: flex;
    justify-content: center;
    gap: 100px;
    padding: 20px;
}

.gallery img {
    width: 350px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}




        .image-container {
            position: relative;
            width: 300px; /* Adjust size as needed */
            height: 200px;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease-in-out;
        }

        .image-container:hover img {
            opacity: 0.5;
        }

        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            font-size: 20px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .image-container:hover .overlay-text {
            opacity: 1;
        }
        </style>

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-links">
       <a href="inddex.php"> <i class="fa-solid fa-user"></i></a>
            <a href="#">All Prints</a>
            <a href="#">About</a>
        </div>
        <div class="logo">  LegalAI</div>
        <div class="cart"></div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        
        <h1>Your Smart Legal Assistant.</h1>
    </header>

    <!-- Image Gallery -->
    

    <section class="gallery">


    <div class="image-container">
            <img src="1.jpg" alt="Image 3">
            <div class="overlay-text">AI lawyers enhance speed, not judgment.
</div>
        </div>
    </div>



     
    <div class="image-container">
            <img src="2.jpg" alt="Image 3">
            <div class="overlay-text">
Technology aids, but human insight leads.
</div>
        </div>
    </div>
       
    
    <div class="image-container">
            <img src="3.webp" alt="Image 3">
            <div class="overlay-text">AI lawyers streamline, humans decide.</div>
        </div>
    </div>
    </section>

</body>
</html>
